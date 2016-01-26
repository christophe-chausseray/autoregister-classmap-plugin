<?php

namespace Chris\Composer\AutoregisterClassmapPlugin;

use Chris\Composer\AutoregisterClassmapPlugin\Container\ModuleContainer;
use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class AutoregisterClassmapPlugin implements PluginInterface
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
     * {@inheritdoc}
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $extra = $composer->getPackage()->getExtra();
        $this->moduleContainer = new ModuleContainer($extra[static::COMPOSER_CONFIG_KEY_EXTRA]['path'], $extra[static::COMPOSER_CONFIG_KEY_EXTRA]['filename']);
    }
}
