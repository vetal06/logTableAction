<?php
namespace dvlp\logTableAction\traits;

use dvlp\logTableAction\components\tableDependency\Dependency;
use dvlp\logTableAction\events\LogTableActionEvent;
use dvlp\logTableAction\models\LogTableAction;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * Логирование действий ActiveRecord
 * Class LogTableActionTrait
 * @package backend\modules\logTableAction\traits
 */
trait LogTableActionTrait
{
    private $deleteAllAtributes;
    private $dataChanged;

    protected function initLogTableAction()
    {
        $this->on(LogTableActionEvent::EVENT_AFTER_INSERT,  [$this, 'lafterInsert']);
        $this->on(LogTableActionEvent::EVENT_AFTER_UPDATE,  [$this, 'lafterUpdate']);
        $this->on(LogTableActionEvent::EVENT_BEFORE_UPDATE,  [$this, 'lbeforeUpdate']);
    }

    /**
     * @param $attributes
     * @param string $condition
     * @param array $params
     * @return mixed
     */
    public static function updateAll($attributes, $condition = '', $params = [])
    {
        if (!self::checkStaticEventEnabled()) {
            return parent::updateAll($attributes, $condition, $params);
        }
        $event = new LogTableActionEvent();
        $event->attributes = $attributes;
        $event->condition = $condition;
        $event->params = $params;
        $result = parent::updateAll($attributes, $condition, $params);
        if ($result > 0) {
            self::lafterStaticUpdateAll($event);
        }
        return $result;
    }

    /**
     * @param string $condition
     * @param array $params
     * @return mixed
     */
    public static function deleteAll($condition = null, $params = [])
    {
        $event = new LogTableActionEvent();
        $event->condition = $condition;
        $event->params = $params;
        $deleteData = self::lbeforeStaticDeleteAll($event);
        $result = parent::deleteAll($condition, $params);
        if ($result > 0) {
            self::lafterStaticDeleteAll($deleteData);
        }
        return $result;
    }

    /**
     *
     */
    public function lbeforeUpdate()
    {
        $model = $this;
        $dataArray = (array)$model->getAttributes();
        $olDataArray = (array)$model->oldAttributes;
        $diffOne = array_diff($olDataArray, $dataArray);
        $diffSecond = array_diff($dataArray, $olDataArray);
        $this->dataChanged = ArrayHelper::merge($diffOne, $diffSecond);
    }

    /**
     * Сохранение состояния таблицы
     */
    public function lafterInsert()
    {
        $model = $this;
        LogTableAction::saveLog(LogTableActionEvent::ACTION_INSERT, $model->getAttribute('id'), $model->tableName(), Json::encode($model->getAttributes()));
    }

    /**
     * Сохранение состояния таблицы
     */
    public function lafterUpdate()
    {
        $model = $this;
        LogTableAction::saveLog(LogTableActionEvent::ACTION_UPDATE, $model->getAttribute('id'), $model->tableName(), Json::encode($model->getAttributes()), Json::encode($this->dataChanged));
    }



    /**
     * @param LogTableActionEvent $event
     */
    public static function lafterStaticUpdateAll(LogTableActionEvent $event)
    {
        LogTableAction::saveLog(LogTableActionEvent::ACTION_UPDATE_ALL, 0, self::tableName(), Json::encode([
            'attributes' => $event->attributes,
            'condition' => $event->condition,
            'params' => $event->params,
        ]));
    }

    /**
     * @param LogTableActionEvent $event
     * @return array
     */
    public static function lbeforeStaticDeleteAll(LogTableActionEvent $event)
    {
        $models = self::find()->andWhere($event->condition, $event->params)->all();
        $dep = new Dependency();
        $deleteAllAtributes = [];
        foreach ($models as $model) {
            $attributes = $model->getAttributes();
            $attributes['dependency'] = $dep->getDependencyData($model->tableName(), $attributes);
            $deleteAllAtributes[] = $attributes;
        }
        return $deleteAllAtributes;
    }

    /**
     * @param array $deleteAllAtributes
     * @throws \yii\base\Exception
     */
    public static function lafterStaticDeleteAll(array $deleteAllAtributes)
    {
        if(!empty($deleteAllAtributes)) {
            foreach ($deleteAllAtributes as $attributes) {
                LogTableAction::saveLog(LogTableActionEvent::ACTION_DELETE_ALL, ArrayHelper::getValue($attributes, 'id'), self::tableName(), Json::encode($attributes));
            }
        }
    }

    /**
     * @return bool
     */
    public static function checkStaticEventEnabled()
    {
        return (bool)preg_match('/^backend/', static::class);
    }



}