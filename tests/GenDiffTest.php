<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

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

    public function testGenDiff()
    {
        $expected1 = file_get_contents('tests/fixtures/stylish.txt');
        $expected2 = file_get_contents('tests/fixtures/plain.txt');
        $expected3 = file_get_contents('tests/fixtures/json.txt');

        $this->assertEquals($expected1, genDiff($this->files['json1'], $this->files['json2']));
        $this->assertEquals($expected2, genDiff($this->files['yaml1'], $this->files['yaml2'], 'plain'));
        $this->assertEquals($expected3, genDiff($this->files['yaml1'], $this->files['yaml2'], 'json'));
    }
}
