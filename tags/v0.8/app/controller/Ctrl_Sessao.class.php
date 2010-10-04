<?php
class Ctrl_Sessao extends BaseController{
    public function __construct() {
        parent::__construct();
    }

    public function login() {
        if( isset( $_POST['login'] ) && isset( $_POST['senha'] ) ) {
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
        $dadosSessao = $usuario->verificar( $login, $senha );

        if( is_array( $dadosSessao ) ) {
            $usuario->registrarLogin();
            $this->registarSessao( $dadosSessao );
            $this->go( D_ROUTE );
        } else {
            $this->getSmarty()->assign('mensagem', 'Login e/ou Senha inv&aacute;lidos!');
        }
    }

    private function registarSessao( $dados ) {
        $_SESSION['SID'] = session_id();
        $_SESSION[$_SESSION['SID']]['idUsuario']   = $dados['id_usuario']; 
        $_SESSION[$_SESSION['SID']]['idPerfil']    = $dados['id_perfil']; 
        $_SESSION[$_SESSION['SID']]['nomeUsuario'] = $dados['nome']; 
        $_SESSION[$_SESSION['SID']]['nomePerfil']  = $dados['nome_perfil']; 
    }

    private function apagarSessao() {
        unset($_SESSION['SID']);
        unset($_SESSION[$_SESSION['SID']]);
    }

    public function sair() {
        $this->apagarSessao();
        $this->go( D_ROUTE );
    }
}
