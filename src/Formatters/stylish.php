<?php

namespace Differ\Formatters;

const TAB = "    ";

function stylish(array $data): string
{
    $iter = function (array $data, int $depth = 0) use (&$iter) {

        $indent = str_repeat(TAB, $depth);
        $lines = array_map(
            function ($item) use ($iter, $depth, $indent) {
                $key = getPrefix($item) . $item['name'];
                $itemValue = $item['value'];
                $value = is_array($itemValue) ? $iter($itemValue, ++$depth) : toString($itemValue);
                return $indent . $key . ": " . $value;
            },
            $data
        );
        $result = ['{', ...$lines, $indent . '}'];

        return implode(PHP_EOL, $result);
    };

    return $iter($data);
}

function getPrefix(array $data): string
{
    $option = $data['opt'] ?? null;
    switch ($option) {
        case 'added':
            return substr_replace(TAB, '+ ', -2);
        case 'deleted':
            return substr_replace(TAB, '- ', -2);
    }
    return TAB;
}

function toString($value): string
{
    return trim(json_encode($value), '"');
}
