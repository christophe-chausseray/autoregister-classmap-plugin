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
     * @param SplFileInfo $file
     *
     * @return array
     */
    public function extractRegisterInformation(SplFileInfo $file)
    {
        $content = Yaml::parse($file->getContents());

        return array(
            'namespace'  => $content['namespace'],
            'source_dir' => rtrim($file->getPathInfo()->getRealPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($content['source_dir'], DIRECTORY_SEPARATOR),
        );
    }
}
