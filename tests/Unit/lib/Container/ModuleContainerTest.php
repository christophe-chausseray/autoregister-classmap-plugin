<?php

namespace Unit\lib\ModuleContainer;

use Chris\Composer\AutoregisterClassmapPlugin\Container\ModuleContainer;

class ModuleContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string PATH_MODULES
     */
    const PATH_MODULES = '/path/to/modules';

    /**
     * @var string REGISTER_FILENAME
     */
    const REGISTER_FILENAME = '.module.yml';

    /**
     * @var ModuleContainer
     */
    protected $moduleContainer;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->moduleContainer = new ModuleContainer(self::PATH_MODULES, self::REGISTER_FILENAME);
    }

    /**
     * Test the type of return for getters method
     */
    public function testReturnValueOfGetters()
    {
        $path = $this->moduleContainer->getPath();

        $this->assertInternalType('string', $path);

        $filename = $this->moduleContainer->getFilename();

        $this->assertInternalType('string', $filename);
    }
}
