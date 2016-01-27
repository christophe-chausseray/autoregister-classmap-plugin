<?php

namespace Chris\Composer\AutoregisterClassmapPlugin;

use Chris\Composer\AutoregisterClassmapPlugin\Container\ModuleContainer;
use Chris\Composer\AutoregisterClassmapPlugin\Dumper\AutoloadDumper;
use Chris\Composer\AutoregisterClassmapPlugin\Manipulator\AutoloadManipulator;
use Chris\Composer\AutoregisterClassmapPlugin\Parser\RegisterFileParser;
use Composer\Composer;
use Composer\Script\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Neirda24\Bundle\ToolsBundle\Converter\ArrayToText;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

class AutoregisterClassmapPlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * @var string COMPOSER_CONFIG_KEY_EXTRA
     */
    const COMPOSER_CONFIG_KEY_EXTRA = 'chris-autoregister-classmap';

    /**
     * @var ModuleContainer
     */
    protected $moduleContainer;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var RegisterFileParser
     */
    protected $parser;

    /**
     * @var AutoloadManipulator
     */
    protected $manipulator;

    /**
     * @var AutoloadDumper
     */
    protected $dumper;

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $extra                 = $composer->getPackage()->getExtra();
        $this->moduleContainer = new ModuleContainer($extra[static::COMPOSER_CONFIG_KEY_EXTRA]['path'], $extra[static::COMPOSER_CONFIG_KEY_EXTRA]['filename']);
        $this->finder          = new Finder();
        $this->parser          = new RegisterFileParser();
        $this->manipulator     = new AutoloadManipulator($composer->getConfig()->get('vendor-dir'));
        $this->dumper          = new AutoloadDumper($composer->getConfig()->get('vendor-dir'), new ArrayToText());
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            'post-autoload-dump' => array(
                array('moveFile', 0),
                array('run', 0),
            )
        );
    }

    /**
     * @param Event $event
     */
    public function moveFile(Event $event)
    {
        $this->manipulator->moveAutoloadPsr4();
    }

    /**
     * @param Event $event
     */
    public function run(Event $event)
    {
        $result = array();
        $this->finder->files()->ignoreDotFiles(false)->name($this->moduleContainer->getFilename())->in($this->moduleContainer->getPath());

        /** @var SplFileInfo $file */
        foreach ($this->finder as $file) {
            $moduleInfo = $this->parser->extractRegisterInformation($file);
            $result[$moduleInfo['namespace']] = $moduleInfo['source_dir'];
        }

        $this->dumper->dumpAutoloadPsr4($result);
    }
}
