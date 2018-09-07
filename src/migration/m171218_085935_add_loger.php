<?php



class m171218_085935_add_loger extends \yii\db\Migration
{
    protected $_tableName = '{{%log_table_action}}';

    public function safeUp()
    {
        $this->createTable($this->_tableName, [
            'id' => $this->primaryKey(),
            'action' => $this->string('20'),
            'table_id' => $this->integer(),
            'table_name' => $this->string(50),
            'user_ip' => $this->string(50),
            'user_id' => $this->integer(),
            'data_changed' => $this->text(),
            'data' => $this->text(),
            'created_at'  => $this->dateTime()->notNull()->comment('Дата создания'),
            'updated_at'  => $this->dateTime()->notNull()->comment('Дата изменения'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable();
    }
}
