<?php
namespace Doc2Test\Doc2Test\Test;

use Doc2Test\Doc2Test\PhpNamespace;
use PHPUnit\Framework\TestCase;

class PhpNamespaceTest extends TestCase
{
    /**
     * @dataProvider data
     * @param string $expected
     * @param string $input
     */
    public function testNameGetsCapitalized(string $expected, string $input)
    {
        $this->assertEquals($expected, (string) new PhpNamespace($input));
    }

    public function data()
    {
        return [
            ['Foo', 'foo'],
            ['Foo\\Bar', 'foo/bar'],
            ['Foo\\Bar', './foo/bar'],
        ];
    }

}
