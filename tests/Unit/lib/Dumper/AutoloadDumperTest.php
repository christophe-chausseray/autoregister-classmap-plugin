<?php

namespace Unit\lib\Dumper;

use Chris\Composer\AutoregisterClassmapPlugin\Dumper\AutoloadDumper;
use Neirda24\Bundle\ToolsBundle\Converter\ArrayToText;
use Phake;
use Phake_IMock;
use PHPUnit_Framework_TestCase;

class AutoloadDumperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string PATH_AUTOLOAD_PSR4
     */
    const PATH_AUTOLOAD_PSR4 = './vendor/composer/autoload_psr4.php';

    /**
     * @var string PATH_AUTOLOAD_PSR4_CHILD
     */
    const PATH_AUTOLOAD_PSR4_CHILD = './vendor/composer/autoload_psr4_child.php';

    /**
     * @var string VENDOR_DIR
     */
    const VENDOR_DIR = './vendor';

    /**
     * @var ArrayToText|Phake_IMock
     */
    protected $arrayToText;

    /**
     * @var AutoloadDumper
     */
    protected $dumper;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->arrayToText = Phake::mock(ArrayToText::class);
        $this->dumper      = new AutoloadDumper(static::VENDOR_DIR, $this->arrayToText);
    }

    /**
     * Test the method to dump the autoload_psr4
     */
    public function testDumpAutoloadPsr4()
    {
        Phake::when($this->arrayToText)->arrayToString(Phake::anyParameters())->thenReturn('"AppModule\\\Module\\\Bundle\\\TestBundle\\\" => "/path/to/module/",');

        $this->dumper->dumpAutoloadPsr4(array('namespace' => 'path'));

        Phake::verify($this->arrayToText)->arrayToString(Phake::anyParameters());

        unlink(static::PATH_AUTOLOAD_PSR4);

        rename(static::PATH_AUTOLOAD_PSR4_CHILD, static::PATH_AUTOLOAD_PSR4);
    }
}
