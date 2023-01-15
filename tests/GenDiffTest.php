<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

function getFixturePath(string $fileName): string
{
    return implode(DIRECTORY_SEPARATOR, [__DIR__, "fixtures", $fileName]);
}

class GenDiffTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGenDiff($pathToFile1, $pathToFile2, $format, $expected)
    {
        $pathToExpectedFixture = getFixturePath($expected);
        $this->assertStringEqualsFile(
            $pathToExpectedFixture,
            genDiff(getFixturePath($pathToFile1), getFixturePath($pathToFile2), $format)
        );
    }

    public function additionProvider()
    {
        return [
            ['file1.json', 'file2.json', 'stylish', 'stylish.txt'],
            ['file1.json', 'file2.json', 'plain', 'plain.txt'],
            ['file1.json', 'file2.json', 'json', 'json.txt'],
            ['file1.yaml', 'file2.yml', 'stylish', 'stylish.txt'],
            ['file1.yaml', 'file2.yml', 'plain', 'plain.txt'],
            ['file1.yaml', 'file2.yml', 'json', 'json.txt']
        ];
    }
}
