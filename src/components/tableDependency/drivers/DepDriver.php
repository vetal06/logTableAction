<?php

namespace dvlp\logTableAction\components\tableDependency\drivers;

/**
 * Interface DepDriver
 * @package dvlp\logTableAction\components\tableDependency\drivers
 */
interface DepDriver
{
    public function getDependencyTables($table) : array;
}