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
                        $smarty->assign( 'mensagem', 'Projeto alterada.' );
                    else
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

                } else {
                    $this->projeto->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->projeto->inserir() )
                        $smarty->assign( 'mensagem', 'Projeto salva!' );
                    else 
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a projeto.' );

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                $smarty->assign( 'mensagem', 'Problema ao salvar projeto.' . $e->getMessage() );
                $smarty->display( 'error.tpl' );
            }

        } else {
            $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
            $smarty->displaySubMenuHBF( 'editar.tpl' );
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

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
                $smarty->assign('msg', "N&atilde;o foram encontradas informa&ccedil;&otilde;es com a palavra {$dados}");
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
                $this->projeto->excluir(); 
                $smarty->assign( 'mensagem', 'Registro excluido.' );
            } else {
                $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel excluir o registro' );
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            $smarty->assign( 'mensagem', 'Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->display( 'error.tpl' );
        }
    }
}

