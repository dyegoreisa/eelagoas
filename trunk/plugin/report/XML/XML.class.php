<?php
require_once 'Field.class.php';

class XML
{
    /**
     * Arquivo XML
     * 
     * @var string
     * @access private
     */
    private $file;

    /**
     * Objeto do DOMDocument para manibular o XML
     * 
     * @var DOMDocument
     * @access private
     */
    private $doc;

    public function __construct()
    {
    }

    /**
     * Seta arquivo
     * 
     * @param string $file 
     * @access public
     * @return void
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * Retorna o arquivo XML
     * 
     * @access public
     * @return void
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Abre o arquivo XML
     * 
     * @access public
     * @return void
     */
    public function openFile()
    {
        $this->doc = new DOMDocument();
        $this->doc->load($this->file);
    }

    /**
     * Varre o xml para pegar os campos
     * 
     * @access public
     * @return array Retorna um array de fields
     */
    public function getFields()
    {
        $fields = array();

        $tables = $this->doc->getElementsByTagName('table');

        foreach ($tables as $table) {
            $itemTableName = $table->getElementsByTagName('name');
            $tableName = $itemTableName->item(0)->nodeValue;

            $columns = $table->getElementsByTagName('columns');
            foreach ($columns as $column) {
                $field = new Field();

                $field->setTableName($tableName);

                $itemColumnName = $column->getElementsByTagName('name');
                $field->setColumnName($itemColumnName->item(0)->nodeValue);

                $itemColumnType = $column->getElementsByTagName('type');
                $field->setColumnType($itemColumnType->item(0)->nodeValue);

                $fields[] = $field;
            }
        }
        return $fields;
    }

    public function parse()
    {
        return 'teste';
    }
}
