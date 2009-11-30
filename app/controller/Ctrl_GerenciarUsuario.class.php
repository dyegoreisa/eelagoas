<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarUsuario extends BaseController implements Gerenciar {
  protected $usuario;

  public function __construct() {
    parent::__construct();

    $this->usuario = new Usuario( $this->getDBH() );
  }

  public function editar( $id = false ) {
    $smarty = $this->getSmarty(); 

    if( $id ) {
      $this->usuario->setId( $id );
      $this->usuario->pegar();

      $smarty->assign( 'usuario', $this->usuario->getData() );
    }

    $smarty->displaySubMenuHBF( 'editar.tpl' );
  }

  public function salvar() {
    $smarty = $this->getSmarty();

    if( 
      isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
      isset( $_POST['login'] ) && $_POST['login'] != '' &&
      isset( $_POST['confirma_senha'] ) && $_POST['confirma_senha'] != '' &&
      isset( $_POST['senha'] ) && $_POST['senha'] != ''
    ) {

      if( $_POST['senha'] == $_POST['confirma_senha'] ) {

        try{
          $this->usuario->setData( 
            array( 
              'login' => $_POST['login'],
              'nome'  => $_POST['nome'],
              'senha' => md5($_POST['senha']),
              'email' => $_POST['email']
            ) 
          );


          if( isset( $_POST['id_usuario'] ) && $_POST['id_usuario'] != '' ) {

            $this->usuario->setId( $_POST['id_usuario'] );

            if( $this->usuario->atualizar() )
              $smarty->assign( 'mensagem', 'Usuario alterada.' );
            else
              $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar o registro.' );

          } else {

            if( $this->usuario->inserir() )
              $smarty->assign( 'mensagem', 'Usuario salvo!' );
            else 
              $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel salvar a usuario.' );

          }
          $smarty->displaySubMenuHBF( 'salvar.tpl' );

        } catch (Exception $e) {
          $smarty->assign( 'mensagem', 'Problema ao salvar usuario.' . $e->getMessage() );
          $smarty->display( 'error.tpl' );
        }

      } else {
        $smarty->assign( 'mensagem', 'A senha deve ser igual a confirma senha.' );
        $smarty->displaySubMenuHBF( 'editar.tpl' );
      }

    } else {
      $smarty->assign( 'mensagem', 'O campo Nome, login, senha e/ou confirma senha n&atilde;o podem ser vazios.' );
      $smarty->displaySubMenuHBF( 'editar.tpl' );
    }
  }

  public function listar() {
    $smarty = $this->getSmarty(); 

    if( $this->usuario->getDataAll() ) {
      $smarty->assign( 'usuarios', $this->usuario->getDataAll() );
    } elseif( $this->usuario->getData() ) {
      $smarty->assign( 'usuarios', array ( $this->usuario->getData() ) );
    } else {
      $smarty->assign( 'usuarios', $this->usuario->listar(array('campo' => 'nome', 'ordem' => 'ASC')) );
    }

    $smarty->displaySubMenuHBF( 'listar.tpl' );
  }

  public function buscar( $dados = false ) {
    $smarty = $this->getSmarty();

    if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
      if( !$dados ) {
        $dados = $_REQUEST['dados'];
      }

      $num_linhas = $this->usuario->buscar( $dados );

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
        $this->usuario->setId( $id );
        $this->usuario->excluir(); 
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

