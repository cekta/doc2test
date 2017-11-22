<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

use League\CommonMark\Block\Element\AbstractBlock;
use League\CommonMark\Block\Element\FencedCode;
use League\CommonMark\Block\Element\HtmlBlock;
use League\CommonMark\DocParser;
use League\CommonMark\Inline\Element\AbstractStringContainer;
use League\CommonMark\Inline\Element\Code;
use League\CommonMark\Inline\Element\HtmlInline;
use League\CommonMark\Inline\Element\Newline;
use League\CommonMark\Node\Node;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class Parser implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    private $parser;

    public function __construct(DocParser $parser)
    {
        $this->parser = $parser;
    }

    public function parse(string $string): Document
    {
        $doc = new DocumentBuilder();
        $walker = $this->parser->parse($string)->walker();
        while ($event = $walker->next()) {
            if (!$event->isEntering()) {
                continue;
            }
            $node = $event->getNode();
            if ($this->isCode($node)) {
                $doc->addBlock($this->makeCodeBlock($node));
            }
        }
        return $doc;
    }

    private function isCode(Node $node): bool
    {
        return $node instanceof Code || $node instanceof FencedCode;
    }

    private function isHtml(Node $node): bool
    {
        return $node instanceof HtmlInline || $node instanceof HtmlBlock;
    }

    private function getContent(Node $node): string
    {
        if ($node instanceof AbstractStringContainer) {
            return $node->getContent();
        } elseif ($node instanceof AbstractBlock) {
            return $node->getStringContent();
        }
        throw new \UnexpectedValueException();
    }

    private function makeCodeBlock(Node $node): CodeBlock
    {
        return new CodeBlock(
            $this->getContent($node),
            $node instanceof FencedCode ? $node->getInfo() : '',
            $this->getMeta($node)
        );
    }

    private function getMeta(Node $node): array
    {
        $node = $node->previous();
        if ($node && $this->isBlank($node)) {
            $node = $node->previous();
        }
        if ($node && $this->isHtml($node) && preg_match('/^<!--(.*)-->$/s', trim($this->getContent($node)), $match)) {
            $content = $match[1];
            $meta = json_decode($content, true);
            if (!$meta) {
                $this->logger && $this->logger->info("Can not parse {$content}");
            }
            return $meta ?: [];
        }
        return [];
    }

    private function isBlank(Node $node): bool
    {
        return $node instanceof Newline
            || !trim($this->getContent($node));
    }
}
