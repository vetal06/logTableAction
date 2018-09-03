<?php
namespace dvlp\logTableAction\models\search;

use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;


/**
 * Интерфейс для поиска записей
 * Interface ApiSearchInterface
 * @package dvlp\logTableAction\models\search
 */
interface ApiSearchInterface
{

    /**
     * @return ActiveQuery
     */
    public function getQuery();

    /**
     * @return ActiveDataProvider
     */
    public function search();
}