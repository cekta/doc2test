<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class CodeBlock
{
    private $content;
    private $language;
    private $meta;

    public function __construct(string $content, string $lang = '', $meta = [])
    {
        $this->content = $content;
        $this->language = $lang;
        $this->meta = $meta;
    }

    public function toStringContent(): string
    {
        return $this->content;
    }

    public function toLanguage(): string
    {
        return $this->language;
    }

    public function toMeta(): array
    {
        return $this->meta;
    }

    public function isPhp(): bool
    {
        return 'php' === $this->toLanguage();
    }
}
