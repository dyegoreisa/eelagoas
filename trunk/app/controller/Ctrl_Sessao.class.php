<?php
class Ctrl_Sessao extends BaseController{
  public function __construct() {
    parent::__construct();
  }

  public function login() {
    if( isset( $_POST['login'] ) && isset( $_POST['senha'] )  ) {
      if( $_POST['login'] != '' && $_POST['senha'] != '' ) {
        $this->autenticar( $_POST['login'], $_POST['senha'] );
      } else {
        $this->getSmarty()->assign('mensagem', 'Campo Login e/ou Senha vazios!');
      }
    }
    $this->getSmarty()->displayHBF("login.tpl");
  }

  public function autenticar( $login, $senha ) {
    $this->apagarSessao();

    $usuario = new Usuario( $this->getDBH() );
    $dados_sessao = $usuario->verificar( $login, $senha );

    if( is_array( $dados_sessao ) ) {
      $usuario->registrarLogin();
      $this->registarSessao( $dados_sessao );
      $this->go( D_ROUTE );
    } else {
      $this->getSmarty()->assign('mensagem', 'Login e/ou Senha inv&aacute;lidos!');
    }
  }

  private function registarSessao( $dados ) {
    $_SESSION['SID']        = session_id();
    $_SESSION['id_usuario'] = $dados['id_usuario']; 
  }

  private function apagarSessao() {
    $_SESSION['SID']        = '';
    $_SESSION['id_usuario'] = ''; 
  }

  public function sair() {
    $this->apagarSessao();
    $this->go( D_ROUTE );
  }
}
