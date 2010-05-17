<?php
abstract class Render
{
    /**
     * Objeto que contém dados vindo do banco
     * 
     * @var Process
     * @access protected
     */
    protected $process;

    /**
     * Lista de parâmetros
     *
     * Esta lista de parametros é um array de arrays associativos
     * 
     * @var mixed
     * @access protected
     */
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
     * Lista de objetos tipo Columns contendo informações sobre as colunas
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

    /**
     * Nível do cabeçalho
     * 
     * @var mixed
     * @access protected
     */
    protected $level;

    public function __construct() 
    {
        $this->todayBR = date("d/m/Y H:i");
        $this->today   = date('Y-m-d-H-i');
    }

    public function getData()
    {
        return $this->data;
    }

    public function setProcess(Process $process) 
    {
        $this->process = $process;
        $this->data   = $this->process->execute();
    }

    public function setColumns(array $columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function getColumnByField($field) 
    {
        foreach ($this->columns as $column) {
            if ($field == $column->getField()) {
                return $column;
            }
        }
    }

    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }
    
    public function setUserName($strUserName)
    {
        $this->userName = $strUserName;
    }

    public function getUserName()
    {
        return $this->userName;
    }

    public function setReportName($reportName)
    {
        $this->reportName = $reportName;
    }

    public function getReportName()
    {
        return $this->reportName;
    }

    public function getToday()
    {
        return $this->today;
    }

    public function getTodayBR()
    {
        return $this->todayBR;
    }
    
    /**
     * Define a lista de listas
     * 
     * @param array $lists 
     * @access public
     * @return void
     */
    public function setLists($lists)
    {
        $this->lists = $lists;
    }

    public function getArrayColumnText()
    {
        $aux = array();
        foreach ($this->columns as $column) {
            $aux[$column->getField()] = $column->getText();
        }
        return $aux;
    }

    public function getArrayColumnAlign()
    {
        $aux = array();
        foreach ($this->columns as $column) {
            $aux[$column->getField()] = $column->getAlign();
        }
        return $aux;
    }

    public function getArrayColumnFill()
    {
        $aux = array();
        foreach ($this->columns as $column) {
            $aux[$column->getField()] = $column->getFill();
        }
        return $aux;
    }

    public function getArrayColumnWidth()
    {
        $aux = array();
        foreach ($this->columns as $column) {
            $aux[$column->getField()] = $column->getWidth();
        }
        return $aux;
    }
    
    /**
     * Monta uma célula do filtro
     * 
     * A variável $param deve ser enviada conforme o exemplo:
     *  array('index'   => 'indice_exemplo', // Indice do array $arrFiltros do objeto
     *        'field'   => 'Label exemplo: ',// Label que aparecerá no relatório
     *        'replace' => true,             // true para subistituição de valor e false quando o é valor literal
     *        'void'    => true)             // true quando quer imprimir somente o Label e omitir este parametro  
     *                                       // quando o for imprimir o valor também 
     * 
     * @param array $params - Array de paramentros
     * @access protected
     * @return array - Dados para o filtro
     */
    protected function makeFilter($params)
    {
        if (!isset($this->filters[$params['index']])) {
            $text = (isset($params['void']) && $params['void']) ? '': 'Todos';
        }elseif (is_array($this->filters[$params['index']])) {
            if (isset($params['replace']) && $params['replace']) {
                $aux = array();
                foreach ($this->filters[$params['index']] as $val) {
                    $aux[] = $this->getDataByParam($val, $params['index']);
                }
                $text = implode(', ', $aux);
            } else {
                $text = implode(', ', $this->filters[$params['index']]);
            }
        } else {
            if (isset($params['replace']) && $params['replace']) {
                $text = $this->getDataByParam($this->filters[$params['index']], $params['index']);
            } else {
                $text = $this->filters[$params['index']];
            }
        }

        return array(
            'field' => $params['field'],
            'value' => $text
        );
    }

    protected function makeTitle()
    {
        $titulos = $this->getColumns();
        $dados   = $this->getData();
        $process = new Process($this->filters);
        $this->level  = ($process->temComposicao()) ? 3 : 2;

        foreach ($titulos as &$titulo) {
            if ($titulo->getField() == 'parametro') {
                $titulo->setColumns($process->getTitulosParametro());
                $titulo->setWidth($titulo->setRecursiveWidthByColumns());
                $titulo->setHeight(1);
            } else {
                $titulo->setHeight($this->level);
            }
        }

        return $titulos;
    }

    public function getDataByParam($key, $index) 
    {
        if (isset($this->lists[$index][$key]) && $this->lists[$index][$key] != '') {
            return $this->lists[$index][$key];
        } else {
            return '';                
        }            
    }
    
    abstract public function render(); 
    abstract public function totalLines();
    abstract public function makeFilters();
}
