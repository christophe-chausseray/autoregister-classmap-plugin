<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Parser;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class NamespaceParser
{
    /**
     * @param SplFileInfo $file
     *
     * @return array
     */
    public function extractNamespace(SplFileInfo $file)
    {
        $content = Yaml::parse($file->getContents());

        return array(
            'namespace' => $content['namespace'],
            'path'      => rtrim($file->getPathInfo()->getRealPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($content['source_dir'], DIRECTORY_SEPARATOR),
        );
    }
}
