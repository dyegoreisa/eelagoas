<?php

require_once 'Relatorio.php';

class Ctrl_Relatorio extends BaseController implements Relatorio
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
    
    public function search()
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_projeto', $this->projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->assign( 'select_categoria', $this->categoria->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->assign( 'select_parametro', $this->parametro->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )) );
        $smarty->displayHBF( 'interface.tpl' );
    }


    public function execute()
    {   
        $filtros       = $_POST;
        $tipoRelatorio = $_POST['tipo_relatorio'];
        $idProjetos    = (isset($_POST['projeto']) && $_POST['projeto'] != '') ? implode(', ', $_POST['projeto']) : '';
        $idLagoas      = (isset($_POST['lagoa'])   && $_POST['lagoa']   != '') ? implode(', ', $_POST['lagoa'])   : '';

        $this->usuario->setId($_SESSION[$_SESSION['SID']]['idUsuario']);
        $this->usuario->pegar();

        $report = new Report(
            $this->usuario->getData('nome'),
            $filtros,
            'Relatório'
        );

        $process = $report->getProcess();
        $process->setOrder('
            data, 
            nome_lagoa, 
            nome_ponto_amostral, 
            nome_categoria, 
            nome_parametro
        ');

        $report->addColumn('data',                  'Data: ',            'L', 1, 40);
        $report->addColumn('nome_projeto',          'Projeto: ',         'L', 1, 40);
        $report->addColumn('nome_lagoa',            'Lagoa: ',           'L', 1, 40);
        $report->addColumn('nome_ponto_amostral',   'Ponto Amostral: ',  'L', 1, 45);
        $report->addColumn('nome_categoria',        'Categoria: ',       'L', 1, 50);
        $report->addColumn('nome_parametro',        'Parâmetro: ',       'L', 1, 60);
        $report->addColumn('valor',                 'Valor: ',           'R', 1, 15);

        switch ($tipoRelatorio) {
            case 'html':
                $report->setHtml();
                $render = $report->getRender();
                break;

            case 'pdf':
                $report->setPdf();
                $render = $report->getRender();
                break;

            case 'xls':
                $report->setXls();
                $render = $report->getRender();
                break;

            default:
                die('Tipo de relatório não informado.');
        }

        $render->setLists(array(
            'projeto'        => $this->projeto->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'lagoa'          => $this->lagoa->listarSelectAssoc($idProjetos, array('campo' => 'nome', 'ordem' => 'ASC')),
            'categorias'     => $this->categoria->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'parametro'      => $this->parametro->listarSelectAssoc(array('campo' => 'nome', 'ordem' => 'ASC')),
            'ponto_amostral' => $this->pontoAmostral->listarSelectAssoc($idLagoas, array('campo' => 'nome', 'ordem' => 'ASC'))
        ));
        $render->render();
    }
}
