<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarParametro extends BaseController implements Gerenciar {
    protected $parametro;
    protected $especie;

    public function __construct() {
        parent::__construct();

        $this->parametro      = new Parametro( $this->getDBH() );
        $this->especie        = new Especie($this->getDBH());
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty(); 

        if( $id ) {
            $this->parametro->setId( $id );
            $this->parametro->pegar();

            $smarty->assign('composicao', ($this->parametro->getData('composicao') == 1) ? 'checked' :'');
            $smarty->assign('parametro', $this->parametro->getData() );
        }

        $smarty->displaySubMenuHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        if(isset($_POST['nome']) && $_POST['nome'] != '') {

            try{
                $this->parametro->setData( array(
                    'nome' => $_POST['nome'],
                    'composicao' => (isset($_POST['composicao']) && $_POST['composicao'] == 1) ? 1 : 0
                ));

                if( isset( $_POST['id_parametro'] ) && $_POST['id_parametro'] != '' ) {
                    $this->parametro->setId( $_POST['id_parametro'] );

                    if($this->parametro->atualizar()) {
                        Mensagem::addOk('Parametro alterado!');
                    } else {
                        Mensagem::addErro(latinToUTF('Não foi possível atualizar o parametro'));
                    }
                } else {
                    if($this->parametro->inserir()) {
                        Mensagem::addOk('Parametro salvo!' );
                    } else {
                        Mensagem::addErro(latinToUTF('Não foi possível salvar a parametro.'));
                    }
                }
                $smarty->displaySubMenuHBF( 'salvar.tpl' );

            } catch (Exception $e) {
                Mensagem::addErro('Problema ao salvar parametro.' . $e->getMessage() );
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
                'modulo'  => 'GerenciarEspecie',
                'metodo'  => 'listar',
                'alt'     => 'Listar especie',
                'texto'   => '[ Es ]',
                'icone'   => 'especie.png',
                'compara' => array (
                    'valor'  => 0, 
                    'icone2' => 'especie2.png'
                )
            ),
            array(
                'modulo' => 'GerenciarParametro',
                'metodo' => 'editar',
                'alt'    => 'Altera parametro',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarParametro',
                'metodo' => 'excluir',
                'alt'    => 'Exclui parametro',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($acoes));

        if( $this->parametro->getDataAll() ) {
            $smarty->assign( 'parametros', $this->parametro->getDataAll() );
        } elseif( $this->parametro->getData() ) {
            $smarty->assign( 'parametros', array ( $this->parametro->getData() ) );
        } else {
            $smarty->assign( 'parametros', $this->parametro->listar(array('campo' => 'nome', 'ordem' => 'ASC')) );
        }

        $smarty->displaySubMenuHBF( 'listar.tpl' );
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
                Mensagem::addAtencao(latinToUTF('Não foi encontrado nenhum parametro.'));
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
                $this->parametro->setId( $id );
                if ($this->parametro->excluir()) {
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

    public function eComposicao($parametros) {
        $partes = explode(',', $parametros);
        $count = 0;
        foreach ($partes as $idParametro) {
            $this->parametro->setId($idParametro);
            if ($this->parametro->eComposicao()) {
                $count++;
            }
        }

        if ($count > 0) {
            $this->getSmarty()->displayJson(array(true));
        } else {
            $this->getSmarty()->displayJson(array(false));
        }
    }

    public function montarMultiSelectEspecie($parametros) {
        $smarty = $this->getSmarty();

        $smarty->assign('select_especie', $this->especie->listarSelectAssoc($parametros, array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        ))); 

        $smarty->displayPiece('multiselect_especie.tpl');
    }
}

