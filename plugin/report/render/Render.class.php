<?php
abstract class Render
{
    /**
     * Objeto que contém dados vindo do banco
     * 
     * @var Result
     * @access protected
     */
    protected $result;

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

    public function getData()
    {
        return $this->data;
    }

    public function setResult(Result $result) 
    {
        $this->result = $result;
        $this->data   = $this->result->execute();
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
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
