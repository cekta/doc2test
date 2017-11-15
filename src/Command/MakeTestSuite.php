<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test\Command;

use Doc2Test\Doc2Test\CodeBlock;
use Doc2Test\Doc2Test\MdFilesIterator;
use Doc2Test\Doc2Test\Parser;
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
     * MakeTestSuite constructor.
     * @param Parser $parser
     * @param TestCaseBuilder $builder
     */
    public function __construct(Parser $parser, TestCaseBuilder $builder)
    {
        parent::__construct();
        $this->parser = $parser;
        $this->builder = $builder;
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
        $mdFiles = new MdFilesIterator(
            new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $input->getArgument('input'),
                    \RecursiveDirectoryIterator::SKIP_DOTS
                )
            )
        );
        /** @var \SplFileInfo $file */
        foreach ($mdFiles as $file) {
            $path = (new Converter('.', $input->getArgument('input')))->convert($file->getPath());
            $namespace = $this->pathToNamespace($path);

            $this->builder->start(mb_convert_case($file->getBasename('.md'), MB_CASE_TITLE), $namespace);
            $doc = $this->parser->parse(file_get_contents($file->getRealPath()));
            /** @var CodeBlock[] $blocks */
            $blocks = array_filter(
                $doc->toBlocks(),
                function (CodeBlock $b) {
                    return $b->isPhp();
                }
            );

            foreach ($blocks as $block) {
                $meta = $block->toMeta();
                $assert = $meta['assert'] ?? 'execute';
                switch ($assert) {
                    case 'output':
                        $expect = $meta['expect'];
                        $this->builder->addOutputTest($expect['value'], $block->toStringContent());
                }
            }
            if (!$this->builder->isEmpty()) {
                $this->builder->write($input->getArgument('output'));
            }
        }
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
