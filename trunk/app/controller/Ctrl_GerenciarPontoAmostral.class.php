<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarPontoAmostral extends BaseController implements Gerenciar {
    protected $pontoAmostral;
    protected $lagoa;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();

        $this->pontoAmostral    = new PontoAmotral( $dbh );
        $this->lagoa                    = new Lagoa( $dbh );
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
                        $smarty->assign( 'mensagem', 'Ponto Amostral alterada.' );
                    else
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

                } else {
                    $this->pontoAmostral->setData( array( 'nome' => $_POST['nome'] ) );
                    if( $this->pontoAmostral->inserir() )
                        $smarty->assign( 'mensagem', 'Ponto Amostral salva!' );
                    else 
                        $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a pontoAmostral.' );

                }
                $smarty->displayHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                $smarty->assign( 'mensagem', 'Problema ao salvar pontoAmostral.' . $e->getMessage() );
                $smarty->display( 'error.tpl' );
            }

        } else {
            $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
            $smarty->displayHBF( 'editar.tpl' );
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

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

    public function buscar( $dados = false ){
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            if( is_numeric( $dados ) ) {
                $this->lagoa->setId( $dados );
                $this->lagoa->pegar();
            }

            $num_linhas = $this->pontoAmostral->buscar( $dados, array('id_ponto_amostral', 'nome') );

            if( $num_linhas > 0 ) {
                $this->listar();
            }
            else {
                $smarty->assign('msg', "N&atilde;o foram encontradas informa&ccedil;&otilde;es com a palavra {$dados}");
                $smarty->displayHBF('buscar.tpl');
            }
        } else {
            $smarty->displayHBF('buscar.tpl');
        }
    }

    public function excluir( $id ){
    }

    public function montarSelect( $id_lagoa ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc( $id_lagoa ) );
        $smarty->displayPiece( 'select_ponto_amostral.tpl' );
    }

    public function montarMultiSelect($lagoas)
    {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc( $lagoas ) );
        $smarty->displayPiece( 'multiselect_ponto_amostral.tpl' );
    }
}
