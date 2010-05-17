<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarCategoria extends BaseController implements Gerenciar {
    protected $categoria;

    public function __construct() {
        parent::__construct();

        $this->categoria      = new Categoria( $this->getDBH() );
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        if( $id ) {
            $this->categoria->setId( $id );
            $this->categoria->pegar();

            $smarty->assign('e_perfil', ($this->categoria->getData('e_perfil') == 1) ? 'checked' : '');
            $smarty->assign('categoria', $this->categoria->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if(isset($_POST['nome']) && $_POST['nome'] != '') {

            try{
                $this->categoria->setData(array(
                    'nome'     => $_POST['nome'],
                    'e_perfil' => (isset($_POST['e_perfil']) && $_POST['e_perfil'] == 1) ? 1 : 0
                ));
                if( isset( $_POST['id_categoria'] ) && $_POST['id_categoria'] != '' ) {
                    $this->categoria->setId($_POST['id_categoria']);
                    if($this->categoria->atualizar()) {
                        Mensagem::addOk('Categoria alterada.');
                    } else {
                        Mensagem::addErro(latinToUTF('Não foi possível salvar o registro.'));
                    }
                } else {
                    if($this->categoria->inserir()) {
                        Mensagem::addOk('Categoria salva!');
                    } else {
                        Mensagem::addErro(latinToUTF('Não foi possível salvar a categoria.'));
                    }
                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );
            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar categoria.' . $e->getMessage() );
                $smarty->displayError();
            }
        } else {
            Mensagem::addErro(latinToUTF('O campo Nome não pode ser vazio.'));
            $this->editar();
        }
    }

    public function listar() {
        $smarty = $this->getSmarty(); 

        $acoes = array(
            array(
                'modulo' => 'GerenciarCategoria',
                'metodo' => 'editar',
                'alt'    => 'Altera categoria',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarCategoria',
                'metodo' => 'excluir',
                'alt'    => 'Exclui categoria',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

        if( $this->categoria->getDataAll() ) {
            $smarty->assign( 'categorias', $this->categoria->getDataAll() );
        } elseif( $this->categoria->getData() ) {
            $smarty->assign( 'categorias', array ( $this->categoria->getData() ) );
        } else {
            $smarty->assign( 'categorias', $this->categoria->listar(array('campo' => 'nome', 'ordem' => 'ASC')) );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
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
                Mensagem::addAtencao('Não foi encontrado nenhuma categoria.');
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
                $this->categoria->setId( $id );
                if ($this->categoria->excluir()) { 
                    Mensagem::addOk('Registro excluido.' );
                } else {
                    Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
                }
            } else {
                Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->displayError();
        }
    }

    public function temProfundidade($categorias) {
        $partes = explode(',', $categorias);
        $count = 0;
        foreach ($partes as $idCategoria) {
            $this->categoria->setId($idCategoria);
            if ($this->categoria->temProfundidade()) {
                $count++;
            }
        }

        if ($count > 0) {
            $this->getSmarty()->displayJson(array(true));
        } else {
            $this->getSmarty()->displayJson(array(false));
        }
    }

    public function montarMultiSelectProfundidade($categorias) {
        $smarty = $this->getSmarty();

        $smarty->assign('select_profundidade', $this->categoria->listaProfundidades($categorias)); 

        $smarty->displayPiece('multiselect_profundidade.tpl');
    }
}

