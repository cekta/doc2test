<?php
declare(strict_types=1);
namespace Assertions;
use PHPUnit\Framework\TestCase;
class OutputTest extends TestCase {
    function test0Output() {
        $this->expectOutputString('42');
        require __DIR__ . '/' . 'OutputTest' . '/' . 'code0.php';
    }
    function test1Output() {
        $this->expectOutputString('{
    "foo": "bar"
}');
        require __DIR__ . '/' . 'OutputTest' . '/' . 'code1.php';
    }

}