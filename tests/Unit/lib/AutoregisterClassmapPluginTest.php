<?php

namespace Unit\lib;

use ArrayIterator;
use Chris\Composer\AutoregisterClassmapPlugin\AutoregisterClassmapPlugin;
use Chris\Composer\AutoregisterClassmapPlugin\Container\ModuleContainer;
use Chris\Composer\AutoregisterClassmapPlugin\Dumper\AutoloadDumper;
use Chris\Composer\AutoregisterClassmapPlugin\Manipulator\AutoloadManipulator;
use Chris\Composer\AutoregisterClassmapPlugin\Parser\RegisterFileParser;
use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use Composer\Script\Event;
use Phake;
use Phake_IMock;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Utils\Accessible;

class AutoregisterClassmapPluginTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string MODULES_PATH
     */
    const MODULES_PATH = '../project/php/modules';

    /**
     * @var string FILENAME
     */
    const FILENAME = '.module.yml';

    /**
     * @var Composer|Phake_IMock
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $io;

    /**
     * @var AutoregisterClassmapPlugin
     */
    protected $autoregister;

    /**
     * @var RootPackage|Phake_IMock
     */
    protected $package;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Finder|Phake_IMock
     */
    protected $finder;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var SplFileInfo
     */
    protected $files;

    /**
     * @var ModuleContainer|Phake_IMock
     */
    protected $moduleContainer;

    /**
     * @var ArrayIterator
     */
    protected $iterator;

    /**
     * @var RegisterFileParser|Phake_IMock
     */
    protected $parser;

    /**
     * @var AutoloadDumper|Phake_IMock
     */
    protected $dumper;

    /**
     * @var AutoloadManipulator|Phake_IMock
     */
    protected $manipulator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->composer     = Phake::mock(Composer::class);
        $this->io           = Phake::mock(IOInterface::class);
        $this->package      = Phake::mock(RootPackage::class);
        $this->config       = Phake::mock(Config::class);
        $this->autoregister = new AutoregisterClassmapPlugin();
        $this->finder       = Phake::mock(Finder::class);
        $this->event        = Phake::mock(Event::class);
        $this->files        = Phake::mock(SplFileInfo::class);
        $this->moduleContainer = Phake::mock(ModuleContainer::class);
        $this->iterator     = new ArrayIterator();
        $this->parser       = Phake::mock(RegisterFileParser::class);
        $this->dumper       = Phake::mock(AutoloadDumper::class);
        $this->manipulator  = Phake::mock(AutoloadManipulator::class);
    }

    /**
     * Test the method to activate the plugin
     */
    public function testActivatePlugin()
    {
        Phake::when($this->composer)->getPackage()->thenReturn($this->package);
        Phake::when($this->package)->getExtra()->thenReturn($this->getExtraConfig());
        Phake::when($this->composer)->getConfig()->thenReturn($this->config);

        $this->autoregister->activate($this->composer, $this->io);

        $moduleContainer = Accessible::getPropertyValue($this->autoregister, 'moduleContainer');
        $finder          = Accessible::getPropertyValue($this->autoregister, 'finder');
        $parser          = Accessible::getPropertyValue($this->autoregister, 'parser');
        $manipulator     = Accessible::getPropertyValue($this->autoregister, 'manipulator');
        $dumper          = Accessible::getPropertyValue($this->autoregister, 'dumper');

        $this->assertInstanceOf(ModuleContainer::class, $moduleContainer);
        $this->assertInstanceOf(Finder::class, $finder);
        $this->assertInstanceOf(RegisterFileParser::class, $parser);
        $this->assertInstanceOf(AutoloadManipulator::class, $manipulator);
        $this->assertInstanceOf(AutoloadDumper::class, $dumper);
    }

    /**
     * Test the method to retrieve subscribed events
     */
    public function testGetSubscribedEvents()
    {
        $events = $this->autoregister->getSubscribedEvents();

        $this->assertArrayHasKey('post-autoload-dump', $events);
    }

    /**
     * Test the method move files
     */
    public function testMoveFile()
    {
        Accessible::setPropertyValue($this->autoregister, 'manipulator', $this->manipulator);

        $this->autoregister->moveFile($this->event);

        Phake::verify($this->manipulator)->moveAutoloadPsr4();
    }

    /**
     * Test the method to register modules
     */
    public function testRun()
    {
        Accessible::setPropertyValue($this->autoregister, 'finder', $this->finder);
        Accessible::setPropertyValue($this->autoregister, 'moduleContainer', $this->moduleContainer);
        Accessible::setPropertyValue($this->autoregister, 'parser', $this->parser);
        Accessible::setPropertyValue($this->autoregister, 'dumper', $this->dumper);

        Phake::when($this->finder)->files()->thenReturn($this->finder);
        Phake::when($this->finder)->ignoreDotFiles(false)->thenReturn($this->finder);
        Phake::when($this->finder)->name(Phake::anyParameters())->thenReturn($this->finder);
        $this->iterator->append($this->files);
        Phake::when($this->finder)->getIterator()->thenReturn($this->iterator);
        Phake::when($this->moduleContainer)->getFilename()->thenReturn(self::FILENAME);
        Phake::when($this->moduleContainer)->getPath()->thenReturn(self::MODULES_PATH);

        $this->autoregister->run($this->event);

        Phake::verify($this->finder)->files();
        Phake::verify($this->finder)->ignoreDotFiles(false);
        Phake::verify($this->finder)->name(Phake::anyParameters());
        Phake::verify($this->finder)->in(Phake::anyParameters());
        Phake::verify($this->moduleContainer)->getFilename();
        Phake::verify($this->moduleContainer)->getPath();
        Phake::verify($this->parser)->extractRegisterInformation(Phake::anyParameters());
        Phake::verify($this->dumper)->dumpAutoloadPsr4(Phake::anyParameters());
    }

    /**
     * @return array
     */
    protected function getExtraConfig()
    {
        return array(
            'chris-autoregister-classmap' =>
                array(
                    'path' => self::MODULES_PATH,
                    'filename' => self::FILENAME,
                )
        );
    }
}
