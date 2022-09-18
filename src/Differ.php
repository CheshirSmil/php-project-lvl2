<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $obj1 = parse($pathToFile1);
    $obj2 = parse($pathToFile2);

    return format(makeDiffData($obj1, $obj2), $format);
}

function makeDiffData(object $obj1, object $obj2): array
{
    $keys1 = array_keys(get_object_vars($obj1));
    $keys2 = array_keys(get_object_vars($obj2));
    $keys =  array_unique(array_merge($keys1, $keys2));
    sort($keys);

    $result = array_map(
        fn ($key) => makeDiffNode($key, $obj1, $obj2),
        $keys
    );

    return $result;
}

function makeDiffNode(string $name, $expeted, $current): array
{
    $node = compact('name');
    //add
    if (!property_exists($expeted, $name)) {
        $node['currentValue'] = makeNode($current->$name);
        return $node;
    }
    //delete
    if (!property_exists($current, $name)) {
        $node['expectedValue'] = makeNode($expeted->$name);
        return $node;
    }
    //same
    if (is_object($expeted->$name) && is_object($current->$name)) {
        $node['children'] = makeDiffData($expeted->$name, $current->$name);
        return $node;
    } elseif ($expeted->$name === $current->$name) {
        $node['value'] = makeNode($current->$name);
        return $node;
    }
    //update
    $node['currentValue'] = makeNode($current->$name);
    $node['expectedValue'] = makeNode($expeted->$name);

    return $node;
}

function makeNode($data)
{
    if (!is_object($data)) {
        return $data;
    }

    $keys = array_keys(get_object_vars($data));
    sort($keys);

    $result = array_map(
        fn ($key) => is_object($data->$key) ?
            [
                'name' => $key,
                'children' => makeNode($data->$key)
            ] :
            [
                'name' => $key,
                'value' => makeNode($data->$key)
            ],
        $keys
    );
    return $result;
}
