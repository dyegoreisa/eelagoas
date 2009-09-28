<?php

require_once 'lib/myFPDF.class.php';

class Pdf extends Render
{
    
    private $heightColumn;
    private $fpdf;
    
    public function __construct()
    {
        parent::__construct();
        $this->fpdf = new myFPDF();
        $this->fpdf->setToday($this->todayBR);
        $this->fpdf->setUserName($this->userName);
    }
    
    public function setUserName($strUserName)
    {
        $this->userName = mb_convert_encoding($strUserName, 'ISO-8859-1', 'UTF-8');
        $this->fpdf->setUserName($this->userName);
    }

    public function getUserName()
    {
        return $this->strUserName;
    }
    
    public function setDados(array $data)
    {
        $this->data = $data;
    } 
    
    public function getData()
    {
        return $this->data;
    }
    
    public function setFilters(array $filters)
    {
        $this->filters = $filters;
    }
    
    public function getTodayBR()
    {
        return $this->todayBR;
    }
    
    public function getToday()
    {
        return $this->today;
    }
    
    public function setReportName($reportName)
    {
        $this->reportName = $reportName;
        $this->fpdf->setReportName($this->reportName);
    }
    
    public function getReportName()
    {
        return $this->reportName;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
    }

    private function makeFilters()
    {
        $intBorder = 1;
        $numHeight = 4;
        $numX = 50;
        $numY = $this->fpdf->GetY();
        $this->fpdf->SetX($numX);

        $this->printCellFilters($numHeight, array('index'   => 'lagoa', 
                                                  'field'   => 'Lagoas: ', 
                                                  'replace' => true));
        
        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilters($numHeight, array('index'   => 'ponto_amostral', 
                                                  'field'   => 'Pontos amostrais: ', 
                                                  'replace' => true));
           
        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilters($numHeight, array('index'   => 'parametro', 
                                                  'field'   => 'Parametros: ', 
                                                  'replace' => true));

        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilters($numHeight, array('index'   => 'id_categoria', 
                                                  'field'   => 'Categoria: ', 
                                                  'replace' => true));   

        $this->printCellFilters($numHeight, array('index'   => 'periodo', 
                                                  'field'   => mb_convert_encoding('Período: ', 'ISO-8859-1', 'UTF-8'), 
                                                  'replace' => false,
                                                  'void'    => true));

        $this->printCellFilters($numHeight, array('index'   => 'data_inicio', 
                                                  'field'   => mb_convert_encoding('Início: ', 'ISO-8859-1', 'UTF-8'), 
                                                  'replace' => false));
        
        $this->printCellFilters($numHeight, array('index'   => 'data_fim', 
                                                  'field'   => 'Fim: ', 
                                                  'replace' => false));
        
    }
    
    public function getDataByParam($key, $index) 
    {
        if (isset($this->lists[$index][$key]) && $this->lists[$index][$key] != '') {
            return $this->lists[$index][$key];
        } else {
            return '';                
        }            
    }
    
    /**
     * Monta e imprime uma célula do filtro
     * 
     * A variável $arrParam deve ser enviada conforme o exemplo:
     *  array('index'   => 'indice_exemplo', // Indice do array $arrFiltros do objeto
     *        'field'   => 'Label exemplo: ',// Label que aparecerá no relatório
     *        'replace' => true,             // true para subistituição de valor e false quando o é valor literal
     *        'void'    => true)             // true quando quer imprimir somente o Label e omitir este parametro  
     *                                       // quando o for imprimir o valor também 
     * 
     * @param float $numHeight - Altura do campo
     * @param array $params - Array de paramentros
     * @access private
     * @return void
     */
    private function printCellFilters($numHeight, $params)
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
        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->Cell($this->fpdf->GetStringWidth($params['field']), $numHeight, $params['field'], 0, 0, 'L', 0);
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell($this->fpdf->GetStringWidth($text) + 5, $numHeight, $text, 0, 0, 'L', 0);         
    }
    
    private function totalLines()
    {
        $this->fpdf->SetFont('Arial', 'B', 10);

        $totalData = count($this->data);

        $text = "Total de registros impressos: $totalData";

        $xBefore = $this->fpdf->GetX();
        $yBefore = $this->fpdf->GetY();
        $this->fpdf->SetX(225);
        $this->fpdf->Cell(70, 4, $text, 0, 1, 'R', 0);
        $this->fpdf->SetXY($xBefore, $yBefore);
    }
    
    public function setHeightColumn()
    {
        $this->heightColumn = array (
            'data'                => 30,
            'nome_lagoa'          => 60,
            'nome_ponto_amostral' => 50,
            'nome_categoria'      => 60,
            'nome_parametro'      => 50,
            'profundidade'        => 25,
            'valor'               => 15
        );
    }

    /**
     * Imprime os nomes das colunas
     * 
     * @access private
     * @return void
     */
    private function makeColumns()
    {
        $this->fpdf->SetFont('Arial', 'B', 9);     
        $this->fpdf->SetFillColor(96);
        $this->fpdf->SetTextColor(255);
        foreach ($this->columns as $key => $val) {
            if (!isset($x)) {
                $x = 4;
                $y = $this->fpdf->GetY() + 5;                
                $this->fpdf->SetXY($x, $y);
            } else {
                $this->fpdf->SetXY($x, $y);
            }
            $this->fpdf->Cell($this->heightColumn[$key], 6, $val, 0, 1, 'L', 1);            
            $x += $this->heightColumn[$key];                
        }
        $this->fpdf->SetFillColor(255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('Arial', '', 9);  
    }

    public function makeList()
    {
        $this->setHeightColumn();        
        $this->fpdf->setReportName(mb_convert_encoding('Relatório', 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->AddPage('L', 'A4');
        $this->fpdf->AliasNbPages();

        $this->makeFilters();
        
        $this->totalLines();
        
        // logica das linhas
        $count = 1;
        $this->makeColumns();
        foreach ($this->data as $key => $data) {
            // Faz paginação
            if (($count % 29) == 0) {
                $this->fpdf->AddPage('L', 'A4');
                $this->makeColumns();
            }
            
            // Intercala cor da linha
            if ($count % 2 != 0) {
                $this->fpdf->SetFillColor(255, 255, 255);
            } else {
                $this->fpdf->SetFillColor(224, 224, 224);
            }
            
            unset($x);            
            foreach ($this->columns as $keyColumn => $column) {
                if (!isset($x)) {
                    $x = 4;
                    $this->fpdf->SetX($x);
                    $y = $this->fpdf->GetY();
                } else {
                    $this->fpdf->SetXY($x, $y);
                }
                $value = $data[$keyColumn];
                $this->fpdf->Cell($this->heightColumn[$keyColumn], 5, $value, 0, 1, 'L', 1);
                $x += $this->heightColumn[$keyColumn];
            }
            $count++;
        }
    }

    /**
     * Imprime o arquivo PDF
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        $this->makeList();
        
        $this->fpdf->Output();
    }
}
?>
