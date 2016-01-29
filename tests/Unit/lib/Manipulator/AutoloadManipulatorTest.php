<?php

namespace Unit\lib\Manipulator;

use Chris\Composer\AutoregisterClassmapPlugin\Manipulator\AutoloadManipulator;

class AutoloadManipulatorTest extends \PHPUnit_Framework_TestCase
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
        $this->autoloadManipulator = new AutoloadManipulator(self::VENDOR_DIR);
    }

    /**
     *
     */
    public function testMoveAutoloadPsr4()
    {
        $isRename = $this->autoloadManipulator->moveAutoloadPsr4();

        $this->assertTrue($isRename);
    }
}
