<?php

require_once 'lib/myFPDF.class.php';

class Pdf extends Render
{
    private $fpdf;
    /**
     * Lista de titulos para as colunas
     * 
     * @var array
     * @access private
     */
    private $titles;
    
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

    public function setReportName($reportName)
    {
        $this->reportName = $reportName;
        $this->fpdf->setReportName($this->reportName);
    }
    
    public function prepareColumns()
    {
        $this->fpdf->fills = $this->getArrayColumnFill();
        $this->fpdf->aligns = $this->getArrayColumnAlign();
        $this->fpdf->widths = $this->getArrayColumnWidth();
        $this->titles = $this->getArrayColumnText();
    }

    public function makeFilters()
    {
        $intBorder = 1;
        $numHeight = 4;
        $numX = 65;
        $numY = $this->fpdf->GetY();
        $this->fpdf->SetX($numX);

        $this->printCellFilter($numHeight, array('index'   => 'lagoa', 
                                                  'field'   => 'Lagoas: ', 
                                                  'replace' => true));
        
        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilter($numHeight, array('index'   => 'ponto_amostral', 
                                                  'field'   => 'Pontos amostrais: ', 
                                                  'replace' => true));
           
        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilter($numHeight, array('index'   => 'parametro', 
                                                  'field'   => mb_convert_encoding('Parâmetros: ', 'ISO-8859-1', 'UTF-8'), 
                                                  'replace' => true));

        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilter($numHeight, array('index'   => 'categorias', 
                                                  'field'   => 'Categoria: ', 
                                                  'replace' => true));   

        // Pula linha
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);
        
        $this->printCellFilter($numHeight, array('index'   => 'periodo', 
                                                  'field'   => mb_convert_encoding('Período: ', 'ISO-8859-1', 'UTF-8'), 
                                                  'replace' => false,
                                                  'void'    => true));

        $this->printCellFilter($numHeight, array('index'   => 'data_inicio', 
                                                  'field'   => mb_convert_encoding('Início: ', 'ISO-8859-1', 'UTF-8'), 
                                                  'replace' => false));
        
        $this->printCellFilter($numHeight, array('index'   => 'data_fim', 
                                                  'field'   => 'Fim: ', 
                                                  'replace' => false));
        
    }
    
    private function printCellFilter($numHeight, $params) 
    {
        $filter = $this->makeFilter($params);

        $this->fpdf->SetFont('Arial', 'B', 9);
        $this->fpdf->Cell($this->fpdf->GetStringWidth($filter['field']), $numHeight, $filter['field'], 0, 0, 'L', 0);
        $this->fpdf->SetFont('Arial', '', 9);
        $this->fpdf->Cell($this->fpdf->GetStringWidth($filter['value']) + 5, $numHeight, $filter['value'], 0, 0, 'L', 0);         
    }
    
    public function totalLines()
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

    public function Titles()
    {
        $this->makeColumns();
        $this->fpdf->SetX(4);
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
        foreach ($this->titles as $key => $val) {
            if (!isset($x)) {
                $x = 4;
                $y = $this->fpdf->GetY() + 5;                
                $this->fpdf->SetXY($x, $y);
            } else {
                $this->fpdf->SetXY($x, $y);
            }

            $align = (isset($this->fpdf->aligns[$key])) ? $this->fpdf->aligns[$key] : 'L';
            $this->fpdf->Cell($this->fpdf->widths[$key], 6, mb_convert_encoding($val, 'ISO-8859-1', 'UTF-8'), 0, 1, $align, 1);            
            $x += $this->fpdf->widths[$key];                
        }
        $this->fpdf->SetFillColor(255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('Arial', '', 9);  
    }

    public function makeList()
    {
        $this->fpdf->setReportName(mb_convert_encoding($this->getReportName(), 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->AddPage('L', 'A4');
        $this->fpdf->AliasNbPages();

        $this->makeFilters();
        
        $this->totalLines();
        
        $this->fpdf->doBorder = false;
        $this->fpdf->doFills = true;
        
        $this->printLines();
    }

    protected function printLines()
    {
        // logica das linhas        
        $this->fpdf->currentLine = 1;        
        $this->makeColumns();
        foreach ($this->data as $key => $data) {
            if ($this->fpdf->currentLine % 2 != 0) {
                $this->fpdf->SetFillColor(255, 255, 255);
            } else {
                $this->fpdf->SetFillColor(224, 224, 224);
            }            
            $this->fpdf->SetX(4);
            $this->fpdf->Row($data);
            $this->fpdf->currentLine++;
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
