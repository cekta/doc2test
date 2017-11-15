<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class DocumentBuilder implements Document
{
    private $blocks = [];
    private $meta = [];

    public function addBlock(CodeBlock $block): void
    {
        $this->blocks[] = $block;
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * @return CodeBlock[]
     */
    public function toBlocks(): array
    {
        return $this->blocks;
    }

    public function toMeta(): array
    {
        return $this->meta;
    }
}
