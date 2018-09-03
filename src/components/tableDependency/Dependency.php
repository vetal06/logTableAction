<?php
namespace dvlp\logTableAction\components\tableDependency;
use dvlp\logTableAction\components\tableDependency\drivers\DepDriver;
use dvlp\logTableAction\components\tableDependency\drivers\MysqlDriver;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class Dependency
 * @package backend\modules\logTableAction\components\tableDependency
 */
class Dependency
{
    /**
     * @var DepDriver
     */
    public $driver;

    /**
     * Dependency constructor.
     */
    public function __construct(DepDriver $driver = null)
    {
        if ($driver === null) {
            $this->driver = $this->getDriver();
        }
    }
    /**
     * @return DepDriver
     * @throws Exception
     */
    private function getDriver() :DepDriver
    {
        if (\Yii::$app->db->driverName === 'mysql') {
            return new MysqlDriver();
        }
        throw new Exception('DepDriver not found!');
    }

    /**
     * @param $table
     * @return array
     */
    public function getDependencyTables($table) : array
    {
        $name = \Yii::$app->db->schema->getRawTableName($table);
        return $this->driver->getDependencyTables($name);
    }

    /**
     * Получение зависимрстей по мнешним ключам
     * @param $table
     * @param $attributes
     * @return array
     */
    public function getDependencyData($table, $attributes)
    {
        $tables = $this->getDependencyTables($table);
        if (empty($tables)) {
            return [];
        }
        $res = [];
        foreach ($tables as $tableFkData) {
            $fkTable = ArrayHelper::getValue($tableFkData, 'TABLE_NAME');
            $fkColumn = ArrayHelper::getValue($tableFkData, 'COLUMN_NAME');
            $data = \Yii::$app->db->createCommand("SELECT * FROM {$fkTable} WHERE {$fkColumn} = :value", [
                ':value' => ArrayHelper::getValue($attributes, ArrayHelper::getValue($tableFkData, 'REFERENCED_COLUMN_NAME')),

            ])->queryAll();

            if (empty($data)) {
                continue;
            }

            foreach ($data as &$dataRow) {
                $recursiveData = $this->getDependencyData($fkTable, $dataRow);
                if (!empty($recursiveData)) {
                    $dataRow['dependency'] = $recursiveData;
                }
            }
            $res[$fkTable] = $data;

        }
        return $res;
    }
}
