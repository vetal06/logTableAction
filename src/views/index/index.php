<?php

use dvlp\logTableAction\events\LogTableActionEvent;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var yii\web\View                $this
 * @var yii\data\ActiveDataProvider $dataProvider
 */

$this->title                   = 'Лог изменений данных';
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    [
        'attribute' => 'id',

    ],
    [
        'attribute' => 'created_at',

    ],
    [
        'attribute' => 'action',

    ],
    [
        'attribute' => 'table_name',
    ],
    [
        'attribute' => 'table_id',
    ],
    [
        'attribute' => 'user_ip',
    ],
    [
        'filter' => Html::activeDropDownList($model, 'user_id', $userList, ['prompt' => '', 'class' => 'form-control']),
        'attribute' => 'user_id',
        'value' => function($model) {
            return empty($model->admin)?$model->user_id:$model->admin->username;
        }
    ],
    [
        'attribute' => 'data',
        'format' => 'raw',
        'value' => function($model) {
            $dataArray = (array)\yii\helpers\Json::decode($model->data);
            $res = '';
            foreach ($dataArray as $attribute => $value) {
                if (is_array($value)) {
                    $value = print_r($value, true);
                }
                $res .= "<b>{$attribute}</b> : {$value} </br>";
            }
            return "<div class='float-left'>{$res}</div>";
        }
    ],
    [
        'attribute' => 'data_changed',
        'format' => 'raw',
        'value' => function($model) {
            $dataArray = (array)\yii\helpers\Json::decode($model->data_changed);
            $res = '';
            foreach ((array)$dataArray as $attribute => $value) {
                if (is_array($value)) {
                    $value = print_r($value, true);
                }
                $res .= "<b>{$attribute}</b> : {$value} </br>";
            }
            return "<div class='float-left'>{$res}</div>";
        }
    ],

    [
        'header' => 'Востановление',
        'format' => 'raw',
        'value' => function ($model) {
            if (in_array($model->action, [
                LogTableActionEvent::ACTION_DELETE,
                LogTableActionEvent::ACTION_DELETE_ALL
            ])) {
                return Html::a('Востановить', ['revert', 'id' => $model->id], ['class' => 'btn btn-success']);
            }
        }
    ],
];
?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $model,
    'columns' => $columns,

]) ?>
