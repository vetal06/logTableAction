logTableAction for Yii2
========================
Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require dvlp/log-table-action-module
```
or add

```json
"dvlp/log-table-action-module" : "~1.0.0"
```

to the require section of your application's `composer.json` file.


Usage
-----
Include in module config

```
'log-table-action' => [
        'class' => \dvlp\logTableAction\LogTableActionModule::class,
        'userModel' => \backend\models\Admin::class
    ],
```

Include in you active record model

```
<?php

namespace backend\models;

use dvlp\logTableAction\traits\LogTableActionTrait;

/**
 * Модель таблицы "{{%test}}".
 *
 */
class Test extends \common\models\Test
{
    use LogTableActionTrait;

    public function init()
    {
        parent::init();
        $this->initLogTableAction();
    }
}

```