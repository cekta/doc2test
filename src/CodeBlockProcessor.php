<?php
namespace Doc2Test\Doc2Test;

use Doc2Test\Doc2Test\CodeBlock;
use Doc2Test\Doc2Test\Document;
use Doc2Test\Doc2Test\TestCaseBuilder;

interface CodeBlockProcessor
{
    public function supports(CodeBlock $block): bool;

    public function process(CodeBlock $block, Document $doc, TestCaseBuilder $builder): void;
}