<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarCategoria extends BaseController implements Gerenciar {
  protected $categoria;

  public function __construct() {
    parent::__construct();

    $this->categoria = new Categoria( $this->getDBH() );
  }

  public function editar( $id = false ) {
    $smarty = $this->getSmarty(); 

    if( $id ) {
      $this->categoria->setId( $id );
      $this->categoria->pegar();

      $smarty->assign( 'categoria', $this->categoria->getData() );
    }

    $smarty->displayHBF( 'editar.tpl' );
  }

  public function salvar() {
    $smarty = $this->getSmarty();

    if( isset( $_POST['nome'] ) && $_POST['nome'] != '' ) {

      try{
        if( isset( $_POST['id_categoria'] ) && $_POST['id_categoria'] != '' ) {
          $this->categoria->setId( $_POST['id_categoria'] );
          $this->categoria->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->categoria->atualizar() )
            $smarty->assign( 'mensagem', 'Categoria alterada.' );
          else
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

        } else {
          $this->categoria->setData( array( 'nome' => $_POST['nome'] ) );
          if( $this->categoria->inserir() )
            $smarty->assign( 'mensagem', 'Categoria salva!' );
          else 
            $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a categoria.' );

        }
        $smarty->displayHBF( 'salvar.tpl' );

      } catch (Exception $e) {
        $smarty->assign( 'mensagem', 'Problema ao salvar categoria.' . $e->getMessage() );
        $smarty->display( 'error.tpl' );
      }

    } else {
      $smarty->assign( 'mensagem', 'O campo Nome n&atilde;o pode ser vazio.' );
      $smarty->displayHBF( 'editar.tpl' );
    }
  }

  public function listar() {
    $smarty = $this->getSmarty(); 

    if( $this->categoria->getDataAll() ) {
      $smarty->assign( 'categorias', $this->categoria->getDataAll() );
    } elseif( $this->categoria->getData() ) {
      $smarty->assign( 'categorias', array ( $this->categoria->getData() ) );
    } else {
      $smarty->assign( 'categorias', $this->categoria->listar() );
    }

    $smarty->displayHBF( 'listar.tpl' );
  }

  public function buscar( $dados = false ) {
    $smarty = $this->getSmarty();

    if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
      if( !$dados ) {
        $dados = $_REQUEST['dados'];
      }

      $num_linhas = $this->categoria->buscar( $dados );

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
        $this->categoria->setId( $id );
        $this->categoria->excluir(); 
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

