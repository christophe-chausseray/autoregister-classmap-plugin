<?php

namespace Chris\Composer\AutoregisterClassmapPlugin;

use Chris\Composer\AutoregisterClassmapPlugin\Container\ModuleContainer;
use Chris\Composer\AutoregisterClassmapPlugin\Parser\NamespaceParser;
use Composer\Composer;
use Composer\EventDispatcher\Event;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\CommandEvent;
use Composer\Plugin\PluginInterface;
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
     * @var NamespaceParser
     */
    protected $parser;

    /**
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $extra                 = $composer->getPackage()->getExtra();
        $this->moduleContainer = new ModuleContainer($extra[static::COMPOSER_CONFIG_KEY_EXTRA]['path'], $extra[static::COMPOSER_CONFIG_KEY_EXTRA]['filename']);
        $this->finder          = new Finder();
        $this->parser          = new NamespaceParser();
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'pre-autoload-dump' => [
                ['run', 0]
            ]
        ];
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
            $row = $this->parser->extractNamespace($file);
            $namespace = key($row);
            $result[$namespace] = $row[$namespace];
        }
    }
}
