<?php

namespace Differ\Formatters;

const TAB = "    ";

function stylish($data, int $depth = 0): string
{
    if (!is_array($data)) {
        return toString($data);
    }

    $lines = array_map(
        fn ($item) => makeLine($item, $depth),
        $data
    );
    $result = ['{', ...$lines, str_repeat(TAB, $depth) . '}'];

    return implode(PHP_EOL, $result);
}

function makeLine(array $node, int $depth = 0): string
{
    $types = ['children', 'value', 'expectedValue', 'currentValue'];
    $key = $node['name'];
    $indent = str_repeat(TAB, $depth);

    $currentTypes = array_filter(
        $types,
        fn ($type) =>
        array_key_exists($type, $node)
    );

    $lines = array_map(
        fn ($type) => $indent . getPrefix($type) . trim($key . ": " . stylish($node[$type], $depth + 1)),
        $currentTypes
    );

    return implode(PHP_EOL, $lines);
}

function getPrefix(string $type): string
{
    switch ($type) {
        case 'expectedValue':
            return substr_replace(TAB, '- ', -2);
        case 'currentValue':
            return substr_replace(TAB, '+ ', -2);
    }
    return TAB;
}

function toString($value): string
{
    return trim(json_encode($value), '"');
}
