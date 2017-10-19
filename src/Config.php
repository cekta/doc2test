<?php
declare(strict_types=1);

namespace Doc2Test\Doc2Test;

class Config
{
    private $conf;
    private $path;

    public function __construct()
    {
        $name = '.doc2test.ini';
        $this->conf = parse_ini_file($name);
        $this->path = dirname(realpath($name));
    }

    public function getSource(): string
    {
        return $this->conf['source'];
    }

    public function getDestination(): string
    {
        return $this->conf['destination'];
    }

    /**
     * Returns phpunit.xml content
     * @return null|string
     */
    public function getPhpUnitXml(): string
    {
        if (isset($this->conf['phpunit.xml'])) {
            return file_get_contents($this->path.'/'.$this->conf['phpunit.xml']);
        }
        return <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<phpunit>
    <testsuites>
        <testsuite name="Default">
            <directory>.</directory>
        </testsuite>
    </testsuites>
    <filter>
        <whitelist>
            <directory suffix=".inc.php">.</directory>
        </whitelist>
    </filter>
</phpunit>
XML;
    }
}
