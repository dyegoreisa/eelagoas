<?php
class Ctrl_Relatorio extends BaseController
{
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $parametro;
    private $usuario;

    public function __construct() 
    {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->lagoa         = new Lagoa($dbh);
        $this->pontoAmostral = new PontoAmotral($dbh);
        $this->categoria     = new Categoria($dbh);
        $this->parametro     = new Parametro($dbh);
        $this->usuario       = new Usuario($dbh);
    }
    
    public function reportInterface()
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc() );
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

        $columns = array(
            'data'                => 'Data: ',
            'nome_lagoa'          => 'Lagoa: ',
            'nome_ponto_amostral' => 'Ponto Amostral: ',
            'nome_categoria'      => 'Categoria: ',
            'nome_parametro'      => 'Parametro: ',
            'profundidade'        => 'Profundidade: ',
            'valor'               => 'Valor: '
        );

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
                break;

            case 'xls':
                break;

            default:
        }

        $render->setColumns($columns);
        $render->setFilters($filtros);
        $render->setUserName($userName);
        $render->setLists(array(
            'lagoa'          => $this->lagoa->listarSelectAssoc(),
            'id_categoria'   => $this->categoria->listarSelectAssoc(),
            'parametro'      => $this->parametro->listarSelectAssoc(),
            'ponto_amostral' => $this->pontoAmostral->listarSelectAssoc($idLagoas)
        ));
        $render->render();
    }
}
