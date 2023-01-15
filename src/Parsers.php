<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $content, string $type): object
{
    switch ($type) {
        case 'json':
            $parsingData = json_decode($content, false);
            break;
        case 'yaml':
        case 'yml':
            $parsingData = Yaml::parse($content, Yaml::PARSE_OBJECT_FOR_MAP);
            break;
        default:
            throw new \Exception("Unknown file type {$type}");
    }

    return $parsingData;
}
