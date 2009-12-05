<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarParametro extends BaseController implements Gerenciar {
    protected $parametro;
    protected $parametroExtra;

    public function __construct() {
        parent::__construct();

        $this->parametro      = new Parametro( $this->getDBH() );
        $this->parametroExtra = new ParametroExtra( $this->getDBH() );
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        if( $id ) {
            $this->parametro->setId( $id );
            $this->parametro->pegar();

            $smarty->assign( 'parametro', $this->parametro->getData() );
        }

        $smarty->assign('select_extra', $this->parametroExtra->listarSelectAssoc());

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if(isset($_POST['nome'])               && $_POST['nome']     != '' &&
           isset($_POST['id_parametro_extra']) && $_POST['id_parametro_extra'] != ''
        ) {

            try{
                $this->parametro->setData( array(
                    'nome'               => $_POST['nome'],
                    'id_parametro_extra' => $_POST['id_parametro_extra']
                ));

                if( isset( $_POST['id_parametro'] ) && $_POST['id_parametro'] != '' ) {
                    $this->parametro->setId( $_POST['id_parametro'] );

                    if($this->parametro->atualizar()) {
                        $smarty->assign( 'mensagem', 'Parametro alterado!' );
                    } else {
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel atualizar o registro.' );
                    }
                } else {
                    if($this->parametro->inserir()) {
                        $smarty->assign( 'mensagem', 'Parametro salvo!' );
                    } else {
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a parametro.' );
                    }
                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                $smarty->assign( 'mensagem', 'Problema ao salvar parametro.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
            $this->editar();
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        if( $this->parametro->getDataAll() ) {
            $smarty->assign( 'parametros', $this->parametro->getDataAll() );
        } elseif( $this->parametro->getData() ) {
            $smarty->assign( 'parametros', array ( $this->parametro->getData() ) );
        } else {
            $smarty->assign( 'parametros', $this->parametro->listar(array('campo' => 'nome', 'ordem' => 'ASC')) );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
    }

    public function buscar( $dados = false ) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->parametro->buscar( $dados );

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
                $this->parametro->setId( $id );
                $this->parametro->excluir(); 
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

