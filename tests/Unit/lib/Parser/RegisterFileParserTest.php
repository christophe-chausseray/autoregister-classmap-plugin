<?php

namespace Unit\lib\Manipulator;

use Chris\Composer\AutoregisterClassmapPlugin\Parser\RegisterFileParser;
use Phake;
use Phake_IMock;
use Symfony\Component\Finder\SplFileInfo;

class RegisterFileParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string PATH_TO_REGISTER_TEST
     */
    const PATH_TO_REGISTER_TEST = './tests/Resources/.module.test.yml';

    /**
     * @var RegisterFileParser
     */
    protected $parser;

    /**
     * @var SplFileInfo|Phake_IMock
     */
    protected $fileInfo;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->fileInfo = Phake::mock(SplFileInfo::class);
        $this->parser   = new RegisterFileParser();
    }

    public function testExtractRegisterInformation()
    {
        Phake::when($this->fileInfo)->getContents()->thenReturn($this->getRegisterInformation());
        Phake::when($this->fileInfo)->getPathInfo()->thenReturn($this->fileInfo);
        Phake::when($this->fileInfo)->getRealPath()->thenReturn('/path/to/module');

        $this->parser->extractRegisterInformation($this->fileInfo);

        Phake::verify($this->fileInfo)->getContents();
        Phake::verify($this->fileInfo)->getPathInfo();
        Phake::verify($this->fileInfo)->getRealPath();
    }

    public function getRegisterInformation()
    {
        return file_get_contents(self::PATH_TO_REGISTER_TEST);
    }
}
