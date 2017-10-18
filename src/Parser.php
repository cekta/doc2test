<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class Parser
{
    /**
     * @var \Parsedown
     */
    private $pd;

    /**
     * Parser constructor.
     * @param \Parsedown $pd
     */
    public function __construct(\Parsedown $pd)
    {
        $this->pd = $pd;
    }

    public static function createDefault()
    {
        return new self(new \Parsedown());
    }

    /**
     * @param string $file
     * @return CodeBlock[]
     */
    public function getCodeBlocks(string $file): array
    {
        $elements = [];
        $xpath = $this->getXPath($file);
        $codeNodes = $xpath->query("//code");
        foreach ($codeNodes as $codeNode) {
            $comment = null;
            /**
             * @var $pre \DOMNode
             */
            $pre = $codeNode->parentNode;
            if ($pre->previousSibling->nodeType === XML_COMMENT_NODE) {
                $comment = $pre->previousSibling;
            } elseif ($pre->previousSibling->nodeType === XML_TEXT_NODE
                && $pre->previousSibling->previousSibling->nodeType === XML_COMMENT_NODE) {
                $comment = $pre->previousSibling->previousSibling;
            }
            $elements[] = new CodeBlock($codeNode, $comment);
        }
        return $elements;
    }

    /**
     * @param string $file
     * @return \DOMXPath
     */
    private function getXPath(string $file): \DOMXPath
    {
        $dom = new \DOMDocument();
        $dom->loadHTML(
            $this->pd->text(
                file_get_contents($file)
            )
        );
        return new \DOMXPath($dom);
    }
}
