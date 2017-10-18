<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class TestCaseBuilder
{
    private $caseName;
    private $outputDir;
    private $tests = [];

    public function __construct(string $caseName, string $outputDir)
    {
        $this->caseName = $caseName;
        $this->outputDir = $outputDir;
    }

    public function addOutputTest(string $code, string $expected): void
    {
        $snippetFile = "{$this->caseName}/code.php";
        $expectedOutputFile = "{$this->caseName}/expected";
        $this->writeFile($snippetFile, $code);
        $this->writeFile($expectedOutputFile, $expected);
        $this->tests[] = <<<PHP
    public function testOutput()
    {
        \$this->expectOutputString(file_get_contents(__DIR__ . '/$expectedOutputFile'));
        require('$snippetFile');
    }
PHP;
    }

    public function dump()
    {
        $name = ucfirst(pathinfo($this->caseName, PATHINFO_FILENAME)) . 'Test';
        $body = implode("\n", $this->tests);
        $testCase = <<<PHP
<?php
class $name extends PHPUnit\Framework\TestCase
{
$body
}
PHP;
        $this->writeFile("{$name}.php", $testCase);
    }

    private function writeFile(string $file, string $content): void
    {
        $file = "{$this->outputDir}/{$file}";
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        file_put_contents($file, $content);
    }
}
