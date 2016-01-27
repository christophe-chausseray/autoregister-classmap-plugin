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

        return $content['namespace'];
    }
}
