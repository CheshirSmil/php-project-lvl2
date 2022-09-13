<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;

function genDiff($pathToFile1, $pathToFile2)
{
    $arr1 = parse($pathToFile1);
    $arr2 = parse($pathToFile2);

    $keys1 = array_keys(get_object_vars($arr1));
    $keys2 = array_keys(get_object_vars($arr2));
    $keys =  array_unique(array_merge($keys1, $keys2));

    sort($keys);

    $result = array_reduce(
        $keys,
        function ($carry, $key) use ($arr1, $arr2) {

            if (property_exists($arr1, $key) && property_exists($arr2, $key) && $arr1->$key === $arr2->$key) {
                $carry .= "    {$key}: " . json_encode($arr1->$key) . PHP_EOL;
            } else {
                if (property_exists($arr1, $key)) {
                    $carry .= "  - {$key}: " . json_encode($arr1->$key) . PHP_EOL;
                }
                if (property_exists($arr2, $key)) {
                    $carry .= "  + {$key}: " . json_encode($arr2->$key) . PHP_EOL;
                }
            }
            return $carry;
        },
        ''
    );

    return "{" . PHP_EOL . strtr($result, ['"' => '', '\"' => '"']) . "}" . PHP_EOL;
}
