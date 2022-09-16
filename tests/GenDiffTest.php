<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Parsers\parse;
use function Differ\Differ\genDiff;

class GenDiffTest extends TestCase
{
    protected $files;

    protected function setUp(): void
    {
        $this->files = [
            'json1' => 'tests/fixtures/file1.json',
            'json2' => 'tests/fixtures/file2.json',
            'yaml1' => 'tests/fixtures/file1.yaml',
            'yaml2' => 'tests/fixtures/file2.yml'
        ];
    }

    public function testParse()
    {
        $expected = (object) [
            "common" => (object) [
                "setting1" => "Value 1",
                "setting2" => 200,
                "setting3" => true,
                "setting6" => (object) [
                    "key" => "value",
                    "doge" => (object) [
                        "wow" => ""
                    ]
                ]
            ],
            "group1" => (object) [
                "baz" => "bas",
                "foo" => "bar",
                "nest" => (object) [
                    "key" => "value"
                ]
            ],
            "group2" => (object) [
                "abc" => 12345,
                "deep" => (object) [
                    "id" => 45
                ]
            ]
        ];

        $this->assertEquals($expected, parse($this->files['json1']));
        $this->assertEquals($expected, parse($this->files['yaml1']));
    }

    public function testGenDiff()
    {
        $expected1 = file_get_contents('tests/fixtures/stylish.txt');
        $expected2 = file_get_contents('tests/fixtures/plain.txt');

        $this->assertEquals($expected1, genDiff($this->files['json1'], $this->files['json2']));
        $this->assertEquals($expected2, genDiff($this->files['yaml1'], $this->files['yaml2'], 'plain'));
    }
}
