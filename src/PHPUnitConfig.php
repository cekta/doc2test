<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class PHPUnitConfig
{
    /**
     * @var \DOMDocument
     */
    private $xml;

    public function __construct(string $xml)
    {
        $this->xml = new \DOMDocument();
        $this->xml->loadXML($xml);
    }

    /**
     * Set the bootstrap file
     * @param string $bootstrap
     */
    public function setBootstrap(string $bootstrap)
    {
        $this->xml->getElementsByTagName('phpunit')
            ->item(0)
            ->setAttribute(
                'bootstrap',
                $bootstrap
            );
    }

    public function toString(): string
    {
        return $this->xml->saveXML();
    }
}
