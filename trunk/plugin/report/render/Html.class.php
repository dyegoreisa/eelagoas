<?php

require_once 'Smarty.class.php';

class Html extends Render
{
    private $formatedData;
    private $smarty;

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
            'lagoa' => $this->makeFilter(array('index'   => 'lagoa',
                                               'field'   => 'Lagoas',
                                               'replace' => true)),

            'ponto_amostral' => $this->makeFilter(array('index'   => 'ponto_amostral',
                                                        'field'   => 'Pontos amostrais',
                                                        'replace' => true)),

            'parametro' => $this->makeFilter(array('index'   => 'parametro',
                                                   'field'   => 'Parametros',
                                                   'replace' => true)),

            'id_categoria' => $this->makeFilter(array('index'   => 'id_categoria',
                                                      'field'   => 'Categorias',
                                                      'replace' => true)),

            'data_inicio' => $this->makeFilter(array('index'   =>  'data_inicio',
                                                     'field'   => 'Inicio',
                                                     'replace' => false)),

            'data_fim' => $this->makeFilter(array('index'   => 'data_fim',
                                                  'field'   => 'Fim',
                                                  'replace' => false))

        );

        return $filters;
    }

    public function render()
    {
        $smarty = $this->smarty;

        $smarty->assign('barra_titulo', $this->getReportName());
        $smarty->assign('dados', $this->getData());
        $smarty->assign('colunas', $this->getColumns());
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
