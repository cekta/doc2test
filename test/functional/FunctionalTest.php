<?php
declare(strict_types=1);

use MatthiasMullie\PathConverter\Converter;
use PHPUnit\Framework\TestCase;

class FunctionalTest extends TestCase
{
    private $output;

    protected function setUp()
    {
        parent::setUp();
        $this->output = sys_get_temp_dir() . '/php' . sha1((string) microtime(true));
        mkdir($this->output);
        system(sprintf('./doc2test doc %s', $this->output));
    }

    protected function tearDown()
    {
        system(sprintf('rm -rf %s', escapeshellarg($this->output)));
        parent::tearDown();
    }


    public function testProducedTestCodeMatchesExpected()
    {
        $this->assertDirectoryHasSameFiles(__DIR__ . '/expected', $this->output);
    }

    public function testConfigurationXmlIsValid()
    {
        exec(sprintf('phpunit -c %s', "{$this->output}/phpunit.xml"), $output, $returnVar);
        $this->assertEquals(0, $returnVar);
    }

    public static function assertDirectoryHasSameFiles(string $expectedDir, string $actualDir)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($expectedDir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        foreach ($files as $file) {
            /** @var $file SplFileInfo */
            if ($file->isDir()) {
                continue;
            }
            $filename = substr($file->getPathname(), strlen($expectedDir));
            self::assertFileEquals($file->getPathname(), $actualDir . '/' . $filename);
        }
    }

}
