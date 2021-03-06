<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarEspecie extends BaseController implements Gerenciar {
    protected $especie;
    protected $parametro;

    public function __construct() {
        parent::__construct();

        $this->especie   = new Especie( $this->getDBH() );
        $this->parametro = new Parametro($this->getDBH());
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        $smarty->assign('select_parametros', $this->parametro->listarComposicoes());

        if( $id ) {
            $this->especie->setId( $id );
            $this->especie->pegar();

            $smarty->assign( 'especie', $this->especie->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if( isset( $_POST['nome'] ) && $_POST['nome'] != '' &&
            isset( $_POST['id_parametro'] ) && $_POST['id_parametro'] != '-1') {

            try{
                $this->especie->setData( array( 
                    'nome'         => $_POST['nome'],
                    'id_parametro' => $_POST['id_parametro']
                ) );

                if( isset( $_POST['id_especie'] ) && $_POST['id_especie'] != '' ) {
                    $this->especie->setId( $_POST['id_especie'] );
                    if( $this->especie->atualizar() )
                        Mensagem::addOk('Especie alterada.' );
                    else
                        Mensagem::addErro(latinToUTF('Não foi possível salvar o registro.'));

                } else {
                    if( $this->especie->inserir() )
                        Mensagem::addOk('Especie salva!' );
                    else 
                        Mensagem::addErro(latinToUTF('Não foi possível salvar a espécie.'));

                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar especie.' . $e->getMessage() );
                $smarty->displayError();
            }

        } else {
            Mensagem::addErro(latinToUTF('O campo Nome ou Parametro não podem ser vazios.'));
            $this->editar();
        }
    }

    public function listar($idParametro = '-1') {
        $smarty = $this->getSmarty(); 

        $acoes = array(
            array(
                'modulo' => 'GerenciarEspecie',
                'metodo' => 'editar',
                'alt'    => 'Altera especie',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarEspecie',
                'metodo' => 'excluir',
                'alt'    => 'Exclui especie',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            ),
            array(
                'modulo' => 'GerenciarParametro',
                'metodo' => 'editar',
                'alt'    => 'Altera parametro',
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($acoes));

        if ($idParametro != -1) {
            $listaEspecies = $this->especie->listarPorParametro($idParametro, array(
                'campo' => 'e.nome',
                'ordem' => 'ASC'
            ));
            if (count($listaEspecies) < 1) {
                Mensagem::addAtencao(latinToUTF('Não foram encontradas especie para essa composição.'));
            }
            
            $smarty->assign( 'especies', $listaEspecies);
            $template = 'listarPorProjeto.tpl';
        } else if( $this->especie->getDataAll() ) {
            $smarty->assign( 'especies', $this->especie->getDataAll() );
        } elseif( $this->especie->getData() ) {
            $smarty->assign( 'especies', array ( $this->especie->getData() ) );
        } else {
            $smarty->assign( 'especies', $this->especie->listar(array('campo' => 'nome_parametro, nome', 'ordem' => '')) );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
    }

    public function buscar($campos = '', $dados = false) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            $num_linhas = $this->especie->buscar( $dados, $campos);

            if( $num_linhas > 0 ) {
                $this->listar();
            } else {
                Mensagem::addAtencao(latinToUTF('Não foi encontrado nehuma categoria.'));
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
                $this->especie->setId( $id );
                if ($this->especie->excluir()) {
                    Mensagem::addOk(latinToUTF('Registro excluído.'));
                } else {
                    Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
                }
            } else {
                Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
            }

            $smarty->displaySubMenuHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->display( 'error.tpl' );
        }
    }
}

