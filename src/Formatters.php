<?php

namespace Differ\Formatters;

use function Differ\Formatters\{plain, stylish};

function format(array $data, string $format): string
{
    switch ($format) {
        case 'stylish':
            return stylish($data);
        case 'plain':
            return plain($data);
        default:
            throw new \Exception("Unknown format");
    }
}
