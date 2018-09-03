<?php
namespace dvlp\logTableAction\events;

use yii\db\ActiveRecord;

/**
 * Class LogTableActionEvent
 * @package backend\modules\logTableAction\events
 */
class LogTableActionEvent extends \yii\base\Event
{
    const EVENT_AFTER_INSERT = ActiveRecord::EVENT_AFTER_INSERT;
    const EVENT_AFTER_UPDATE = ActiveRecord::EVENT_AFTER_UPDATE;
    const EVENT_BEFORE_DELETE = ActiveRecord::EVENT_BEFORE_DELETE;
    const EVENT_AFTER_DELETE = ActiveRecord::EVENT_AFTER_DELETE;
    const EVENT_BEFORE_UPDATE = ActiveRecord::EVENT_BEFORE_UPDATE;
    const EVENT_BEFORE_STATIC_UPDATE_ALL = 'EVENT_BEFORE_STATIC_UPDATE_ALL';
    const EVENT_AFTER_STATIC_UPDATE_ALL = 'EVENT_AFTER_STATIC_UPDATE_ALL';
    const EVENT_BEFORE_STATIC_DELETE_ALL = 'EVENT_BEFORE_STATIC_DELETE_ALL';
    const EVENT_AFTER_STATIC_DELETE_ALL = 'EVENT_AFTER_STATIC_DELETE_ALL';

    const ACTION_INSERT = 'insert';
    const ACTION_UPDATE = 'update';
    const ACTION_UPDATE_ALL = 'updateAll';
    const ACTION_DELETE = 'delete';
    const ACTION_DELETE_ALL = 'deleteAll';

    /**
     * @var
     */
    public $attributes;
    /**
     * @var
     */
    public $condition;
    /**
     * @var
     */
    public $params;
}