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

    if( $id ) {
      $this->lagoa->setId( $id );
      $this->lagoa->pegar();

      $smarty->assign( 'lagoa', $this->lagoa->getData() );
    }

    $smarty->displayHBF( 'editar.tpl' );
  }

  public function salvar() {
    $smarty = $this->getSmarty();

    if( isset( $_POST['nome'] ) && $_POST['nome'] != '' ) {

      try{
        if( isset( $_POST['id_lagoa'] ) && $_POST['id_lagoa'] != '' ) {
          $this->lagoa->setId( $_POST['id_lagoa'] );
          $this->lagoa->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->lagoa->atualizar() )
            $smarty->assign( 'mensagem', 'Lagoa alterada.' );
          else
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

        } else {
          $this->lagoa->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->lagoa->inserir() )
            $smarty->assign( 'mensagem', 'Lagoa salva!' );
          else 
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a lagoa.' );

        }
        $smarty->displayHBF( 'salvar.tpl' );

      } catch (Exception $e) {
        $smarty->assign( 'mensagem', 'Problema ao salvar lagoa.' . $e->getMessage() );
        $smarty->display( 'error.tpl' );
      }

    } else {
      $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
      $smarty->displayHBF( 'editar.tpl' );
    }
  }

  public function listar() {
    $smarty = $this->getSmarty(); 

    if( $this->lagoa->getDataAll() ) {
      $smarty->assign( 'lagoas', $this->lagoa->getDataAll() );
    } elseif( $this->lagoa->getData() ) {
      $smarty->assign( 'lagoas', array ( $this->lagoa->getData() ) );
    } else {
      $smarty->assign( 'lagoas', $this->lagoa->listar() );
    }

    $smarty->displayHBF( 'listar.tpl' );
  }

  public function buscar( $dados = false ) {
    $smarty = $this->getSmarty();

    if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
      if( !$dados ) {
        $dados = $_REQUEST['dados'];
      }

      $num_linhas = $this->lagoa->buscar( $dados );

      if( $num_linhas > 0 ) {
        $this->listar();
      } else {
        $smarty->assign('msg', "N&atilde;o foram encontradas informa&ccedil;&otilde;es com a palavra {$dados}");
        $smarty->displayHBF('buscar.tpl');
      }
    } else {
      $smarty->displayHBF('buscar.tpl');
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

      $smarty->displayHBF( 'salvar.tpl' );
    }catch( Exception $e ) {
      $smarty->assign( 'mensagem', 'Erro ao tentar exluir um registro.' . $e->getMessage() );
      $smarty->display( 'error.tpl' );
    }
  }
}

