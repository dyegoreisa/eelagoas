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
    
    public function selecionar()
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


    public function gerar()
    {   
        $filtros       = $_POST;
        $tipoRelatorio = $_POST['tipo_relatorio'];
        $idProjetos    = (isset($_POST['projeto'])    && $_POST['projeto']    != '') ? implode(', ', $_POST['projeto']) : '';
        $idLagoas      = (isset($_POST['lagoa'])      && $_POST['lagoa']      != '') ? implode(', ', $_POST['lagoa'])   : '';
        $orientacao    = (isset($_POST['orientacao']) && $_POST['orientacao'] != '') ? $_POST['orientacao']             : 'L';
        $formato       = (isset($_POST['formato'])    && $_POST['formato']    != '') ? $_POST['formato']                : 'A4';

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

        $report->addColumn('data',                'Data ',             'L', 1); 
        $report->addColumn('nome_projeto',        'Projeto ',          'L', 1); 
        $report->addColumn('nome_lagoa',          'Lagoa ',            'L', 1); 
        $report->addColumn('nome_ponto_amostral', 'Ponto Amostral ',   'L', 1); 
        $report->addColumn('nome_categoria',      'Categoria ',        'L', 1); 
        $report->addColumn('profundidade',        'Profundidade ',     'L', 1); 
        $report->addColumn('parametro',           'Par&acirc;metros ', 'C', 1); 

        switch ($tipoRelatorio) {
            case 'html':
                $report->setHtml();
                $render = $report->getRender();
                break;

            case 'pdf':
                $report->changeColumn('data',                'Width', 9);
                $report->changeColumn('nome_projeto',        'Width', 15);
                $report->changeColumn('nome_lagoa',          'Width', 9);
                $report->changeColumn('nome_ponto_amostral', 'Width', 10);
                $report->changeColumn('nome_categoria',      'Width', 10);
                $report->changeColumn('profundidade',        'Width', 9);
                $report->changeColumn('parametro',           'Text', mb_convert_encoding('Parâmetros ', 'latin1', 'UTF-8'));
                $report->setPdf($orientacao, $formato);
                
                $render = $report->getRender();
                break;

            case 'xls':
                $report->changeColumn('parametro', 'Text', 'Parâmetro');
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
