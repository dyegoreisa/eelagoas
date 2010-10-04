<?php

require_once SMARTY_TEMPLATE . 'Smarty.class.php';

class Html extends Render
{
    private $formatedData;
    private $smarty;
    private $algins;

    public function __construct() 
    {
        parent::__construct();
        $this->smarty = new Smarty();
        $this->smarty->template_dir = TEMPLATE_DIR;
        $this->smarty->compile_dir  = COMPILE_DIR;
        $this->smarty->config_dir   = CONFIG_DIR;
        $this->smarty->cache_dir    = CACHE_DIR;
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
        $header = $this->makeHeader($this->makeTitle(), &$ordem, &$nivel, true);
        $data = $this->makeData($this->getData(), $ordem);

        $html = array_merge($header, $data);

        array_unshift($html, "\n" . '<table id="report">' . "\n");
        $html[] = '</table>' . "\n";

        return implode($html);
    }

    private function makeHeader(array $colunas, $ordem, $nivel = 0, $inicio = false)
    {
        $html     = array();
        $subHtml  = array();
        $beginTr  = "\t" . '<tr class="header">' . "\n";
        $endTr    = "\t" . '</tr>'. "\n";
       
        if ($nivel < 1) { 
            if (!$inicio) {
                $html[] = $endTr;
            }
            $html[] = $beginTr;
        }

        foreach ($colunas as $coluna) {
            if (is_array($coluna->getColumns())) {
                $html[]  = "\t\t" . '<th class="align_' . $coluna->getAlign() . '" colspan="' . $coluna->getWidth() . '">' . $coluna->getText() . '</th>' . "\n";
                $subHtml = array_merge($subHtml, $this->makeHeader($coluna->getColumns(), &$ordem, $nivel++));
            } else {
                $html[]  = "\t\t" . '<th class="align_' . $coluna->getAlign() . '" rowspan="' . $coluna->getHeight() . '">' . $coluna->getText() . '</th>' . "\n";
                $ordem[] = array(
                    'id'    => $coluna->getId(), 
                    'field' => $coluna->getField()
                );
            }
        }

        $html = array_merge($html, $subHtml);

        if ($inicio) {
            $html[] = $endTr;
        }

        return $html;
    }

    private function makeData(array $dados, array $ordem) 
    {
        $html       = array();
        $beginTr[0] = "\t" . '<tr class="linha1">' . "\n";
        $beginTr[1] = "\t" . '<tr class="linha2">' . "\n";
        $endTr      = "\t" . '</tr>'. "\n";

        foreach ($dados as $key => $dado) {
            $html[] = $beginTr[$key%2];

            $html[] = "\t\t" . '<td class="align_L">' . $dado->data . '</td>' . "\n";
            $html[] = "\t\t" . '<td class="align_L">' . $dado->nome_projeto . '</td>' . "\n";
            $html[] = "\t\t" . '<td class="align_L">' . $dado->nome_lagoa . '</td>' . "\n";
            $html[] = "\t\t" . '<td class="align_L">' . $dado->nome_ponto_amostral . '</td>' . "\n";
            $html[] = "\t\t" . '<td class="align_L">' . $dado->nome_categoria . '</td>' . "\n";
            $html[] = "\t\t" . '<td class="align_L">' . $dado->profundidade . '</td>' . "\n";

            for ($i = 6; $i < count($ordem); $i++) {
                $encontrado = false;
                foreach ($dado->parametro as $parametro) {
                    if (!is_array($parametro->composicao)) {
                        if ($ordem[$i]['field'] == 'id_parametro' && $ordem[$i]['id'] == $parametro->id_parametro) {
                            $html[] = "\t\t" . '<td class="align_C">' . $parametro->valor . '</td>' . "\n";
                            $encontrado = true;
                        }
                    } else {
                        foreach ($parametro->composicao as $especie) {
                            if ($ordem[$i]['field'] == 'id_especie' && $ordem[$i]['id'] == $especie->id_especie) {
                                $html[] = "\t\t" . '<td class="align_C">' . $especie->quantidade . '</td>' . "\n";
                                $encontrado = true;
                            }
                        }
                    }
                }
                if (!$encontrado) {
                    $html[] = "\t\t" . '<td class="align_">&nbsp;</td>' . "\n";
                }
            }

            $html[] = $endTr;
        }

        return $html;
    }

    public function render()
    {
        $smarty = $this->smarty;
        //$smarty->debugging = true;

        $smarty->assign('site', SITE);
        $smarty->assign('barra_titulo', $this->getReportName());
        $smarty->assign('alinhamento', $this->getArrayColumnAlign());
        $smarty->assign('total_linhas', $this->totalLines());
        $smarty->assign('logo', REP_LOGO_HTML);
        $smarty->assign('nome_relatorio', $this->getReportName());
        $smarty->assign('usuario', $this->getUserName());
        $smarty->assign('data_emissao', $this->getTodayBR());
        $smarty->assign('filtros', $this->makeFilters());
        $smarty->assign('render_html', $this->makeHtml());

        $smarty->display('header.tpl');
        $smarty->display('body.tpl');
        $smarty->display('footer.tpl');
    }
}
