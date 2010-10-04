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

    private $fatorWidth;
    private $fatorHeight;
    private $orientation;
    private $format;
    
    public function __construct($orientation = 'L', $format = 'A4')
    {
        parent::__construct();
        $this->fpdf = new myFPDF();
        $this->fpdf->setToday($this->todayBR);
        $this->fpdf->setUserName($this->userName);
        $this->fatorWidth  = 2;
        $this->fatorHeight = 6;
        $this->orientation = $orientation;
        $this->format      = $format;
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

    public function pulaLinhaFiltro($numX, $numY, $numHeight)
    {
        $numY = $this->fpdf->GetY() + $numHeight;
        $this->fpdf->SetXY($numX, $numY);

        return $numY;
    }

    public function makeFilters()
    {
        $intBorder = 1;
        $numHeight = 4;
        $numX = 65;
        $numY = $this->fpdf->GetY();
        $this->fpdf->SetX($numX);

        $this->printCellFilter($numHeight, array('index'   => 'projeto', 
                                                 'field'   => 'Projetos: ', 
                                                 'replace' => true));
        
        $numY = $this->pulaLinhaFiltro($numX, $numY, $numHeight);

        $this->printCellFilter($numHeight, array('index'   => 'lagoa', 
                                                 'field'   => 'Lagoas: ', 
                                                 'replace' => true));
        
        $numY = $this->pulaLinhaFiltro($numX, $numY, $numHeight);
        
        $this->printCellFilter($numHeight, array('index'   => 'ponto_amostral', 
                                                 'field'   => 'Pontos amostrais: ', 
                                                 'replace' => true));
           
        $numY = $this->pulaLinhaFiltro($numX, $numY, $numHeight);
        
        $this->printCellFilter($numHeight, array('index'   => 'parametro', 
                                                 'field'   => latinToUTF('Parâmetros: '), 
                                                 'replace' => true));

        $numY = $this->pulaLinhaFiltro($numX, $numY, $numHeight);
        
        $this->printCellFilter($numHeight, array('index'   => 'categoria', 
                                                 'field'   => 'Categoria: ', 
                                                 'replace' => true));   

        $numY = $this->pulaLinhaFiltro($numX, $numY, $numHeight);

        $this->printCellFilter($numHeight, array('index'   => 'tipo_periodo', 
                                                 'field'   => latinToUTF('Período: '), 
                                                 'replace' => true));   

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

    /**
     * Imprime os nomes das colunas
     * 
     * @access private
     * @return void
     */
    private function makeColumns()
    {
        $order = array();
        $this->makeTitle();

        $this->fpdf->SetFont('Arial', 'B', 6);     
        $this->fpdf->SetFillColor(96);
        $this->fpdf->SetTextColor(255);

        $this->setRecursiveWidth($this->getColumnByField('parametro')); // Faz novamente para pega a largura com fpdf
        $this->makeRecursiveColumns(4, $this->fpdf->GetY() + 5, $this->getColumns(), &$ordem);

        $this->fpdf->SetFillColor(255);
        $this->fpdf->SetTextColor(0);
        $this->fpdf->SetFont('Arial', '', 9);  

        return $ordem;
    }

    public function setRecursiveWidth($column)
    {
        $width = 0;
        $count = 0;
        $stringWidth = $this->fpdf->GetStringWidth($column->getText());
        $stringWidth = ($stringWidth < 6) ? 6 : $stringWidth;
        if (is_array($column->getColumns())) {
            foreach ($column->getColumns() as $coluna) {
                $width += $this->setRecursiveWidth($coluna);
            }
            $column->setWidth($width);
            $column->setHeight(1);
        } else {
            $column->setWidth($stringWidth);
        }

        return $column->getWidth();
    }

    private function makeRecursiveColumns($x, $y, array $columns, $ordem) 
    {
        foreach ($columns as $column) {

            $width  = $column->getWidth() * $this->fatorWidth;
            $height = $column->getHeight() * $this->fatorHeight;
            $text   = $column->getText();
            $align  = (isset($this->fpdf->aligns[$column->getField()])) ? $this->fpdf->aligns[$column->getField()] : 'L';

            $this->fpdf->SetXY($x, $y);
            $this->fpdf->Cell($width, $height, $text, 1, 1, $align, 1);            

            if (is_array($column->getColumns())) {
                $this->makeRecursiveColumns($x, $y + 6, $column->getColumns(), &$ordem);
            } else {
                $ordem[] = array(
                    'id'     => $column->getId(), 
                    'field'  => $column->getField(),
                    'width'  => $width,
                    'height' => $height,
                    'text'   => $text
                );
            }

            $x += $width;
        }
    }

    protected function printLines()
    {
        // logica das linhas        
        $this->fpdf->currentLine = 1;        
        $ordem = $this->makeColumns();

        $this->fpdf->SetFont('Arial', 'B', 6);     

        foreach ($this->data as $dado) {
            $widths = $heights = $aligns = $texts = array();

            $this->fpdf->SetX(4);

            if ($this->fpdf->currentLine % 2 != 0) {
                $this->fpdf->SetFillColor(255, 255, 255);
            } else {
                $this->fpdf->SetFillColor(224, 224, 224);
            }            

            for ($i = 0; $i < 6; $i++) {
                $atribute  = $this->columns[$i]->getField();
                $widths[]  = $this->columns[$i]->getWidth() * $this->fatorWidth;
                $heights[] = $this->columns[$i]->getHeight();
                $texts[]   = $dado->$atribute;
                $aligns[]  = 'L';
            }

            $i = 6;
            while ($i < count($ordem)) {
                $text = '';
                foreach ($dado->parametro as $parametro) {
                    if (!is_array($parametro->composicao)) {
                        if ($ordem[$i]['field'] == 'id_parametro' && $ordem[$i]['id'] == $parametro->id_parametro) {
                            $text = $parametro->valor;
                        }
                    } else {
                        foreach ($parametro->composicao as $especie) {
                            if ($ordem[$i]['field'] == 'id_especie' && $ordem[$i]['id'] == $especie->id_especie) {
                                $text = $especie->quantidade;
                            }
                        }
                    }
                }

                $widths[]  = $ordem[$i]['width'];
                $heights[] = $ordem[$i]['height'];
                $texts[]   = $text;
                $aligns[]  = 'L';
                $i++;
            }

            $this->fpdf->SetWidths($widths);
            $this->fpdf->SetHeight($heights);
            $this->fpdf->SetAligns($aligns);
            $this->fpdf->border(true);

            $this->fpdf->Row($texts);

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
        $this->fpdf->setReportName(mb_convert_encoding($this->getReportName(), 'ISO-8859-1', 'UTF-8'));
        $this->fpdf->AddPage($this->orientation, $this->format);
        $this->fpdf->AliasNbPages();

        $this->makeFilters();
        
        $this->totalLines();
        
        $this->fpdf->doBorder = false;
        $this->fpdf->doFills = true;
        
        $this->printLines();
        
        $this->fpdf->Output('Relatorio.pdf', 'I');
    }
}
?>
