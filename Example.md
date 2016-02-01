Projects Structure
==================

Projects Structure :

    - root directory
        - main-project
        - php
            - modules
                - test-module

Module Project :

    - test-module
        - src
            - Controller
                AppController.php
        .module.yml

Main Project :

    - main-project
        - src
            - Controller
                AppController.php
        composer.json


Configuration
=============

To register your module in the main project, you need to add the plugin in requires and add the extra configuration :

```json
{
    "name": "name/test-module",
    "license": "proprietary",
    "type": "project",
    "autoload": {
        "psr-4": {
            "TestModule\\": "/src"
        }
    },
    "require": {
        "php": ">=5.3.9",
        "chris13/autoregister-classmap-plugin": "~1",
        // ...
    },
    "require-dev": {
        "composer/composer": "~1.0@dev"
    },
    "extra": {
        "chris-autoregister-classmap": {
            "path" : "./php/modules",
            "filename" : ".module.yml"
        }
    }
}
```

Then in your module project, you need to specify the namespace for your module and the source directory :

```yml
namespace: "Test\\Module\\TestModule\\"
source_dir: "src/"
```
