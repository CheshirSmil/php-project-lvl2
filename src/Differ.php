<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\stylish;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $obj1 = parse($pathToFile1);
    $obj2 = parse($pathToFile2);

    if ($format === 'stylish') {
        return stylish(iter($obj1, $obj2));
    }
}

function iter(object $obj1, object $obj2)
{
    $keys1 = array_keys(get_object_vars($obj1));
    $keys2 = array_keys(get_object_vars($obj2));
    $keys =  array_unique(array_merge($keys1, $keys2));
    sort($keys);

    return array_reduce(
        $keys,
        function ($carry, $key) use ($obj1, $obj2) {
            if (!property_exists($obj1, $key)) {
                $value = makeValue($obj2->$key);
                $opt = 'added';
                $carry[] = makeNode($key, $value, $opt);
                return $carry;
            }
            if (!property_exists($obj2, $key)) {
                $value = makeValue($obj1->$key);
                $opt = 'deleted';
                $carry[] = makeNode($key, $value, $opt);
                return $carry;
            }
            if ($obj1->$key === $obj2->$key) {
                $value = makeValue($obj1->$key);
                $carry[] = makeNode($key, $value);
                return $carry;
            } elseif (is_object($obj1->$key) && is_object($obj2->$key)) {
                $value = iter($obj1->$key, $obj2->$key);
                $carry[] = makeNode($key, $value);
                return $carry;
            } else {
                $value1 = makeValue($obj1->$key);
                $value2 = makeValue($obj2->$key);
                $opt1 = 'deleted';
                $opt2 = 'added';
                $carry[] = makeNode($key, $value1, $opt1);
                $carry[] = makeNode($key, $value2, $opt2);
                return $carry;
            }
        },
        []
    );
}

function makeNode($name, $value, $opt = null)
{
    return $opt ? compact('opt', 'name', 'value') : compact('name', 'value');
}

function makeValue($data)
{
    if (!is_object($data)) {
        return $data;
    }

    return iter($data, $data);
}
