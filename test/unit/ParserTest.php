<?php
namespace Doc2Test\Doc2Test\Test;

use Doc2Test\Doc2Test\Parser;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testEmptyMarkdownProducesNoBlocks()
    {
        $p = $this->createParser();
        $doc = $p->parse('');
        $this->assertCount(0, $doc->toBlocks());
    }

    public function testBlocksAreParsed()
    {
        $document = $this
            ->createParser()
            ->parse(file_get_contents(__DIR__ . '/samples/example.md'));
        [
            $inlineOne,
            $inlineTwo,
            $php,
            $json,
            $textMarked,
            $textUnmarked,
        ] = $document->toBlocks();

        $this->assertEquals('block one', $inlineOne->toStringContent());
        $this->assertEquals('', $inlineOne->toLanguage());
        $this->assertEquals([], $inlineOne->toMeta());

        $this->assertEquals('block two', $inlineTwo->toStringContent());
        $this->assertEquals('', $inlineTwo->toLanguage());
        $this->assertEquals(['name' => 'my_inline_block'], $inlineTwo->toMeta());

        $this->assertEquals("<?php\necho 'hello world';\n", $php->toStringContent());
        $this->assertEquals('php', $php->toLanguage());
        $this->assertEquals([], $php->toMeta());

        $this->assertEquals("{\n  \"hello\": \"world\"\n}\n", $json->toStringContent());
        $this->assertEquals('json', $json->toLanguage());
        $this->assertEquals(['foo' => 'bar', 'bar' => 'foo'], $json->toMeta());

        $this->assertEquals("This block is marked as text\n", $textMarked->toStringContent());
        $this->assertEquals('text', $textMarked->toLanguage());
        $this->assertEquals([], $textMarked->toMeta());

        $this->assertEquals("This block has no language assigned\n", $textUnmarked->toStringContent());
        $this->assertEquals('', $textUnmarked->toLanguage());
        $this->assertEquals(['name' => 'plain text block'], $textUnmarked->toMeta());
    }

    /**
     * @return Parser
     */
    private function createParser(): Parser
    {
        return new Parser(new DocParser(Environment::createCommonMarkEnvironment()));
    }
}
