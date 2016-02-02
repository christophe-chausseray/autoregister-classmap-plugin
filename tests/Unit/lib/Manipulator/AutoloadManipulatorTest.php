<?php

namespace Unit\lib\Manipulator;

use Chris\Composer\AutoregisterClassmapPlugin\Manipulator\AutoloadManipulator;
use PHPUnit_Framework_TestCase;

class AutoloadManipulatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var string VENDOR_DIR
     */
    const VENDOR_DIR = './vendor';

    /**
     * @var AutoloadManipulator
     */
    protected $autoloadManipulator;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->autoloadManipulator = new AutoloadManipulator(static::VENDOR_DIR);
    }

    /**
     * Test to move the autoload PSR4
     */
    public function testMoveAutoloadPsr4()
    {
        $isRename = $this->autoloadManipulator->moveAutoloadPsr4();

        $this->assertTrue($isRename);
    }
}
