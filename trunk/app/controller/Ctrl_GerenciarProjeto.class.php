<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarProjeto extends BaseController implements Gerenciar 
{
    protected $projeto;

    public function __construct() {
        parent::__construct();

        $this->projeto = new Projeto( $this->getDBH() );
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        if( $id ) {
            $this->projeto->setId( $id );
            $this->projeto->pegar();

            $smarty->assign( 'projeto', $this->projeto->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' ) {

            try{
                if( isset( $_POST['id_projeto'] ) && $_POST['id_projeto'] != '' ) {
                    $this->projeto->setId( $_POST['id_projeto'] );
                    $this->projeto->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->projeto->atualizar() )
                        Mensagem::addOk('Projeto alterada.' );
                    else
                        Mensagem::addErro('Não foi possível salvar o registro.' );

                } else {
                    $this->projeto->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->projeto->inserir() )
                        Mensagem::addOk('Projeto salva!' );
                    else 
                        Mensagem::addErro('Não foi possível salvar a projeto.' );

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar projeto.' . $e->getMessage() );
                $smarty->display( 'error.tpl' );
            }

        } else {
            Mensagem::addErro('O campo Nome não pode ser vazio.' );
            $smarty->displaySubMenuHBF( 'editar.tpl' );
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        $acoes = array(
            array(
                'modulo' => 'GerenciarLagoa',
                'metodo' => 'listar',
                'alt'    => 'Listar lagoas',
                'texto'  => '[ L ]'
            ),
            array(
                'modulo' => 'GerenciarProjeto',
                'metodo' => 'editar',
                'alt'    => 'Altera projeto',
                'texto'  => '[ A ]'
            ),
            array(
                'modulo' => 'GerenciarProjeto',
                'metodo' => 'excluir',
                'alt'    => 'Exclui projeto',
                'texto'  => '[ E ]',
                'class'  => 'excluir'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesProjeto', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

        if( $this->projeto->getDataAll() ) {
            $smarty->assign( 'projetos', $this->projeto->getDataAll() );
        } elseif( $this->projeto->getData() ) {
            $smarty->assign( 'projetos', array ( $this->projeto->getData() ) );
        } else {
            $smarty->assign( 'projetos', $this->projeto->listar() );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
    }

    public function buscar( $dados = false ) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->projeto->buscar( $dados );

            if( $num_linhas > 0 ) {
                $this->listar();
            } else {
                Mensagem::addAtencao('Não foi encontrador nenhum projeto.');
                $smarty->displaySubMenuHBF('buscar.tpl');
            }
        } else {
            $smarty->displaySubMenuHBF('buscar.tpl');
        }
    }

    public function excluir( $id ) {
        $smarty = $this->getSmarty();
        
        try{
            if( isset( $id ) && $id != '' ) {
                $this->projeto->setId( $id );
                if ($this->projeto->excluir()) {
                    Mensagem::addOk('Registro excluido.' );
                } else {
                    Mensagem::addErro('Não foi possível excluir o registro.');
                }
            } else {
                Mensagem::addErro('Não foi possível excluir o registro.');
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->displayError();
        }
    }
}

