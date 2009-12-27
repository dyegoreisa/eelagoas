<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarParametro extends BaseController implements Gerenciar {
    protected $parametro;
    protected $parametroExtra;
    protected $especie;

    public function __construct() {
        parent::__construct();

        $this->parametro      = new Parametro( $this->getDBH() );
        $this->parametroExtra = new ParametroExtra( $this->getDBH() );
        $this->especie        = new Especie($this->getDBH());
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
                        Mensagem::addOk('Parametro alterado!');
                    } else {
                        Mensagem::addErro('Não foi possível atualizar o parametro');
                    }
                } else {
                    if($this->parametro->inserir()) {
                        Mensagem::addOk('Parametro salvo!' );
                    } else {
                        Mensagem::addErro('Não foi possível salvar a parametro.' );
                    }
                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar parametro.' . $e->getMessage() );
                $smarty->displayError();
            }
        } else {
            Mensagem::addErro('O campo Nome n&atilde;o pode ser vazio.' );
            $this->editar();
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        $acoes = array(
            array(
                'modulo' => 'GerenciarParametro',
                'metodo' => 'editar',
                'alt'    => 'Altera parametro',
                'texto'  => '[ A ]'
            ),
            array(
                'modulo' => 'GerenciarParametro',
                'metodo' => 'excluir',
                'alt'    => 'Exclui parametro',
                'texto'  => '[ E ]',
                'class'  => 'excluir'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesParametro', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

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
                Mensagem::addAtencao('Não foi encontrado nenhum parametro.');
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
                Mensagem::addOk('Registro excluido.' );
            } else {
                Mensagem::addErro('Não foi possível excluir o registro' );
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->display( 'error.tpl' );
        }
    }

    public function montarMultiSelectExtra($nomeExtra, $parametros) {
        $smarty = $this->getSmarty();
        $extra = $this->$nomeExtra;

        $this->parametroExtra->buscar($nomeExtra);

        $smarty->assign('nomeCampo', $nomeExtra);
        $smarty->assign('label', $this->parametroExtra->getData('descricao'));
        $smarty->assign('select_extra', $extra->listarSelectAssoc($parametros));
        $smarty->displayPiece("multiselect_extra.tpl", true );
    }

    public function temParametroExtra($parametros) {
        $this->getSmarty()->displayJson(array($this->parametroExtra->temExtra($parametros)));
    }
}

