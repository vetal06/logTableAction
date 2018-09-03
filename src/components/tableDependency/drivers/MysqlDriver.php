<?php
namespace dvlp\logTableAction\components\tableDependency\drivers;


/**
 * Class MysqlDriver
 * @package dvlp\logTableAction\components\tableDependency\drivers
 */
class MysqlDriver implements DepDriver
{

    /**
     * @param $table
     * @return array
     */
    public function getDependencyTables($table): array
    {
        $database = \Yii::$app->db->createCommand("SELECT DATABASE()")->cache()->queryScalar();
        $sql = <<<SQL
SELECT 
  TABLE_NAME,COLUMN_NAME,REFERENCED_COLUMN_NAME
FROM
  INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
  REFERENCED_TABLE_SCHEMA = :database 
  AND REFERENCED_TABLE_NAME = :table;
  AND REFERENCED_COLUMN_NAME IS NOT NULL
SQL;
        return (array)\Yii::$app->db->createCommand($sql, [
            ':database' => $database,
            ':table' => $table,
        ])->queryAll();
    }
}