<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class DocumentBuilder implements Document
{
    private $blocks = [];
    private $blocksByName = [];
    private $meta = [];

    public function addBlock(CodeBlock $block): void
    {
        $this->blocks[] = $block;
        $m = $block->toMeta();
        if (isset($m['name'])) {
            $this->blocksByName[$m['name']] = $block;
        }
    }

    public function setMeta(array $meta)
    {
        $this->meta = $meta;
    }

    /**
     * Convert to array of code blocks in the document
     *
     * @return CodeBlock[]
     */
    public function toBlocks(): array
    {
        return $this->blocks;
    }

    /**
     * Convert to meta information about the document
     * @return array
     */
    public function toMeta(): array
    {
        return $this->meta;
    }

    /**
     * Convert to block by name
     * @param string $name
     * @return CodeBlock
     */
    public function toBlock(string $name): CodeBlock
    {
        if (isset($this->blocksByName[$name])) {
            return $this->blocksByName[$name];
        }
        throw new \OutOfBoundsException("Block {$name} not found");
    }
}
