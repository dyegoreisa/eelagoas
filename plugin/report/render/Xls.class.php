<?php

require_once 'Writer.php';

class Xls extends Render
{
    private $workbook;
    private $worksheet;
    private $fills;
    private $aligns;
    private $widths;
    private $titles;
    private $x;
    private $y;
    
    public function __construct()
    {
        parent::__construct();
        $this->workbook = new Spreadsheet_Excel_Writer();
        $this->x = 0;
        $this->y = 0;
    }

    public function prepareColumns()
    {
        $this->fills  = $this->getArrayColumnFill();
        $this->aligns = $this->getArrayColumnAlign();
        $this->widths = $this->getArrayColumnWidth();
        $this->titles = $this->getArrayColumnText();
    }

    private function addWorksheet($name)
    {
        $this->worksheet =& $this->workbook->addWorksheet($name);
    }

    public function makeFilters()
    {
        $this->y = 2;

        $this->printCellFilter(array('index'   => 'lagoa', 
                                     'field'   => 'Lagoas: ', 
                                     'replace' => true));

        $this->printCellFilter(array('index'   => 'ponto_amostral', 
                                     'field'   => 'Pontos amostrais: ', 
                                     'replace' => true));
        
        $this->printCellFilter(array('index'   => 'parametro', 
                                     'field'   => 'Parametros: ', 
                                     'replace' => true));
        
        $this->printCellFilter(array('index'   => 'id_categoria', 
                                     'field'   => 'Categoria: ', 
                                     'replace' => true));   
        
        $this->printCellFilter(array('index'   => 'periodo', 
                                     'field'   => mb_convert_encoding('Período: ', 'ISO-8859-1', 'UTF-8'), 
                                     'replace' => false,
                                     'void'    => true));
        
        $this->y++; // Próxima coluna
        $this->x--; // Não pula linha
        $this->printCellFilter(array('index'   => 'data_inicio', 
                                     'field'   => mb_convert_encoding('Início: ', 'ISO-8859-1', 'UTF-8'), 
                                     'replace' => false));
        
        $this->y += 2; // Próximas 2 colunas
        $this->x--;    // Não pula linha
        $this->printCellFilter(array('index'   => 'data_fim', 
                                     'field'   => 'Fim: ', 
                                     'replace' => false));
        $this->y = 0;
    }
    
    private function printCellFilter($params) 
    {
        $filter = $this->makeFilter($params);

        $this->worksheet->writeString($this->x, $this->y, $filter['field']);
        $this->worksheet->writeString($this->x, $this->y + 1, $filter['value']);
        $this->x++;
    }
    
    private function makeHead()
    {
        $this->worksheet->insertBitmap($this->x, $this->y, REP_LOGO_XLS);
        $this->y = count($this->titles) - 1;
        $this->worksheet->write($this->x++, $this->y, mb_convert_encoding($this->getReportName(), 'ISO-8859-1', 'UTF-8'));
        $this->worksheet->write($this->x++, $this->y, 'Emitido por: ' . $this->getUserName());
        $this->worksheet->write($this->x++, $this->y, 'Emitido em: ' . $this->getTodayBR());
    }

    public function totalLines()
    {
        $totalData = count($this->data);

        $text = "Total de registros impressos: $totalData";

        $this->y = count($this->titles) - 1;
        $this->worksheet->writeString(++$this->x, $this->y, $text);
        $this->x++;
    }

    /**
     * Imprime os nomes das colunas
     * 
     * @access private
     * @return void
     */
    private function makeColumns()
    {
        $this->worksheet->writeRow($this->x, 0, $this->titles);
        $this->x++;
    }

    protected function printLines()
    {
        $this->makeColumns();
        foreach ($this->data as $key => $val) {
            $this->worksheet->writeRow($this->x, 0, $val);
            $this->x++;
        }
    }

    public function Titles()
    {
        $this->makeColumns();
    }

    public function makeList()
    {
        $this->makeFilters();
        
        $this->totalLines();
        
        $this->printLines();
    }

    /**
     * Gera arquivo em excel
     * 
     * @access public
     * @return void
     */
    public function render()
    {
        $nomeArquivo = 'relatorio-' . date('Y-m-d-G-i') . '.xls';
        $this->workbook->send($nomeArquivo);

        $this->addWorksheet(mb_convert_encoding($this->getReportName(), 'ISO-8859-1', 'UTF-8'));

        $this->makeHead();

        $this->makeList();
        
        $this->workbook->close();
    }
}
?>
