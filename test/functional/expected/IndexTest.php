<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;
class IndexTest extends TestCase {
    function test0Output() {
        $this->expectOutputString('42');
        require __DIR__ . '/' . 'IndexTest' . '/' . 'code0.php';
    }

}