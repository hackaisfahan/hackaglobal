<?php

namespace RedBeanPHP;

use R;
use RedBeanPHP\Adapter;
use RedBeanPHP\QueryWriter\MySQL;

class UUIDWriterMySQL extends MySQL
{

    const C_DATATYPE_SPECIAL_UUID = 97;
    protected $defaultValue = '@uuid';

    public function __construct(Adapter $adapter)
    {
        parent::__construct($adapter);
        $this->addDataType(
            self::C_DATATYPE_SPECIAL_UUID, 'char(36)');
    }

    public function createTable($table)
    {
        $table = $this->esc($table);
        $sql = "
                CREATE TABLE {$table} (
                id char(36) NOT NULL,
                PRIMARY KEY ( id ))
                ENGINE = InnoDB DEFAULT
                CHARSET=utf8mb4
                COLLATE=utf8mb4_unicode_ci ";
        $this->adapter->exec($sql);
    }

    public function updateRecord($table, $updateValues, $id = NULL)
    {
        $flagNeedsReturnID = (!$id);
        if ($flagNeedsReturnID) R::exec('SET @uuid = uuid() ');
        $id = parent::updateRecord($table, $updateValues, $id);
        if ($flagNeedsReturnID) $id = R::getCell('SELECT @uuid');
        return $id;
    }

    public function getTypeForID()
    {
        return self::C_DATATYPE_SPECIAL_UUID;
    }
}