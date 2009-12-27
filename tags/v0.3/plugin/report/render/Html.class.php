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
            'projeto' => $this->makeFilter(array('index'   => 'projeto',
                                                 'field'   => 'Projetos',
                                                 'replace' => true)),

            'lagoa' => $this->makeFilter(array('index'   => 'lagoa',
                                               'field'   => 'Lagoas',
                                               'replace' => true)),

            'ponto_amostral' => $this->makeFilter(array('index'   => 'ponto_amostral',
                                                        'field'   => 'Pontos amostrais',
                                                        'replace' => true)),

            'parametro' => $this->makeFilter(array('index'   => 'parametro',
                                                   'field'   => 'Parametros',
                                                   'replace' => true)),

            'categorias' => $this->makeFilter(array('index'   => 'categorias',
                                                    'field'   => 'Categorias',
                                                    'replace' => true)),
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

    public function render()
    {
        $smarty = $this->smarty;

        $smarty->assign('barra_titulo', $this->getReportName());
        $smarty->assign('dados', $this->getData());
        $smarty->assign('colunas', $this->getFormattedTitles());
        $smarty->assign('alinhamento', $this->getArrayColumnAlign());
        $smarty->assign('total_linhas', $this->totalLines());
        $smarty->assign('logo', REP_LOGO_HTML);
        $smarty->assign('nome_relatorio', $this->getReportName());
        $smarty->assign('usuario', $this->getUserName());
        $smarty->assign('data_emissao', $this->getTodayBR());
        $smarty->assign('filtros', $this->makeFilters());

        $smarty->display('header.tpl');
        $smarty->display('body.tpl');
        $smarty->display('footer.tpl');
    }
}
