This composer plugin allow to register external source code in the autoload of composer from your project

Installation
============

Step 1: Enable the Plugin
-------------------------

Before downloading it you need to update your `composer.json` file with the following:

```json
"extra":
    "chris-autoregister-classmap": {
        "path" : "path/to/modules",
        "filename" : ".module.yml"
    }
}
```
Path : Directory where you store all your external source code (module)
Filename : The file where you have the namespace and the source directory of your module (it can be a hidden file or not)

Step 2: Download the Plugin
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this plugin:

```bash
$ composer require chris13/autoregister-classmap-plugin "~1"
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.


Usage
=====

In each external source code (module) directory that you want registered, you need to a YML file containing the namespace and the source
directory of your bundle.

```yml
namespace: "App\\Module\\Bundle\\ExampleBundle\\"
source_dir: "src/"
```

[Example of usage](./Example.md)
