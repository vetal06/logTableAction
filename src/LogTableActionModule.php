<?php
namespace dvlp\logTableAction;


use dvlp\logTableAction\controllers\IndexController;
use yii\base\Module;

/**
 * Class LogTableActionModule
 * @package backend\modules\logTableAction
 */
class LogTableActionModule extends Module
{

    public $userModel;
    public $controllerMap = [
        'index' => IndexController::class
    ];
}