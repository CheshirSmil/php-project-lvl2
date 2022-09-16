<?php

namespace Differ\Formatters;

function plain(array $data, $path = []): string
{
    $result = array_map(
        fn ($node) => makePlainLine($node, $path),
        $data
    );

    return implode(PHP_EOL, array_filter($result));
}

function makePlainLine(array $node, $path): string
{
    if (array_key_exists('name', $node)) {
        $path[] = $node['name'];
    }

    $types = ['children', 'expectedValue', 'currentValue'];

    $currentTypes = array_filter(
        $types,
        fn ($type) =>
        array_key_exists($type, $node)
    );

    $type = implode(' ', $currentTypes);

    switch ($type) {
        case 'children':
            return plain($node['children'], $path);
        case 'expectedValue':
            $message = "was removed";
            return makeString($path, $message);
        case 'currentValue':
            $value = getValue($node['currentValue']);
            $message = "was added with value: {$value}";
            return makeString($path, $message);
        case 'expectedValue currentValue':
            $value1 = getValue($node['expectedValue']);
            $value2 = getValue($node['currentValue']);
            $message = "was updated. From {$value1} to {$value2}";
            return makeString($path, $message);
    }
    return '';
}

function makeString(array $path, string $message): string
{
    $property = implode('.', $path);
    return "Property '{$property}' {$message}";
}

function getValue($data): string
{
    return (is_array($data)) ? '[complex value]' : str_replace('"', "'", (json_encode($data)));
}
