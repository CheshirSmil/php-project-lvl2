<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $path): object
{
    $content = file_get_contents($path);
    $type = pathinfo($path, PATHINFO_EXTENSION);
    switch ($type) {
        case 'json':
            return json_decode($content, false);
            break;
        case 'yaml':
        case 'yml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Exception("Unknown file type {$type}");
            break;
    }
}
