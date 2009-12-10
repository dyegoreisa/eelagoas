<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarLagoa extends BaseController implements Gerenciar {
    protected $lagoa;

    public function __construct() {
        parent::__construct();

        $this->lagoa = new Lagoa( $this->getDBH() );
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        $projeto = new Projeto($this->getDBH());
        $smarty->assign('select_projetos', $projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));

        if( $id ) {
            $this->lagoa->setId( $id );
            $this->lagoa->pegar();

            $smarty->assign( 'lagoa', $this->lagoa->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
            isset( $_POST['id_projeto'] ) && $_POST['id_projeto'] != '-1') {

            $this->lagoa->setData( array( 
                'nome'       => $_POST['nome'],
                'id_projeto' => $_POST['id_projeto']
            ));

            try{
                if( isset( $_POST['id_lagoa'] ) && $_POST['id_lagoa'] != '' ) {
                    $this->lagoa->setId( $_POST['id_lagoa'] );
                    if( $this->lagoa->atualizar() )
                        $smarty->assign( 'mensagem', 'Lagoa alterada.' );
                    else
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

                } else {
                    if( $this->lagoa->inserir() )
                        $smarty->assign( 'mensagem', 'Lagoa salva!' );
                    else 
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a lagoa.' );

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                $smarty->assign( 'mensagem', 'Problema ao salvar lagoa.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            $smarty->assign( 'mensagem', 'O campo Nome ou Projeto n&atilde;o podem ser vazios.' );
            $smarty->displaySubMenuHBF( 'editar.tpl' );
        }
    }

    public function listar($idProjeto = -1) {
        $smarty = $this->getSmarty(); 
        $template = 'listar.tpl';

        if ($idProjeto != -1) {
            $smarty->assign( 'lagoas', $this->lagoa->listarPorProjeto($idProjeto, array(
                'campo' => 'l.nome',
                'ordem' => 'ASC'
            )));
            $template = 'listarPorProjeto.tpl';
        } elseif( $this->lagoa->getDataAll() ) {
            $smarty->assign( 'lagoas', $this->lagoa->getDataAll() );
        } elseif( $this->lagoa->getData() ) {
            $smarty->assign( 'lagoas', array ( $this->lagoa->getData() ) );
        } else {
            $smarty->assign( 'lagoas', $this->lagoa->listar(array(
                'campo' => 'l.nome',
                'ordem' => 'ASC'
            )));
        }

        $smarty->displaySubMenuHBF($template);
    }

    public function buscar( $dados = false ) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->lagoa->buscar( $dados, '', array(
                'campo' => 'nome',
                'ordem' => 'ASC'
            ));

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
                $this->lagoa->setId( $id );
                $this->lagoa->excluir(); 
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

    public function montarSelect( $idProjeto ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc( $idProjeto ) );
        $smarty->displayPiece( 'select_lagoa.tpl', true );
    }

    public function montarMultiSelect( $idProjeto ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc( $idProjeto ) );
        $smarty->displayPiece( 'multiselect_lagoa.tpl', true );
    }

    public function montarMultiSelectData($campo, $tipoPeriodo, $idLagoas) {
        $smarty = $this->getSmarty();

        $smarty->assign('campo', $campo);
        $smarty->assign('options', $this->lagoa->listarSelectAssocData($campo, $tipoPeriodo, $idLagoas));
        $smarty->displayPiece( 'multiselect_data.tpl', true );
    }
}

