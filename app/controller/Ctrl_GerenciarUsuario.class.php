<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarUsuario extends BaseController implements Gerenciar {
    protected $usuario;
    protected $perfil;

    protected $subMenu;

    public function __construct() {
        parent::__construct();

        $this->usuario = new Usuario($this->getDBH());
        $this->perfil  = new Perfil($this->getDBH());

        $this->subMenu = array(
            array(
                'modulo' => 'GerenciarUsuario',
                'metodo' => 'alterarSenha/' . $_SESSION[$_SESSION['SID']]['idUsuario'],
                'texto'  => 'Alterar Senha',
                'icone'  => 'senha.png'
            )
        );
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 
        $smarty->addSubMenuItem($this->subMenu);

        if( $id ) {
            $this->usuario->setId( $id );
            $this->usuario->pegar();

            $smarty->assign('usuario', $this->usuario->getData() );
        }

        $smarty->assign('select_perfis', $this->perfil->listarSelectAssoc());
        $smarty->displaySubMenuHBF('editar.tpl');
    }

    public function salvar() {
        $smarty = $this->getSmarty();
        $smarty->addSubMenuItem($this->subMenu);

        if (
            isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
            isset( $_POST['id_perfil'] ) && $_POST['id_perfil'] != '-1'
        ) {
            try {
                if(isset($_POST['id_usuario']) && $_POST['id_usuario'] != '') {
                    $this->usuario->setId( $_POST['id_usuario'] );
                    $this->usuario->setData(array(
                        'id_perfil' => $_POST['id_perfil'],
                        'nome'      => $_POST['nome'],
                        'email'     => $_POST['email']
                    ));

                    if($this->usuario->atualizar()) {
                        Mensagem::addOk(latinToUTF('Usuário alterado.'));
                    } else {
                        Mensagem::addErro(latinToUTF('Não foi possível salvar o registro.'));
                    }
                } else {
                    if(
                        isset($_POST['login']) && $_POST['login'] != '' &&
                        isset($_POST['senha']) && $_POST['senha'] != '' &&
                        isset($_POST['confirma_senha']) && $_POST['confirma_senha'] != '' &&
                        $_POST['senha'] == $_POST['confirma_senha']
                    ) {
                        $this->usuario->setData(array( 
                            'id_perfil' => $_POST['id_perfil'],
                            'login'     => $_POST['login'],
                            'nome'      => $_POST['nome'],
                            'senha'     => md5($_POST['senha']),
                            'email'     => $_POST['email']
                        ));

                        if($this->usuario->inserir()) {
                            Mensagem::addOk('Usuário salvo.');
                        } else {
                            Mensagem::addErro(latinToUTF('Não foi possível salvar a usuário.'));
                        }
                    } else {
                        Mensagem::addErro('A senha deve ser igual a confirma senha.');
                        $smarty->displaySubMenuHBF('editar.tpl');
                    }
                }
                $smarty->assign('titulo', 'Cadastro de Usu&aacute;rio');
                $smarty->displaySubMenuHBF('salvar.tpl');
            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar usuário.' . $e->getMessage());
                $smarty->displayError();
            }
        } else {
            Mensagem::addErro('Tem algum campo vazio, favor preencher todos.');
            if (isset($_POST['id_usuario']) && $_POST['id_usuario'] != '') {
                $this->editar($_POST['id_usuario']);
            } else {
                $this->editar();
            }
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 
        $smarty->addSubMenuItem($this->subMenu);

        $acoes = array(
            array(
                'modulo' => 'GerenciarUsuario',
                'metodo' => 'editar',
                'alt'    => 'Altera usuario',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarUsuario',
                'metodo' => 'excluir',
                'alt'    => 'Exclui usuario',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($acoes));

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
        $smarty->addSubMenuItem($this->subMenu);

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->usuario->buscar( $dados );

            if( $num_linhas > 0 ) {
                $this->listar();
            } else {
                Mensagem::addAtencao(latinToUTF('Nenhum usuário encontrado.'));
                $smarty->displaySubMenuHBF('buscar.tpl');
            }
        } else {
            $smarty->displaySubMenuHBF('buscar.tpl');
        }
    }

    public function excluir( $id ) {
        $smarty = $this->getSmarty();
        $smarty->addSubMenuItem($this->subMenu);
        
        try{
            if( isset( $id ) && $id != '' ) {
                $this->usuario->setId( $id );
                if ($this->usuario->excluir()) { 
                    Mensagem::addOk('Registro excluido.');
                } else {
                    Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
                }
            } else {
                Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
            }

            $smarty->assign('titulo', 'Cadastro de Usuário');
            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->displayError();
        }
    }

    public function alterarSenha($id)
    {
        $smarty = $this->getSmarty(); 
        $smarty->addSubMenuItem($this->subMenu);

        $this->usuario->setId($id);
        $this->usuario->pegar();    

        $smarty->assign('nome_usuario', 'Alterar senha de ' . $this->usuario->getData('nome'));
        $smarty->assign( 'usuario', $this->usuario->getData() );
        $smarty->displaySubMenuHBF('alterarSenha.tpl');
    }

    public function salvarSenha()
    {
        Mensagem::begin();
        Mensagem::setSeparador('<br>');

        $smarty = $this->getSmarty(); 
        $smarty->addSubMenuItem($this->subMenu);

        if (
            isset($_POST['id_usuario']) && $_POST['id_usuario'] != '' &&
            isset($_POST['senha_atual'])  && $_POST['senha_atual'] != '' &&
            isset($_POST['nova_senha'])  && $_POST['nova_senha'] != '' &&
            isset($_POST['confirma_senha'])  && $_POST['confirma_senha'] != '' &&
            $_POST['nova_senha'] == $_POST['confirma_senha']
        ) {
            if ($this->usuario->verificarIdSenha($_POST['id_usuario'], $_POST['senha_atual'])) {
                $this->usuario->setId($_POST['id_usuario']);
                $this->usuario->setData(array('senha' => md5($_POST['nova_senha'])));
                if ($this->usuario->atualizar()) {
                    Mensagem::addOk('Senha alterada.');
                    $smarty->assign('titulo', 'Alterar senha');
                    $smarty->displaySubMenuHBF('salvar.tpl');
                } else {
                    Mensagem::addErro('Ao alterar a senha, favor tente mais tarde.');
                    $this->displayError();
                }
            } else {
                Mensagem::addErro(latinToUTF('A senha atual informada está incorreta, favor tente novamente.'));
                $this->alterarSenha($_POST['id_usuario']);
            }
        } else {
            Mensagem::addErro(latinToUTF('Não foi possível alterar a senha'));
            if (isset($_POST['id_usuario']) && $_POST['id_usuario'] != '') {
                $this->alterarSenha($_POST['id_usuario']);
            } else {
                Mensagem::addErro(latinToUTF('Usuário não identificado.'));
                $this->displayError();
            }
        }
    }
}

