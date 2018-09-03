<?php
namespace dvlp\logTableAction\controllers;

use backend\base\controllers\Controller;
use dvlp\logTableAction\models\LogTableAction;
use dvlp\logTableAction\models\search\LogTableActionSearch;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/**
 * Class LogTableActionController
 * @package backend\controllers
 */
class IndexController extends Controller
{
    public $modelClass = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = new LogTableActionSearch();
        $model->load(\Yii::$app->request->get());
        $dataProvider = $model->search();
        $userList = $this->getUserList();
        return $this->render('index', compact('model', 'dataProvider', 'userList'));
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionRevert($id)
    {
        $model = LogTableAction::findOne(['id' => $id]);
        if (!$model) {
            throw new NotFoundHttpException();
        }
        $attributesData = Json::decode($model->data);

        $res = $this->revert($model->table_name, $attributesData);
        if ($res > 0) {
            \Yii::$app->session->setFlash('success', 'Данные востановлены');
        } else {
            \Yii::$app->session->setFlash('error', 'Данные не востановлены');
        }

        return $this->redirect('index');
    }

    private function revert($tableName, $attributes)
    {
        $dependency = ArrayHelper::getValue($attributes, 'dependency');
        unset($attributes['dependency']);
        $res = \Yii::$app->db->createCommand()->insert($tableName, $attributes)->execute();
        if ($res && !empty($dependency)) {
            foreach ($dependency as $table => $attributesList) {
                foreach ($attributesList as $attr) {
                    $this->revert($table, $attr);
                }
            }
        }
        return true;

    }

    /**
     * @return array
     */
    protected function getUserList()
    {
        if (!$this->module->userModel) {
            throw new Exception('UserModel Not found in config module!');
        }
        $admins = call_user_func([$this->module->userModel, 'find'])->select(['id', 'username'])->asArray()->all();
        return ArrayHelper::map($admins, 'id', function($row){return $row['username']. "({$row['id']})";});
    }
}