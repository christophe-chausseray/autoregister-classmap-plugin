<?php

namespace Functional\lib;

use Chris\Composer\AutoregisterClassmapPlugin\AutoregisterClassmapPlugin;
use Composer\Composer;
use Composer\Config;
use Composer\IO\IOInterface;
use Composer\Package\RootPackage;
use Composer\Script\Event;
use Phake;
use Phake_IMock;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class AutoregisterClassmapPluginTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string Path to modules directory MODULES_PATH
     */
    const MODULES_PATH = './tests/';

    /**
     * @var string File to register the namespace AUTOREGISTER_FILENAME
     */
    const AUTOREGISTER_FILENAME = '.module.test.yml';

    /**
     * @var string VENDOR_DIR
     */
    const VENDOR_DIR = './tmp';

    /**
     * @var string Path to the autoload that will be copied AUTOLOAD_FIXTURE_PATH
     */
    const AUTOLOAD_FIXTURE_PATH = 'Resources/composer/autoload_psr4.php';

    /**
     * @var string Path where the autoload generated will be storage AUTOLOAD_GENERATED_DIR
     */
    const AUTOLOAD_GENERATED_DIR = './tmp/composer/';

    /**
     * @var string The name of composer autoload file COMPOSER_AUTOLOAD_FILENAME
     */
    const COMPOSER_AUTOLOAD_FILENAME = 'autoload_psr4.php';

    /**
     * @var string The name of composer autoload file copied COMPOSER_AUTOLOAD_CHILD_FILENAME
     */
    const COMPOSER_AUTOLOAD_CHILD_FILENAME = 'autoload_psr4_child.php';

    /**
     * @var AutoregisterClassmapPlugin
     */
    protected $autoregister;

    /**
     * @var Event
     */
    protected $event;

    /**
     * @var Composer|Phake_IMock
     */
    protected $composer;

    /**
     * @var IOInterface
     */
    protected $interface;

    /**
     * @var RootPackage|Phake_IMock
     */
    protected $package;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->event        = Phake::mock(Event::class);
        $this->composer     = Phake::mock(Composer::class);
        $this->interface    = Phake::mock(IOInterface::class);
        $this->package      = Phake::mock(RootPackage::class);
        $this->config       = Phake::mock(Config::class);
        $this->fs           = new Filesystem();
        $this->finder       = new Finder();
        $this->autoregister = new AutoregisterClassmapPlugin();
    }

    /**
     * Set up to define the return on methods called
     */
    public function setUpMethodCalled()
    {
        Phake::when($this->composer)->getPackage()->thenReturn($this->package);
        Phake::when($this->package)->getExtra()->thenReturn($this->getExtraConfig());
        Phake::when($this->composer)->getConfig()->thenReturn($this->config);
        Phake::when($this->config)->get('vendor-dir')->thenReturn(static::VENDOR_DIR);
    }

    /**
     * Test the autoload files generation
     *
     * @dataProvider listFilesGenerated
     */
    public function testGenerateAutoloadFiles($fileExpected)
    {
        $this->clean();
        $this->setUpMethodCalled();

        $this->autoregister->activate($this->composer, $this->interface);

        $this->autoregister->moveFile($this->event);
        $this->autoregister->run($this->event);

        $this->assertTrue($this->fs->exists(static::VENDOR_DIR . DIRECTORY_SEPARATOR . $fileExpected));
    }

    /**
     * Test contents in autoload generated
     */
    public function testContentsInAutoloadGenerated()
    {
        $this->clean();
        $this->setUpMethodCalled();

        $this->autoregister->activate($this->composer, $this->interface);

        $this->autoregister->moveFile($this->event);
        $this->autoregister->run($this->event);

        $this->finder->files()->in(static::VENDOR_DIR);

        /** @var SplFileInfo $file */
        foreach ($this->finder as $file) {
            if (static::COMPOSER_AUTOLOAD_FILENAME === $file->getFilename()) {
                $this->assertContains('AppModule\\\\Module\\\\Bundle\\\\TestBundle\\\\', $file->getContents());
            }

            if (static::COMPOSER_AUTOLOAD_CHILD_FILENAME === $file->getFilename()) {
                $this->assertContains('Symfony\\\\Component\\\\Yaml\\\\', $file->getContents());
            }
        }
    }

    /**
     * @return array
     */
    public function listFilesGenerated()
    {
        return [
            ['composer/autoload_psr4.php'],
            ['composer/autoload_psr4_child.php'],
        ];
    }

    /**
     * @return array
     */
    protected function getExtraConfig()
    {
        return array(
            'chris-autoregister-classmap' =>
                array(
                    'path'     => static::MODULES_PATH,
                    'filename' => static::AUTOREGISTER_FILENAME,
                )
        );
    }

    /**
     * Method to clean the tmp directory before execute test
     */
    protected function clean()
    {
        if (is_dir(static::AUTOLOAD_GENERATED_DIR)) {
            $dirHandle = opendir(static::AUTOLOAD_GENERATED_DIR);

            while ($file = readdir($dirHandle)) {
                if ('.' !== $file && '..' !== $file) {
                    unlink(static::AUTOLOAD_GENERATED_DIR . $file);
                }
            }
        }

        copy(static::MODULES_PATH . static::AUTOLOAD_FIXTURE_PATH, static::AUTOLOAD_GENERATED_DIR . static::COMPOSER_AUTOLOAD_FILENAME);
    }
}
