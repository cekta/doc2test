<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test\Processor;

use Doc2Test\Doc2Test\CodeBlock;
use Doc2Test\Doc2Test\CodeBlockProcessor;
use Doc2Test\Doc2Test\Document;
use Doc2Test\Doc2Test\TestCaseBuilder;

class OutputValueProcessor implements CodeBlockProcessor
{
    public function supports(CodeBlock $block): bool
    {
        $m = $block->toMeta();
        return isset($m['assert']) && $m['assert'] === 'output'
            && isset($m['expect']['value']);
    }

    public function process(CodeBlock $block, Document $doc, TestCaseBuilder $builder): void
    {
        $builder->addOutputTest($block->toMeta()['expect']['value'], $block->toStringContent());
    }
}