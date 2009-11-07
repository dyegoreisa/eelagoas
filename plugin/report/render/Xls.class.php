<?php

require_once SPREADSHEET . 'Writer.php';

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
    private $format;
    
    public function __construct()
    {
        parent::__construct();
        $this->workbook = new Spreadsheet_Excel_Writer();
        $this->x = 0;
        $this->y = 0;

        $this->format();
    }

    public function format()
    {
        // Setando as cores
        $this->workbook->setCustomColor(8, 224, 224, 224); // Linhas intercaladas
        $this->workbook->setCustomColor(9, 95, 95, 95); // Cor de fundo dos nomes das coluna
        $this->workbook->setCustomColor(10, 255, 255, 255); // Cor dos nomes das coluna

        // Formatação do cabeçalho
        $this->format['head'] =& $this->workbook->addFormat();
        $this->format['head']->setBold();
        $this->format['head']->setAlign('right');

        // Formatação da segunda parte do cabeçalho
        $this->format['subhead'] =& $this->workbook->addFormat();
        $this->format['subhead']->setAlign('right');

        // Formatação do nomes dos campos dos filtros
        $this->format['filter_field'] =& $this->workbook->addFormat();
        $this->format['filter_field']->setBold();

        // Formatação dos valores dos filtros
        $this->format['filter_value'] =& $this->workbook->addFormat();

        // Formatação dos nomes das colunas
        $this->format['columns'] =& $this->workbook->addFormat();
        $this->format['columns']->setBold();
        $this->format['columns']->setFgColor(9);
        $this->format['columns']->setColor(10);

        // Formatação dos nomes das colunas para colunas de números
        $this->format['columns_number'] =& $this->workbook->addFormat();
        $this->format['columns_number']->setBold();
        $this->format['columns_number']->setFgColor(9);
        $this->format['columns_number']->setColor(10);
        $this->format['columns_number']->setAlign('right');

        // Formatação da linha que mostra o total de registros
        $this->format['total'] =& $this->workbook->addFormat();
        $this->format['total']->setBold();
        $this->format['total']->setAlign('right');

        // Formatação das linhas intercaladas
        $this->format['row_even'] =& $this->workbook->addFormat();
        $this->format['row_even']->setFgColor(8);

        // Formatação das linhas intercaladas
        $this->format['row_odd'] =& $this->workbook->addFormat();
    }

    public function prepareColumns()
    {
        $this->fills  = $this->getArrayColumnFill();
        $this->aligns = $this->getArrayColumnAlign();
        $this->widths = $this->getArrayColumnWidth();
        $this->titles = $this->getArrayColumnText();

        $this->totalColmuns = count($this->titles);
    }

    private function addWorksheet($name)
    {
        $this->worksheet =& $this->workbook->addWorksheet($name);
        $this->worksheet->hideScreenGridlines();
        $this->worksheet->setColumn(0, $this->totalColmuns, 18);
    }

    public function makeFilters()
    {
        $this->y = 0;

        $this->printCellFilter(array('index'   => 'lagoa', 
                                     'field'   => 'Lagoas: ', 
                                     'replace' => true));

        $this->printCellFilter(array('index'   => 'ponto_amostral', 
                                     'field'   => 'Pontos amostrais: ', 
                                     'replace' => true));
        
        $this->printCellFilter(array('index'   => 'parametro', 
                                     'field'   => mb_convert_encoding('Parâmetros: ', 'ISO-8859-1', 'UTF-8'), 
                                     'replace' => true));
        
        $this->printCellFilter(array('index'   => 'categorias', 
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

        $this->worksheet->writeString($this->x, $this->y, $filter['field'], $this->format['filter_field']);
        $this->worksheet->writeString($this->x, $this->y + 1, $filter['value'], $this->format['filter_value']);
        $this->x++;
    }
    
    private function makeHead()
    {
        $this->worksheet->insertBitmap($this->x, $this->y, REP_LOGO_XLS, 0, 0, 1, 1);
        $this->y = $this->totalColmuns - 1;
        $this->worksheet->write($this->x++, $this->y, mb_convert_encoding($this->getReportName(), 'ISO-8859-1', 'UTF-8'), $this->format['head']);
        $this->worksheet->write($this->x++, $this->y, 'Emitido por: ' . $this->getUserName(), $this->format['subhead']);
        $this->worksheet->write($this->x++, $this->y, 'Emitido em: ' . $this->getTodayBR(), $this->format['subhead']);
        $this->x++;
    }

    public function totalLines()
    {
        $totalData = count($this->data);

        $text = "Total de registros impressos: $totalData";

        $this->y = $this->totalColmuns - 1;
        $this->worksheet->writeString(++$this->x, $this->y, $text, $this->format['total']);
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
        $this->y = 0;
        foreach ($this->titles as $key => $val) {
            $format = ($this->aligns[$key] == 'R') ? 'columns_number' : 'columns';
            $this->worksheet->writeString($this->x, $this->y, mb_convert_encoding($val, 'ISO-8859-1', 'UTF-8'), $this->format[$format]);
            $this->y++;
        }
        $this->x++;
    }

    protected function printLines()
    {
        $count = 0;
        $this->makeColumns();
        foreach ($this->data as $key => $val) {
            $cyle = ($count % 2) ? 'row_even' : 'row_odd';
            $this->worksheet->writeRow($this->x, 0, $val, $this->format[$cyle]);
            $this->x++;
            $count++;
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
