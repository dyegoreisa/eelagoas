<?php
class Field
{
    /**
     * Nome da tabela 
     * 
     * @var string
     * @access private
     */
    private $tableName;

    /**
     * Nome da coluna 
     * 
     * @var mixed
     * @access private
     */
    private $columnName;
    private $columnType;
    
    public function __construct()
    {
    }

    public function setTableName($name)
    {
        $this->tableName = $name;
    }

    public function setColumnName($name)
    {
        $this->columnName = $name;
    }

    public function setColumnType($type)
    {
        $this->columnType = $type;
    }
}
