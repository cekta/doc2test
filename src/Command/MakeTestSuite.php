<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test\Command;

use Doc2Test\Doc2Test\CodeBlock;
use Doc2Test\Doc2Test\MdFilesIterator;
use Doc2Test\Doc2Test\Parser;
use Doc2Test\Doc2Test\CodeBlockProcessor;
use Doc2Test\Doc2Test\PHPUnitConfig;
use Doc2Test\Doc2Test\Processor\OutputValueProcessor;
use Doc2Test\Doc2Test\TestCaseBuilder;
use MatthiasMullie\PathConverter\Converter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeTestSuite extends Command
{
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var TestCaseBuilder
     */
    private $builder;

    /**
     * @var CodeBlockProcessor[]
     */
    private $processors = [];

    private $phpunitConfig;

    public function __construct(Parser $parser, TestCaseBuilder $builder, string $phpunitConfig)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->builder = $builder;
        $this->phpunitConfig = $phpunitConfig;
    }

    public function addProcessor(CodeBlockProcessor $processor): void
    {
        $this->processors[] = $processor;
    }

    protected function configure()
    {
        $this
            ->setName('make:test')
            ->setDescription('Produces a test suite for a given directory')
            ->addArgument('input', InputArgument::REQUIRED, 'Source document folder')
            ->addArgument('output', InputArgument::REQUIRED, 'Output folder');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = new MdFilesIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $input->getArgument('input'),
                    \RecursiveDirectoryIterator::SKIP_DOTS
                )
            )
        );
        /** @var \SplFileInfo $file */
        foreach ($files as $file) {
            $namespace = $this->pathToNamespace(
                (new Converter('.', $input->getArgument('input')))->convert($file->getPath())
            );
            $this->builder->start(mb_convert_case($file->getBasename('.md'), MB_CASE_TITLE), $namespace);
            $doc = $this->parser->parse(file_get_contents($file->getRealPath()));
            /** @var CodeBlock[] $blocks */
            $blocks = array_filter(
                $doc->toBlocks(),
                function (CodeBlock $b) {
                    return $b->isPhp();
                }
            );
            foreach ($blocks as $code) {
                foreach ($this->processors as $processor) {
                    if ($processor->supports($code)) {
                        $processor->process($code, $doc, $this->builder);
                        continue 2;
                    }
                }
            }
            if (!$this->builder->isEmpty()) {
                $this->builder->writeTo($input->getArgument('output'));
            }
        }
        file_put_contents($input->getArgument('output') . '/phpunit.xml', $this->phpunitConfig);
    }

    private function pathToNamespace(string $path): string
    {
        return implode(
            '\\',
            array_map(
                function (string $s) {
                    return mb_convert_case($s, MB_CASE_TITLE);
                },
                explode('\\', $path)
            )
        );
    }
}
