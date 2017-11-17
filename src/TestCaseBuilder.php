<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

use League\Plates\Engine;

class TestCaseBuilder
{
    private $template;
    private $files = [];
    private $tests = [];
    private $name;
    private $namespace;

    public function __construct(Engine $template)
    {
        $this->template = $template;
    }

    public function isEmpty(): bool
    {
        return empty($this->tests);
    }

    public function start(string $name, string $namespace): void
    {
        $this->name = $name . 'Test';
        $this->namespace = $namespace;
        $this->files = [];
        $this->tests = [];
    }

    public function addOutputTest(string $expected, string $code): void
    {
        $id = count($this->tests);
        $codeFile = "code{$id}.php";
        $this->files[$codeFile] = $code;
        $this->tests[] = $this->template->render(
            'output_test',
            [
                'name' => "test{$id}Output",
                'expected' => $expected,
                'code_file' => $codeFile,
                'test_name' => $this->name,
            ]
        );
    }

    public function writeTo(string $dir): void
    {
        $path = str_replace('\\', '/', $this->namespace);
        foreach ($this->files as $file => $content) {
            $this->writeFile("{$dir}/{$path}/{$this->name}/{$file}", $content);
        }
        $class = $this->template->render(
            'class',
            [
                'name' => $this->name,
                'namespace' => $this->namespace,
                'tests' => $this->tests
            ]
        );
        $this->writeFile("{$dir}/{$path}/{$this->name}.php", $class);
    }

    private function writeFile(string $file, string $content): void
    {
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        file_exists($dir) || mkdir($dir, 0777, true);
        file_put_contents($file, $content);
    }
}
