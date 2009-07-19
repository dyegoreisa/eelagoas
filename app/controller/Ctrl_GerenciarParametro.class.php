<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarParametro extends BaseController implements Gerenciar {
  protected $parametro;

  public function __construct() {
    parent::__construct();

    $this->parametro = new Parametro( $this->getDBH() );
  }

  public function editar( $id = false ) {
    $smarty = $this->getSmarty(); 

    if( $id ) {
      $this->parametro->setId( $id );
      $this->parametro->pegar();

      $smarty->assign( 'parametro', $this->parametro->getData() );
    }

    $smarty->displayHBF( 'editar.tpl' );
  }

  public function salvar() {
    $smarty = $this->getSmarty();

    if( isset( $_POST['nome'] ) && $_POST['nome'] != '' ) {

      try{
        if( isset( $_POST['id_parametro'] ) && $_POST['id_parametro'] != '' ) {
          $this->parametro->setId( $_POST['id_parametro'] );
          $this->parametro->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->parametro->atualizar() )
            $smarty->assign( 'mensagem', 'Parametro alterada.' );
          else
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

        } else {
          $this->parametro->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->parametro->inserir() )
            $smarty->assign( 'mensagem', 'Parametro salva!' );
          else 
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a parametro.' );

        }
        $smarty->displayHBF( 'salvar.tpl' );

      } catch (Exception $e) {
        $smarty->assign( 'mensagem', 'Problema ao salvar parametro.' . $e->getMessage() );
        $smarty->display( 'error.tpl' );
      }

    } else {
      $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
      $smarty->displayHBF( 'editar.tpl' );
    }
  }

  public function listar() {
    $smarty = $this->getSmarty(); 

    if( $this->parametro->getDataAll() ) {
      $smarty->assign( 'parametros', $this->parametro->getDataAll() );
    } elseif( $this->parametro->getData() ) {
      $smarty->assign( 'parametros', array ( $this->parametro->getData() ) );
    } else {
      $smarty->assign( 'parametros', $this->parametro->listar() );
    }

    $smarty->displayHBF( 'listar.tpl' );
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
        $this->parametro->setId( $id );
        $this->parametro->excluir(); 
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

