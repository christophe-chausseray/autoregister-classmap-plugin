<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Parser;

use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\Yaml\Yaml;

class NamespaceParser
{
    /**
     * @param SplFileInfo $file
     */
    public function extractNamespace(SplFileInfo $file)
    {
        $content = Yaml::parse($file->getContents());

        var_dump($content);
    }
}
