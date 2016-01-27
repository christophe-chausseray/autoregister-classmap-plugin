<?php

namespace Chris\Composer\AutoregisterClassmapPlugin\Dumper;

use Neirda24\Bundle\ToolsBundle\Converter\ArrayToText;

/**
 * Class AutoloadDumper
 *
 * @author Christophe Chausseray <chausseray.christophe@gmail.com>
 *
 * Dump the autoload file
 */
class AutoloadDumper
{
    /**
     * @var string
     */
    protected $vendorDir;

    /**
     * @var ArrayToText
     */
    protected $arrayToText;

    /**
     * AutoloadDumper constructor.
     *
     * @param string      $vendorDir
     * @param ArrayToText $arrayToText
     */
    public function __construct($vendorDir, ArrayToText $arrayToText)
    {
        $this->vendorDir   = $vendorDir;
        $this->arrayToText = $arrayToText;
    }

    /**
     * Dump the file "autoload_psr4.php".
     *
     * @param $modulesPsr4
     */
    public function dumpAutoloadPsr4($modulesPsr4)
    {
        $modules = $this->arrayToText->arrayToString($modulesPsr4, 1);

        $autoloadPsr4File = <<<EOF
<?php

// @generated by chris13/autoregister-classmap-plugin

\$composer = require_once __DIR__  . '/autoload_psr4_child.php';
\$modules = array(
$modules
);

\$result = array_merge(\$composer, \$modules);

return \$result;
EOF;
        file_put_contents($this->vendorDir . '/composer/autoload_psr4.php', $autoloadPsr4File);
    }
}
