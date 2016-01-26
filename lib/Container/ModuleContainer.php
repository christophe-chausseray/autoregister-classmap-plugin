<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Container;

/**
 * Class ModuleContainer
 *
 * @author Christophe Chausseray <chausseray.christophe@gmail.com>
 *
 * Contain information about modules (modules location, filename to register the module)
 */
class ModuleContainer
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $filename;

    /**
     * ModuleContainer constructor.
     *
     * @param string $path
     * @param string $filename
     */
    public function __construct($path, $filename)
    {
        $this->path     = $path;
        $this->filename = $filename;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }
}
