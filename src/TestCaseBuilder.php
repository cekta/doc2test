<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class TestCaseBuilder
{
    private $caseName;
    private $outputDir;
    private $tests = [];
    private $id = 0;

    public function __construct(string $caseName, string $outputDir)
    {
        $this->caseName = $caseName;
        $this->outputDir = $outputDir;
    }

    public function addOutputTest(string $code, string $expected): void
    {
        $id = $this->nextId();
        $snippetFile = "{$this->caseName}/code{$id}.inc.php";
        $expectedOutputFile = "{$this->caseName}/expected{$id}";
        $this->writeFile($snippetFile, $code);
        $this->writeFile($expectedOutputFile, $expected);
        $this->tests[] = <<<PHP
    public function testOutput{$id}()
    {
        \$this->expectOutputString(file_get_contents(__DIR__ . '/$expectedOutputFile'));
        require('$snippetFile');
    }
PHP;
    }

    public function addExecutionTest(string $code): void
    {
        $id = $this->nextId();
        $snippetFile = "{$this->caseName}/code{$id}.inc.php";
        $this->writeFile($snippetFile, $code);
        $this->tests[] = <<<PHP
    public function testExecution{$id}()
    {
        require('$snippetFile');
        \$this->assertTrue(true, 'Code executes');
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

    private function nextId(): int
    {
        return $this->id++;
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
