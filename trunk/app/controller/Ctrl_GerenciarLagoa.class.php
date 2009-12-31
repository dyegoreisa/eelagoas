<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarLagoa extends BaseController implements Gerenciar {
    protected $lagoa;
    protected $projeto;

    public function __construct() {
        parent::__construct();

        $this->lagoa   = new Lagoa($this->getDBH());
        $this->projeto = new Projeto($this->getDBH());
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        $projeto = new Projeto($this->getDBH());
        $smarty->assign('select_projetos', $projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));

        if( $id ) {
            $this->lagoa->setId( $id );
            $this->lagoa->pegar();

            $smarty->assign( 'lagoa', $this->lagoa->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
            isset( $_POST['id_projeto'] ) && $_POST['id_projeto'] != '-1') {

            $this->lagoa->setData( array( 
                'nome'       => $_POST['nome'],
                'id_projeto' => $_POST['id_projeto']
            ));

            try{
                if( isset( $_POST['id_lagoa'] ) && $_POST['id_lagoa'] != '' ) {
                    $this->lagoa->setId( $_POST['id_lagoa'] );
                    if( $this->lagoa->atualizar() )
                        Mensagem::addOk('Lagoa alterada.' );
                    else
                        Mensagem::addErro('Não foi possível salvar a lagoa.' );

                } else {
                    if( $this->lagoa->inserir() )
                        Mensagem::addOk('Lagoa salva!' );
                    else 
                        Mensagem::addErro('Não foi possível salvar a lagoa.' );

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar lagoa.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            Mensagem::addErro('O campo Nome ou Projeto não podem ser vazios.' );
            if (isset($_POST['id_lagoa']) && $_POST['id_lagoa'] != '') {
                $this->editar($_POST['id_lagoa']);
            } else {
                $this->editar();
            }
        }
    }

    public function listar($idProjeto = -1) {
        $smarty = $this->getSmarty(); 
        $template = 'listar.tpl';

        $acoes = array(
            array(
                'modulo' => 'GerenciarColeta',
                'metodo' => 'buscar',
                'param'  => 'id_lagoa/',
                'alt'    => 'Lista coletas',
                'texto'  => '[ C ]',
                'icone'  => 'coleta.png'
            ),
            array(
                'modulo' => 'GerenciarPontoAmostral',
                'metodo' => 'buscar',
                'param'  => 'id_lagoa/',
                'alt'    => 'Lista pontos amostrais',
                'texto'  => '[ P ]',
                'icone'  => 'pontoAmostral.png'
            ),
            array(
                'modulo' => 'GerenciarLagoa',
                'metodo' => 'editar',
                'alt'    => 'Altera lagoa',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarLagoa',
                'metodo' => 'excluir',
                'alt'    => 'Exclui lagoa',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            ),
            array(
                'modulo' => 'GerenciarProjeto',
                'metodo' => 'editar',
                'alt'    => 'Alterar lagoa'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

        if ($idProjeto != -1) {
            $listaLagoas = $this->lagoa->listarPorProjeto($idProjeto, array(
                'campo' => 'l.nome',
                'ordem' => 'ASC'
            ));

            if (count($listaLagoas) == 0) {
                Mensagem::addAtencao(mb_convert_encoding('Não foram encontradas lagoas para o projeto selecionado.', 'latin1', 'UTF-8'));
            }

            $this->projeto->setId($idProjeto);
            $this->projeto->pegar();

            $smarty->assign('nomeProjeto', $this->projeto->getData('nome'));
            $smarty->assign( 'lagoas', $listaLagoas);
            $template = 'listarPorProjeto.tpl';
        } elseif( $this->lagoa->getDataAll() ) {
            $smarty->assign( 'lagoas', $this->lagoa->getDataAll() );
        } elseif( $this->lagoa->getData() ) {
            $smarty->assign( 'lagoas', array ( $this->lagoa->getData() ) );
        } else {
            $smarty->assign( 'lagoas', $this->lagoa->listar(array(
                'campo' => 'l.nome',
                'ordem' => 'ASC'
            )));
        }

        $smarty->displaySubMenuHBF($template);
    }

    public function buscar( $dados = false ) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->lagoa->buscar( $dados, '', array(
                'campo' => 'nome',
                'ordem' => 'ASC'
            ));

            if( $num_linhas > 0 ) {
                $this->listar();
            } else {
                Mensagem::addAtencao('Não foi encontrada nenhuma lagoa.');
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
                $this->lagoa->setId( $id );
                if ($this->lagoa->excluir()) {
                    Mensagem::addOk('Registro excluido.' );
                } else {
                    Mensagem::addErro('Não foi possível excluir a lagoa.');
                }
            } else {
                Mensagem::addErro('Não foi possível excluir a lagoa.');
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->display( 'error.tpl' );
        }
    }

    public function montarSelect( $idProjeto ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc( $idProjeto ) );
        $smarty->displayPiece( 'select_lagoa.tpl', true );
    }

    public function montarMultiSelect( $idProjeto ) {
        $smarty = $this->getSmarty();
        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc( $idProjeto ) );
        $smarty->displayPiece( 'multiselect_lagoa.tpl', true );
    }

    public function montarMultiSelectData($campo, $tipoPeriodo, $idLagoas) {
        $smarty = $this->getSmarty();

        $smarty->assign('campo', $campo);
        $smarty->assign('options', $this->lagoa->listarSelectAssocData($campo, $tipoPeriodo, $idLagoas));
        $smarty->displayPiece( 'multiselect_data.tpl', true );
    }
}

