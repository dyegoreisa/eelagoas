<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarPerfil extends BaseController implements Gerenciar {
    protected $perfil;

    public function __construct() {
        parent::__construct();

        $this->perfil = new Perfil( $this->getDBH() );
    }

    public function editar( $id = false )
    {
        $smarty = $this->getSmarty();

        if ($id) {
            $this->perfil->setId($id);
            $this->perfil->pegar();

            $smarty->assign('perfil', $this->perfil->getData());
        }

        // Plugin
        $acesso = new Acesso_Acesso();
        $classes = $acesso->carregarClasses(DIR_CONTROLLER, $this->perfil->getId());

        ksort($classes);

        $smarty->assign('classes', $classes);
        $smarty->assign('simNao', array('S' => 'Sim', 'N' => 'N&atilde;o'));

        $smarty->displaySubMenuHBF('editar.tpl');
    }
    
    public function salvar()
    {
        $smarty = $this->getSmarty();

        $acesso = new Acesso_Acesso();

        if (isset($_POST['nome']) && $_POST['nome'] != '') {
            $permissoesBrutas = $_POST;
            unset($permissoesBrutas['nome']);

            try {
                $this->perfil->setData(array('nome' => $_POST['nome']));

                if (isset($_POST['id_perfil']) && $_POST['id_perfil'] != '') {

                    $this->perfil->setId($_POST['id_perfil']);

                    if($this->perfil->atualizar()) {
                        $acesso->salvarPermissoesDoPerfil($this->perfil->getId(), $this->perfil->getData('nome'), $permissoesBrutas);
                        Mensagem::addOk('Perfil alterado.');
                    } else {
                        Mensagem::addErro('Não foi possível alterar o perfil.');
                    }

                } else {
                    if($this->perfil->inserir()) {
                        $acesso->salvarPermissoesDoPerfil($this->perfil->getId(), $this->perfil->getData('nome'), $permissoesBrutas);
                        Mensagem::addOk('Perfil salvo!');
                    } else {
                        Mensagem::addErro('Não foi possível salvar a perfil.');
                    }
                }
                $smarty->displaySubMenuHBF('salvar.tpl');

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar perfil.' . $e->getMessage());
                $smarty->displayError();
            }
        } else {
          Mensagem::addErro('O campo Nome não pode ser vazio.');
          $smarty->displaySubMenuHBF('editar.tpl');
        }
    }
    
    public function listar()
    {
        $smarty = $this->getSmarty(); 
        if($this->perfil->getDataAll()) {
            $smarty->assign('perfis', $this->perfil->getDataAll());
        } elseif($this->perfil->getData()) {
            $smarty->assign('perfis', array ($this->perfil->getData()));
        } else {
            $smarty->assign('perfis', $this->perfil->listar(array('campo' => 'nome', 'ordem' => 'ASC')));
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
    }
    
    public function buscar($dados = false)
    {
        $smarty = $this->getSmarty();

        if($dados || isset($_REQUEST['dados']) && $_REQUEST['dados'] != '') {
            if(!$dados) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->perfil->buscar($dados);

            if ($num_linhas > 0) {
                $this->listar();
            } else {
                Mensagem::addAtencao('Não foi encontrado nenhum perfil.');
                $smarty->displaySubMenuHBF('buscar.tpl');
            }
        } else {
            $smarty->displaySubMenuHBF('buscar.tpl');
        }
    }
    
    public function excluir( $id )
    {
        $smarty = $this->getSmarty();
        
        try{
            if(isset($id) && $id != '') {
                $this->perfil->setId($id);
                $this->perfil->excluir(); 
                Mensagem::addOk('Registro excluido.');
            } else {
                Mensagem::addErro('Não foi possível excluir o registro');
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErr('Erro ao tentar exluir um registro.' . $e->getMessage());
            $smarty->display( 'error.tpl' );
        }
    }

}

