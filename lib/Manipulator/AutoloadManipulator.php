<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Manipulator;

/**
 * Class AutoloadManipulator
 *
 * @author Christophe Chausseray <chausseray.christophe@gmail.com>
 *
 * Manipulator of the existing autoload files.
 */
class AutoloadManipulator
{
    /**
     * @var string
     */
    protected $vendorDir;

    /**
     * AutoloadManipulator constructor.
     *
     * @param string $vendorDir
     */
    public function __construct($vendorDir)
    {
        $this->vendorDir = $vendorDir;
    }

    /**
     * Rename "autoload_psr4.php" into "autoload_psr4_child.php"
     *
     * @return bool
     */
    public function moveAutoloadPsr4()
    {
        return $this->moveFile('autoload_psr4.php', 'autoload_psr4_child.php');
    }

    /**
     * Rename one autoload file into something else.
     *
     * @param string $oldName
     * @param string $newName
     *
     * @return bool
     */
    protected function moveFile($oldName, $newName)
    {
        return rename($this->vendorDir . '/composer/' . $oldName, $this->vendorDir . '/composer/' . $newName);
    }
}
