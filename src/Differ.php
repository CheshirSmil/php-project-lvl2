<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Functional\sort as f_sort;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $obj1 = parse($pathToFile1);
    $obj2 = parse($pathToFile2);

    return format(makeDiff($obj1, $obj2), $format);
}

function makeDiff(object $obj1, object $obj2): array
{
    $keys1 = array_keys(get_object_vars($obj1));
    $keys2 = array_keys(get_object_vars($obj2));
    $keys =  sortArrayValues(array_unique(array_merge($keys1, $keys2)));

    $result = array_map(
        fn ($key) => makeDiffNode($key, $obj1, $obj2),
        $keys
    );

    return $result;
}

function makeDiffNode(string $name, object $expeted, object $current): array
{
    //add
    if (!property_exists($expeted, $name)) {
        $currentValue = makeNode([$current->$name]);
        return compact('name', 'currentValue');
    }
    //delete
    if (!property_exists($current, $name)) {
        $expectedValue = makeNode([$expeted->$name]);
        return compact('name', 'expectedValue');
    }
    //same
    if (is_object($expeted->$name) && is_object($current->$name)) {
        $children = makeDiff($expeted->$name, $current->$name);
        $result = compact('name', 'children');
    } elseif ($expeted->$name === $current->$name) {
        $value = makeNode([$current->$name]);
        $result = compact('name', 'value');
    } else {
        //update
        $currentValue = makeNode([$current->$name]);
        $expectedValue = makeNode([$expeted->$name]);
        $result = compact('name', 'currentValue', 'expectedValue');
    }

    return $result;
}

function makeNode(array $arrayData)
{
    $data = $arrayData[0];

    if (!is_object($data)) {
        return $data;
    }

    $keys = sortArrayValues(array_keys(get_object_vars($data)));

    $result = array_map(
        fn ($key) => is_object($data->$key) ?
            [
                'name' => $key,
                'children' => makeNode([$data->$key])
            ] :
            [
                'name' => $key,
                'value' => makeNode([$data->$key])
            ],
        $keys
    );
    return $result;
}

function sortArrayValues(array $array): array
{
    return f_sort(
        $array,
        function ($left, $right) {
            return strcmp($left, $right);
        }
    );
}
