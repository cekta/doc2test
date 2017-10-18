<?php
require_once __DIR__ . '/vendor/autoload.php';
$p = new \Doc2Test\Doc2Test\Parser(new Parsedown());
$mdFile = __DIR__ . '/test/example.md';
$name = pathinfo($mdFile, PATHINFO_BASENAME);
$builder = new \Doc2Test\Doc2Test\TestCaseBuilder($name, 'output');
$elements = $p->getCodeBlocks($mdFile);

/**
 * @var $named \Doc2Test\Doc2Test\CodeBlock[]
 */
$named = [];
foreach ($elements as $index => $element) {
    $name = $element->getMetaValue('name');
    if ($name) {
        $named[$name] = $element;
    }
}

foreach ($elements as $index => $element) {
    if ($element->getLanguage() !== 'php') {
        continue;
    }
    if ($element->getMetaValue('assert') === 'output') {
        $from = $element->getMetaValue('from');
        if ($from) {
            $builder->addOutputTest($element->getCode(), $named[$from]->getCode());
        }
    }
}

$builder->dump();
