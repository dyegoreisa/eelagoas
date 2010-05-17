<?php
require_once 'config.inc.php';
require_once 'XML/XML.class.php';
require_once 'render/Render.class.php';
require_once 'render/Html.class.php';
require_once 'render/Pdf.class.php';
require_once 'render/Xls.class.php';
require_once 'data/Process.class.php';
require_once 'data/Result.class.php';
require_once 'data/Column.class.php';

class Report
{
    private $html;
    private $pdf;
    private $xls;
    private $userName;
    private $filters;
    private $reportName;
    private $process;

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
    public function __construct($userName, $filters, $reportName)
    {
        $this->render     = null;
        $this->userName   = $userName;
        $this->filters    = $filters;
        $this->reportName = $reportName;
        $this->process    = new Process($this->filters);
    }

    private function setData()
    {
        $this->render->setProcess($this->process);
        $this->render->setColumns($this->columns);
        $this->render->setUserName($this->userName);
        $this->render->setFilters($this->filters);
        $this->render->setReportName($this->reportName);
    }

    public function setHtml() 
    {
        $this->render = new Html();
        $this->setData();
    }

    public function setPDF($orientation, $format)
    {
        $this->render = new Pdf($orientation, $format);
        $this->setData();
        $this->render->prepareColumns();
    }

    public function setXls()
    {
        $this->render = new Xls();
        $this->setData();
        $this->render->prepareColumns();
    }

    public function getRender()
    {
        return $this->render;
    }

    public function getProcess()
    {
        return $this->process;
    }

    public function addColumn($field, $text, $align, $width)
    {
        $column = new Column();

        $column->setField($field);
        $column->setText($text);
        $column->setAlign($align);
        $column->setWidth($width);

        $this->columns[] = $column;
    }

    public function changeColumn($field, $type, $value)
    {
        $method = "set{$type}";
        foreach ($this->columns as &$column) {
            if ($column->getField() == $field) {
                call_user_method_array($method, $column, array($value));
            }
        }
    }

}
