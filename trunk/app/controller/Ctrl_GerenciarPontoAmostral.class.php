<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarPontoAmostral extends BaseController implements Gerenciar {
    protected $pontoAmostral;
    protected $lagoa;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->pontoAmostral = new PontoAmotral( $dbh );
        $this->lagoa         = new Lagoa( $dbh );
    }
     
    public function editar( $id = false ){
        $smarty = $this->getSmarty(); 

        if( $id ) {
            $this->pontoAmostral->setId( $id );
            $this->pontoAmostral->pegar();

            $smarty->assign( 'pontoAmostral', $this->pontoAmostral->getData() );
        }

        $smarty->displayHBF( 'editar.tpl' );
    }

    public function salvar(){
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' ) {

            try{
                if( isset( $_POST['id_ponto_amostral'] ) && $_POST['id_ponto_amostral'] != '' ) {
                    $this->pontoAmostral->setId( $_POST['id_ponto_amostral'] );
                    $this->pontoAmostral->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->pontoAmostral->atualizar() )
                        Mensagem::addOk('Ponto Amostral alterada.' );
                    else
                        Mensagem::addErro('Não foi possível salvar o registro.' );

                } else {
                    $this->pontoAmostral->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->pontoAmostral->inserir() )
                        Mensagem::addOk('Ponto Amostral salva!' );
                    else 
                        Mensagem::addErro('Não foi possível salvar a pontoAmostral.' );

                }
                $smarty->displayHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar pontoAmostral.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            Mensagem::addErro('O campo Nome não pode ser vazio.' );
            $smarty->displayHBF( 'editar.tpl' );
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        $acoes = array(
            array(
                'modulo' => 'GerenciarPontoAmostral',
                'metodo' => 'editar',
                'alt'    => 'Altera ponto amostral',
                'texto'  => '[ A ]'
            ),
            array(
                'modulo' => 'GerenciarPontoAmostral',
                'metodo' => 'excluir',
                'alt'    => 'Exclui ponto amostral',
                'texto'  => '[ E ]',
                'class'  => 'excluir'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesPontoAmostral', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

        $smarty->assign( 'lagoa', $this->lagoa->getData() );

        if( $this->pontoAmostral->getDataAll() ) {
            $smarty->assign( 'pontosAmostrais', $this->pontoAmostral->getDataAll() );
        } elseif( $this->pontoAmostral->getData() ) {
            $smarty->assign( 'pontosAmostrais', array ( $this->pontoAmostral->getData() ) );
        } else {
            $smarty->assign( 'pontosAmostrais', $this->pontoAmostral->listar() );
        }

        $smarty->displayHBF( 'listar.tpl' );
    }

    public function buscar($campo = 'id_ponto_amostral', $dados = false){
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            if( is_numeric( $dados ) ) {
                $this->lagoa->setId( $dados );
                $this->lagoa->pegar();
            }

            if ($campo == 'id_ponto_amostral') {
                $campos = array($campo, 'nome');
            } else {
                $campos = array($campo);
            }

            $num_linhas = $this->pontoAmostral->buscar( $dados, $campos );

            if( $num_linhas > 0 ) {
                $this->listar();
            }
            else {
                Mensagem::addAtencao('Não foi encontrador nenhum ponto amostral.');
                $smarty->displayHBF('buscar.tpl');
            }
        } else {
            $smarty->displayHBF('buscar.tpl');
        }
    }

    public function excluir( $id ){
        $smarty = $this->getSmarty();
        
        try{
            if( isset( $id ) && $id != '' ) {
                $this->pontoAmostral->setId( $id );
                if ($this->pontoAmostral->excluir()) {
                    Mensagem::addOk('Registro excluido.' );
                } else {
                    Mensagem::addErro('Não foi possível excluir o registro' );
                }
            } else {
                Mensagem::addErro('Não foi possível excluir o registro' );
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->displayError();
        }
    }

    public function montarSelect( $id_lagoa ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc( $id_lagoa ) );
        $smarty->displayPiece( 'select_ponto_amostral.tpl', true );
    }

    public function montarMultiSelect($lagoas)
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc( $lagoas ) );
        $smarty->displayPiece( 'multiselect_ponto_amostral.tpl', true );
    }
}
