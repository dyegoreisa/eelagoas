<?php
require_once 'config.inc.php';
require_once 'XML/XML.class.php';
require_once 'render/Render.class.php';
require_once 'render/Html.class.php';
require_once 'render/Pdf.class.php';
require_once 'data/Result.class.php';
require_once 'data/Column.class.php';

class Report
{
    private $xml;
    private $html;
    private $pdf;
    private $xls;
    private $result;

    /**
     * Lista de objetos de column contendo definições da coluna 
     * 
     * @var Column
     * @access private
     */
    private $columns;

    /**
     * Método construtor que inicia variáveis
     * 
     * @access public
     * @return void
     */
    public function __construct()
    {
        $this->render = null;
        $this->xml    = new Xml();
        $this->result = new Result();
    }

    public function setHtml() 
    {
        $this->render = new Html();
        $this->render->setResult($this->result);
        $this->render->setColumns($this->columns);
    }

    public function setPDF()
    {
        $this->render = new Pdf();
        $this->render->setResult($this->result);
        $this->render->setColumns($this->columns);
    }

    public function setXls()
    {
        $this->render = new Xls();
        $this->render->setResult($this->result);
        $this->render->setColumns($this->columns);
    }

    public function getRender()
    {
        return $this->render;
    }

    public function getResult()
    {
        return $this->result;
    }

    public function addColumn($field, $text, $align, $fill, $width)
    {
        $column = new Column();

        $column->setField($field);
        $column->setText($text);
        $column->setAlign($align);
        $column->setFill($fill);
        $column->setWidth($width);

        $this->columns[] = $column;
    }

}
