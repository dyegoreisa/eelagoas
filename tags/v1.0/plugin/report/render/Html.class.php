<?php

require_once SMARTY_TEMPLATE . 'Smarty.class.php';

class Html extends Render
{
    private $formatedData;
    private $algins;

    public function __construct() 
    {
        parent::__construct();
    }

    public function totalLines()
    {
        return count($this->data);
    }

    public function makeFilters()
    {
        $filters = array(
            'projeto' => $this->makeFilter(array(
                'index'   => 'projeto',
                'field'   => 'Projetos',
                'replace' => true
            )),

            'lagoa' => $this->makeFilter(array(
                'index'   => 'lagoa',
                'field'   => 'Lagoas',
                'replace' => true
            )),

            'ponto_amostral' => $this->makeFilter(array(
                'index'   => 'ponto_amostral',
                'field'   => 'Pontos amostrais',
                'replace' => true
            )),

            'parametro' => $this->makeFilter(array(
                'index'   => 'parametro',
                'field'   => 'Parametros',
                'replace' => true
            )),

            'categoria' => $this->makeFilter(array(
                'index'   => 'categoria',
                'field'   => 'Categorias',
                'replace' => true
            )),

            'tipo_periodo' => $this->makeFilter(array(
                'index'   => 'tipo_periodo',
                'field'   => 'Per&iacute;odo',
                'replace' =>  true
            )),
        );

        return $filters;
    }

    public function getFormattedTitles()
    {
        $titles = $this->getArrayColumnText();
        foreach ($titles as &$val) {
            $val = htmlentities($val, ENT_NOQUOTES, 'UTF-8');
        }
        return $titles;
    }

    public function getReportName()
    {
        return htmlentities($this->reportName, ENT_QUOTES, 'UTF-8');
    }

    public function makeHtml()
    {
        $nivel = 0;
        $ordem = array();

        echo "\n" . '<table id="report">' . "\n";
        $this->makeHeader($this->makeTitle(), &$ordem, &$nivel, true);
        $this->makeData($this->getData(), $ordem);
        echo '</table>' . "\n";
    }

    private function makeHeader(array $colunas, $ordem, $nivel = 0, $inicio = false)
    {
        $subHtml  = '';
        $beginTr  = "\t" . '<tr class="header">' . "\n";
        $endTr    = "\t" . '</tr>'. "\n";
       
        if ($nivel < 1) { 
            if (!$inicio) {
                echo $endTr;
            }
            echo $beginTr;
        }

        foreach ($colunas as $coluna) {
            if (is_array($coluna->getColumns())) {
                echo "\t\t" . '<th class="align_' . $coluna->getAlign() . '" colspan="' . $coluna->getWidth() . '">' . $coluna->getText() . '</th>' . "\n";
                ob_start();
                $this->makeHeader($coluna->getColumns(), &$ordem, $nivel++);
                $subHtml .= ob_get_contents();
                ob_end_clean();
            } else {
                echo "\t\t" . '<th class="align_' . $coluna->getAlign() . '" rowspan="' . $coluna->getHeight() . '">' . $coluna->getText() . '</th>' . "\n";
                $ordem[] = new Ordem($coluna->getId(), $coluna->getField());
            }
        }

        echo $subHtml;

        if ($inicio) {
            echo $endTr;
        }
    }

    private function makeData(array $dados, array $ordem) 
    {
        $beginTr[0] = "\t" . '<tr class="linha1">' . "\n";
        $beginTr[1] = "\t" . '<tr class="linha2">' . "\n";
        $endTr      = "\t" . '</tr>'. "\n";

        foreach ($dados as $key => $coleta) {
            
            echo $beginTr[$key%2];

            echo "\t\t" . '<td class="align_L">' . $coleta->getData() . '</td>' . "\n";
            echo "\t\t" . '<td class="align_L">' . $coleta->getNomeProjeto() . '</td>' . "\n";
            echo "\t\t" . '<td class="align_L">' . $coleta->getNomeLagoa() . '</td>' . "\n";
            echo "\t\t" . '<td class="align_L">' . $coleta->getNomePontoAmostral() . '</td>' . "\n";
            echo "\t\t" . '<td class="align_L">' . $coleta->getNomeCategoria() . '</td>' . "\n";
            echo "\t\t" . '<td class="align_L">' . $coleta->getProfundidade() . '</td>' . "\n";
            
            $qtdeOrdem = count($ordem);
            for ($i = 6; $i < $qtdeOrdem; $i++) {
            	$this->makeDataParametro($coleta, $ordem[$i]);           
            }

            echo $endTr;
        }
    }
    
    private function makeDataParametro($coleta, $ordem)
    {
        $objModel = $coleta->findParametro($ordem->getId(), $ordem->getField());
        if (isset($objModel)) {
            echo "\t\t" . '<td class="align_C">' . $objModel->getValor() . '</td>' . "\n";
        } else {
            echo "\t\t" . '<td class="align_">&nbsp;</td>' . "\n";
        }
    	
    }

    public function render()
    {
        echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"'
           . '"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">'
           . '<html>'
           . '<head>'
           . '<title>'
           . $this->getReportName()
           . '</title>'
           . '<link rel="stylesheet" type="text/css" href="' . SITE . '/plugin/report/render/css/report.css" />'
           . '<head>'
           . '<body>'
           . '<img src="' . REP_LOGO_HTML . '" id="logo">'
           . '<div id="header">'
           . '    <h2>' . $this->getReportName() . '</h2>'
           . '    <p><b>Emitido por:</b> ' . $this->getUserName() . '</p>'
           . '    <p><b>Emiss&atilde;o:</b> ' . $this->getTodayBR() . '</p>'
           . '</div>'
           . '<div id="filter">'
           . '    <ul>';

        $filtros = $this->makeFilters();
        foreach ($filtros as $filtro) {
           echo '            <li><b>' . $filtro['field'] . ':</b>  ' . $filtro['value'] . '</li>';
        }

        echo '    </ul>'
           . '</div>'
           . '<p id="total">Total de registros impressos: ' . $this->totalLines() . '</p>';

        $this->makeHtml();
         
        echo '</body></html>';
    }
}
