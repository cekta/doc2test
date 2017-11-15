<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test\Processor;

use Doc2Test\Doc2Test\CodeBlock;
use Doc2Test\Doc2Test\CodeBlockProcessor;
use Doc2Test\Doc2Test\Document;
use Doc2Test\Doc2Test\TestCaseBuilder;

class OutputBlockProcessor implements CodeBlockProcessor
{
    public function supports(CodeBlock $block): bool
    {
        $m = $block->toMeta();
        return isset($m['assert']) && $m['assert'] === 'output'
            && isset($m['expect']['block']);
    }

    public function process(CodeBlock $block, Document $doc, TestCaseBuilder $builder): void
    {
        $m = $block->toMeta();
        $expected = trim($doc->toBlock($m['expect']['block'])->toStringContent());
        $actual =  $block->toStringContent();
        $builder->addOutputTest($expected, $actual);
    }
}