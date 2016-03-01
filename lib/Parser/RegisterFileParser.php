<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Parser;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

/**
 * Class NamespaceParser
 *
 * @author Christophe Chausseray <chausseray.christophe@gmail.com>
 *
 * Manipulator of the existing autoload files.
 */
class RegisterFileParser
{
    /**
     * @var array
     */
    protected $moduleList = array();

    /**
     * @param SplFileInfo $file
     *
     * @return array
     */
    public function extractRegisterInformation(SplFileInfo $file)
    {
        $content = Yaml::parse($file->getContents());

        $result = array(
            'namespace'  => $content['namespace'],
            'source_dir' => rtrim($file->getPathInfo()->getRealPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($content['source_dir'], DIRECTORY_SEPARATOR),
        );

        $name = [];
        preg_match('#(?P<name>[a-z]*)$#i', $this->getPath(), $name);
        $key = $name['name'];

        $this->moduleList[$key] = $result;

        return $result;
    }

    /**
     * Get ModuleList
     *
     * @return array
     */
    public function getModuleList()
    {
        return $this->moduleList;
    }
}
