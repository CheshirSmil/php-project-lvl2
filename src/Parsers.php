<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $path)
{
    $content = file_get_contents($path);
    if ($content === false) {
        throw new \Exception("Error file reading {$path}");
    }

    $type = pathinfo($path, PATHINFO_EXTENSION);

    switch ($type) {
        case 'json':
            return json_decode($content, false);
        case 'yaml':
        case 'yml':
            return Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
        default:
            throw new \Exception("Unknown file type {$type}");
    }
}
