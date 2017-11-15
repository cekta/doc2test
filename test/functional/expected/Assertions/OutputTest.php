<?php
declare(strict_types=1);
namespace Assertions;
use PHPUnit\Framework\TestCase;
class OutputTest extends TestCase {
    function testOutput() {
        $this->expectOutputString('42');
        require __DIR__ . '/' . 'OutputTest' . '/' . 'code.php';
    }
}