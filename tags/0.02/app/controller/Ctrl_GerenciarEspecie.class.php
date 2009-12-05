<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarEspecie extends BaseController implements Gerenciar {
    protected $especie;
    protected $parametro;

    public function __construct() {
        parent::__construct();

        $this->especie   = new Especie( $this->getDBH() );
        $this->parametro = new Parametro($this->getDBH());
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        $smarty->assign('select_parametros', $this->parametro->listarSelectAssocEspecie());

        if( $id ) {
            $this->especie->setId( $id );
            $this->especie->pegar();

            $smarty->assign( 'especie', $this->especie->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
            isset( $_POST['id_parametro'] ) && $_POST['id_parametro'] != '-1') {

            try{
                $this->especie->setData( array( 
                    'nome'         => $_POST['nome'],
                    'id_parametro' => $_POST['id_parametro']
                ) );

                if( isset( $_POST['id_especie'] ) && $_POST['id_especie'] != '' ) {
                    $this->especie->setId( $_POST['id_especie'] );
                    if( $this->especie->atualizar() )
                        $smarty->assign( 'mensagem', 'Especie alterada.' );
                    else
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

                } else {
                    if( $this->especie->inserir() )
                        $smarty->assign( 'mensagem', 'Especie salva!' );
                    else 
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a especie.' );

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                $smarty->assign( 'mensagem', 'Problema ao salvar especie.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            $smarty->assign( 'mensagem', 'O campo Nome ou Parametro n&atilde;o podem ser vazios.' );
            $this->editar();
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        if( $this->especie->getDataAll() ) {
            $smarty->assign( 'especies', $this->especie->getDataAll() );
        } elseif( $this->especie->getData() ) {
            $smarty->assign( 'especies', array ( $this->especie->getData() ) );
        } else {
            $smarty->assign( 'especies', $this->especie->listar(array('campo' => 'nome_parametro, nome', 'ordem' => '')) );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
    }

    public function buscar( $dados = false ) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->especie->buscar( $dados );

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
                $this->especie->setId( $id );
                $this->especie->excluir(); 
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

