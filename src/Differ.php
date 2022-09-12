<?php

namespace Differ\Differ;

function readFile($path)
{
    $content = file_get_contents($path);

    return json_decode($content, true);
}

function genDiff($pathToFile1, $pathToFile2)
{
    $arr1 = readFile($pathToFile1);
    $arr2 = readFile($pathToFile2);

    $keys1 = array_keys($arr1);
    $keys2 = array_keys($arr2);
    $keys =  array_unique(array_merge($keys1, $keys2));

    sort($keys);

    $result = array_reduce(
        $keys,
        function ($carry, $key) use ($arr1, $arr2) {

            if (array_key_exists($key, $arr1) && array_key_exists($key, $arr2) && $arr1[$key] === $arr2[$key]) {
                $carry .= "    {$key}: " . json_encode($arr1[$key]) . PHP_EOL;
            } else {
                if (array_key_exists($key, $arr1)) {
                    $carry .= "  - {$key}: " . json_encode($arr1[$key]) . PHP_EOL;
                }
                if (array_key_exists($key, $arr2)) {
                    $carry .= "  + {$key}: " . json_encode($arr2[$key]) . PHP_EOL;
                }
            }
            return $carry;
        },
        ''
    );

    return "{" . PHP_EOL . strtr($result, ['"' => '', '\"' => '"']) . "}" . PHP_EOL;
}
