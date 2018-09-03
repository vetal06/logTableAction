<?php

namespace dvlp\logTableAction\models;

use Yii;
use yii\base\Exception;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;


/**
 * Модель таблицы "{{%log_table_action}}".
 *
 * @property integer $id 
 * @property string $action 
 * @property integer $table_id 
 * @property string $table_name 
 * @property string $user_ip 
 * @property integer $user_id 
 * @property string $data 
 * @property string $data_changed
 * @property string $created_at Дата создания
 * @property string $updated_at Дата изменения
 */
class LogTableAction extends ActiveRecord
{
    /**
     * @return string Название таблицы
     */
    public static function tableName()
    {
        return '{{%log_table_action}}';
    }

    /**
    * @return array Правила валидации
    */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['table_id', 'user_id'], 'integer'],
            [['data', 'data_changed'], 'string'],
            [['action'], 'string', 'max' => 20],
            [['table_name', 'user_ip'], 'string', 'max' => 50],
        ]);
    }

    /**
    * @return array Поведения
    */
    public function behaviors()
    {
        $items = parent::behaviors();

        $items['date'] = [
            'class' => TimestampBehavior::className(),
            'createdAtAttribute' => 'created_at',
            'updatedAtAttribute' => $this->hasProperty('updated_at') ? 'updated_at' : 'created_at',
            'value' => function () {
                return date('Y-m-d H:i:s');
            },
        ];


        return $items;
    }


    /**
     * @return array Надписи атрибутов
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'action' => 'Дейтвие',
            'table_id' => 'ID таблицы',
            'table_name' => 'Название таблицы',
            'user_ip' => 'User Ip',
            'user_id' => 'Пользователь',
            'data' => 'Данные',
            'data_changed' => 'Изменненные данные',
        ]);
    }


    /**
     * @param $action
     * @param $tableId
     * @param $tableName
     * @param $data
     * @param $dataChanged
     * @throws Exception
     */
    public static function saveLog($action, $tableId, $tableName, $data, $dataChanged = null)
    {
        $model = new self();
        $userIp = null;
        $userId = null;
        try{
            $userIp = Yii::$app->request->getRemoteIP();
            $userId = Yii::$app->user->id;
        }catch (\Exception $e) {

        }
        $model->setAttributes([
            'action' => $action,
            'table_id' => $tableId,
            'table_name' => $tableName,
            'data' => $data,
            'data_changed' => $dataChanged,
            'user_ip' => $userIp,
            'user_id' => $userId,
        ]);
        if (!$model->save()) {
            throw new Exception(Json::encode($model->getErrors()));
        }

    }
}
