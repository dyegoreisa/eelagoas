<?php
abstract class Render
{
    protected $fields;
    protected $result;
    protected $lists;

    /**
     * Nome do usuário
     * 
     * @access protected
     * @var string
     */
    protected $userName;
    
    /**
     * Lista com os dados
     * 
     * @access protected
     * @var array
     */
    protected $data;
    
    /**
     * Lista de titulos das colunas
     * 
     * @access protected
     * @var array
     */
    protected $columns;
    
    /**
     * Lista de filtros aplicados
     * 
     * @access protected
     * @var array
     */
    protected $filters;
    
    /**
     * Data e hora de geração do relatório em formato Brasileiro
     * 
     * @access protected
     * @var string
     */
    protected $todayBR;
    
    /**
     * Data e hora de geração do relatório em formato ISO
     * 
     * @access protected
     * @var string
     */
    protected $today;
    
    /**
     * Nome do relatório
     * 
     * @access protected
     * @var string
     */
    protected $reportName;

    public function __construct() 
    {
        $this->todayBR = date("d/m/Y H:i");
        $this->today   = date('Y-m-d-H-i');
    }

    public function setFields(array $fields) 
    {
        $this->fields = $fields;
    }

    public function getFields() 
    {
        return $this->fields;
    }

    public function setResult(Result $result) 
    {
        $this->result = $result;
        $this->data   = $this->result->execute();
    }

    public function setLists($lists)
    {
        $this->lists = $lists;
    }
    
    abstract public function render(); 
}
