<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class PhpNamespace
{
    private $name;

    public function __construct(string $s)
    {
        $this->name = implode(
            '\\',
            array_map(
                function (string $s) {
                    return mb_convert_case($s, MB_CASE_TITLE);
                },
                array_filter(
                    explode(
                        '/',
                        preg_replace('/[^a-z0-9\/]/', '', $s)
                    )
                )
            )
        );
    }

    public function __toString(): string
    {
        return $this->name;
    }
}