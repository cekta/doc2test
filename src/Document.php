<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

interface Document
{
    /**
     * @return CodeBlock[]
     */
    public function toBlocks(): array;

    public function toMeta(): array;
}
