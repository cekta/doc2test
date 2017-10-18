<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class CodeBlock
{
    /**
     * @var \DOMNode
     */
    private $code;

    /**
     * @var \DOMNode
     */
    private $meta;

    /**
     * @param \DOMNode $code
     * @param \DOMNode $meta
     */
    public function __construct(\DOMNode $code, \DOMNode $meta = null)
    {
        $this->code = $code;
        $this->meta = $meta;
    }

    public function getLanguage(): string
    {
        $nodeClass = $this->code->attributes->getNamedItem('class');
        if ($nodeClass && preg_match('/^language-(\w+)$/', trim($nodeClass->textContent), $m)) {
            return $m[1];
        }
        return '';
    }

    public function getCode(): string
    {
        return $this->code->textContent;
    }

    public function getMeta(): array
    {
        $parsed = [];
        $meta = $this->meta ? trim($this->meta->textContent) : '';
        $pairs = preg_split('/\s/', $meta, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($pairs as $pair) {
            @list($left, $right) = explode('=', $pair);
            $parsed[$left] = $right;
        }
        return $parsed;
    }

    /**
     * @param  string      $key
     * @return null|string
     */
    public function getMetaValue(string $key)
    {
        return @$this->getMeta()[$key];
    }
}
