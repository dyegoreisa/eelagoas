<?php
class Ctrl_Relatorio extends BaseController
{
    private $projeto;
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $parametro;
    private $usuario;

    public function __construct() 
    {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->projeto       = new Projeto($dbh);
        $this->lagoa         = new Lagoa($dbh);
        $this->pontoAmostral = new PontoAmotral($dbh);
        $this->categoria     = new Categoria($dbh);
        $this->parametro     = new Parametro($dbh);
        $this->usuario       = new Usuario($dbh);
    }
    
    public function reportInterface()
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_projeto', $this->projeto->listarSelectAssoc() );
        $smarty->assign( 'select_categoria', $this->categoria->listarSelectAssoc() );
        $smarty->assign( 'select_parametro', $this->parametro->listarSelectAssoc() );
        $smarty->displayHBF( 'interface.tpl' );
    }


    public function reportExecute()
    {   
        $filtros       = $_POST;
        $tipoRelatorio = $_POST['tipo_relatorio'];
        $idLagoas      = (isset($_POST['lagoa']) && $_POST['lagoa'] != '') ? implode(',', $_POST['lagoa']) : '';

        $this->usuario->setId($_SESSION['id_usuario']);
        $this->usuario->pegar();
        $userName = $this->usuario->getData('nome');

        $report = new Report();
        $result = $report->getResult();
        $result->setDBH($this->getDBH());
        $result->setFilters($filtros);

        $report->addColumn('data',                'Data: ',           'L', 1, 30);
        $report->addColumn('nome_lagoa',          'Lagoa: ',          'L', 1, 60);
        $report->addColumn('nome_ponto_amostral', 'Ponto Amostral: ', 'L', 1, 50);
        $report->addColumn('nome_categoria',      'Categoria: ',      'L', 1, 60);
        $report->addColumn('nome_parametro',      'Parâmetro: ',      'L', 1, 50);
        $report->addColumn('profundidade',        'Profundidade: ',   'R', 1, 25);
        $report->addColumn('valor',               'Valor: ',          'R', 1, 15);

        switch ($tipoRelatorio) {
            case 'html':
                $report->setHtml();
                $render = $report->getRender();
                $render->setReportName('Relat&oacute;rio');
                break;

            case 'pdf':
                $report->setPdf();
                $render = $report->getRender();
                $render->setReportName('Relatório');
                $render->prepareColumns();
                break;

            case 'xls':
                $report->setXls();
                $render = $report->getRender();
                $render->setReportName('Relatório');
                $render->prepareColumns();
                break;

            default:
                die('Tipo de relatório não informado.');
        }


        //$render->setColumns($columns);
        $render->setFilters($filtros);
        $render->setUserName($userName);
        $render->setLists(array(
            'lagoa'          => $this->lagoa->listarSelectAssoc(),
            'categorias'     => $this->categoria->listarSelectAssoc(),
            'parametro'      => $this->parametro->listarSelectAssoc(),
            'ponto_amostral' => $this->pontoAmostral->listarSelectAssoc($idLagoas)
        ));
        $render->render();
    }
}
