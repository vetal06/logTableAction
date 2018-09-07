<?php
namespace dvlp\logTableAction\models\search;

use dvlp\logTableAction\models\LogTableAction;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class LogTableActionSearch extends Model implements ApiSearchInterface
{

    public $id;
    public $action;
    public $table_name;
    public $table_id;
    public $user_ip;
    public $user_id;
    public $data;
    public $data_changed;
    public $created_at;

    public function rules()
    {
        return [
            [['id', 'table_id', 'user_id'], 'integer'],
            [['action', 'table_name', 'user_ip', 'data', 'data_changed', 'created_at'], 'string']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getQuery()
    {
        $query =  LogTableAction::find();
        if ($this->validate()) {
            $query->andFilterWhere([
                'id' => $this->id,
                'action' => $this->action,
                'table_id' => $this->table_id,
                'user_ip' => $this->user_ip,
                'user_id' => $this->user_id,
            ]);
            $query->andFilterWhere(['like', 'data', $this->data]);
            $query->andFilterWhere(['like', 'data_changed', $this->data_changed]);
            $query->andFilterWhere(['like', 'table_name', $this->table_name]);
            $query->andFilterWhere(['like', 'created_at', $this->created_at]);
        }

        return $query;
    }

    /**
     * @return ActiveDataProvider
     */
    public function search()
    {
        $query = $this->getQuery();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
//                'attributes' => [
//                    'created_at'
//                ],
                'defaultOrder' => ['id' => SORT_DESC]
            ]
        ]);

        return $dataProvider;
    }

}