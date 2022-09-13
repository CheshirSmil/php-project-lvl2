<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Parsers\parse;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    protected $files;
    protected $result;

    protected function setUp(): void
    {
        $this->files = [
            'json1' => 'tests/fixtures/file1.json',
            'json2' => 'tests/fixtures/file2.json',
            'yaml1' => 'tests/fixtures/file1.yaml',
            'yaml2' => 'tests/fixtures/file2.yml'
        ];
        $this->result = file_get_contents('tests/fixtures/result');
    }

    public function testParse()
    {
        $expected = (object) [
            "host" => "hexlet.io",
            "timeout" => 50,
            "proxy" => "123.234.53.22",
            "follow" => false
        ];

        $this->assertEquals($expected, parse($this->files['json1']));
        $this->assertEquals($expected, parse($this->files['yaml1']));
    }

    public function testGenDiff()
    {
        $expected = $this->result;

        $this->assertEquals($expected, genDiff($this->files['json1'], $this->files['json2']));
        $this->assertEquals($expected, genDiff($this->files['yaml1'], $this->files['yaml2']));
    }
}