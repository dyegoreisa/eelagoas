<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarColeta extends BaseController implements Gerenciar {
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $parametro;
    private $coleta;
    private $coletaParametro;
    private $coletaParametroEspecie;
    private $especie;
    private $parametros;
    private $coletaParametros;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();
        
        $this->projeto                = new Projeto( $dbh );
        $this->lagoa                  = new Lagoa( $dbh );
        $this->pontoAmostral          = new PontoAmotral( $dbh );
        $this->categoria              = new Categoria( $dbh );
        $this->parametro              = new Parametro( $dbh );
        $this->coleta                 = new Coleta( $dbh );
        $this->coletaParametro        = new ColetaParametro( $dbh );
        $this->coletaParametroEspecie = new ColetaParametroEspecie( $dbh );
        $this->especie                = new Especie( $dbh );
        $this->parametros             = array();
        $this->coletaParametros       = array();
    }

    public function editar($idColeta = false, $mensagem = '') 
    {
        if ($mensagem != '') {
            Mensagem::addAtencao(latinToUTF($mensagem));
        }

        $smarty = $this->getSmarty();
        //$smarty->debugging = true;

        $idProjeto = -1;
        $idLagoa   = -1;

        if( $idColeta ) {

            $this->coleta->setId( $idColeta );
            $this->coleta->pegar();
            $dadosColeta = $this->coleta->getDataFormated();
            $smarty->assign( 'coleta', $dadosColeta );

            // Obtém dados de lagoa para saber qual o projeto está selecionado
            $this->lagoa->setId( $dadosColeta['id_lagoa'] );
            $this->lagoa->pegar();
            $idLagoa = $this->lagoa->getId();
            $dadosLagoa = $this->lagoa->getData();

            $smarty->assign( 'id_projeto', $dadosLagoa['id_projeto'] );
            $this->projeto->setId( $dadosLagoa['id_projeto'] );
            $this->projeto->pegar();
            $idProjeto = $this->projeto->getId();

            $this->categoria->setId($dadosColeta['id_coleta']);
            $smarty->assign('tem_profundidade', $this->categoria->temProfundidade());

        }

        $smarty->assign('select_parametro', $this->getSelectParametros($idColeta));
        $smarty->assign('select_especie', $this->especie->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));
        $smarty->assign('select_projeto', $this->projeto->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));
        $smarty->assign('select_lagoa', $this->lagoa->listarSelectAssoc($idProjeto));
        $smarty->assign('select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc($idLagoa));
        $smarty->assign('select_categoria', $this->categoria->listarSelectAssoc(array(
            'campo' => 'nome',
            'ordem' => 'ASC'
        )));
        $smarty->displayHBF( 'editar.tpl' );
    }

    private function getSelectParametros($idColeta) {
        if ($idColeta) {
            $lista = $this->coletaParametro->listarSelectAssoc($idColeta);
        } else {
            $lista = $this->parametro->listarCheckBoxAssoc();
        }

        foreach ($lista as &$item) {
            $item['especies'] = $this->especie->listarSelected($item['id_parametro'], $idColeta, array(
                'campo' => 'nome',
                'ordem' => 'ASC'
            ));
        }

        return $lista;
    }

    public function ajaxNovoParametro($count) {
        $smarty = $this->getSmarty();

        $smarty->assign('count', $count);
        $smarty->displayPiece('novo_parametro.tpl');
    }

    public function salvar() {

        $smarty = $this->getSmarty();

        $dbh = $this->getDBH();
        $dbh->beginTransaction();
    
        if (isset($_POST['id_projeto']) && $_POST['id_projeto'] != -1) {
            $this->projeto->setId( $_POST['id_projeto'] );
            $this->projeto->pegar();
            Mensagem::addOk('Selecionado o projeto ' . $this->projeto->getData( 'nome' ));
            $ok_projeto = true;
        } else {
            $ok_projeto = false;
            Mensagem::addErro(latinToUTF('Campo Projeto está vazio.'));
        }

        if (isset($_POST['id_lagoa']) && $_POST['id_lagoa'] != -1) {
            $this->lagoa->setId( $_POST['id_lagoa'] );
            $this->lagoa->pegar();
            Mensagem::addOk('Selecionada a lagoa ' . $this->lagoa->getData( 'nome' ));
            $ok_lagoa = true;
        } else {
            $ok_lagoa = false;
            Mensagem::addErro(latinToUTF('Campo lagoa está vazio.'));
        }

        if (isset($_POST['id_ponto_amostral']) && $_POST['id_ponto_amostral'] != -1) {
            $this->pontoAmostral->setId( $_POST['id_ponto_amostral'] );
            $this->pontoAmostral->pegar();
            Mensagem::addOk("Selecionado o ponto amostral " . $this->pontoAmostral->getData( 'nome' ));
            $ok_pontoAmostal = true;
        } else {
            $ok_pontoAmostal = false;
            Mensagem::addErro(latinToUTF('Campo ponto amostral está vazio.'));
        }

        if (isset($_POST['id_categoria']) && $_POST['id_categoria'] != -1) {
            $this->categoria->setId( $_POST['id_categoria'] );
            $this->categoria->pegar();
            Mensagem::addOk("Selecionada a categoria " . $this->categoria->getData( 'nome' ));
            $ok_categoria = true;
        } else {
            $ok_categoria = false;
            Mensagem::addErro(latinToUTF('Campo categoria está vazio.'));
        }

        // Insere coleta
        if (isset($_POST['data']) && $_POST['data'] != '' &&
            $this->lagoa->getId() > 0 &&
            $this->pontoAmostral->getId() > 0 &&
            $this->categoria->getId() > 0
        ) {
            $padraoDMAH = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
            $padraoMAH  = '/^(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
            $tipoPeriodo = '';
            if (preg_match($padraoDMAH, $_POST['data'])) {
                $ok_data     = true;                
                $dataISO     = preg_replace($padraoDMAH, '\3-\2-\1 \4:00:00', $_POST['data']); 
                $tipoPeriodo = 'diario';
            } elseif (preg_match($padraoMAH, $_POST['data'])) {
                $ok_data     = true;
                $dataISO     = preg_replace($padraoMAH, '\2-\1-01 \3:00:00', $_POST['data']); 
                $tipoPeriodo = 'mensal';
            } else {
                $ok_data = false;
                Mensagem::addErro(latinToUTF('A data informada está no formato incorreto, favor informar no formato (dd/mm/aaaa hh) ou (dd/mm/aaaa hh).'));
            }

            $this->coleta->setData( 
                array( 
                    'data'              => $dataISO,
                    'tipo_periodo'      => $tipoPeriodo,
                    'id_lagoa'          => $this->lagoa->getId(),
                    'id_ponto_amostral' => $this->pontoAmostral->getId(),
                    'id_categoria'      => $this->categoria->getId(),
                    'profundidade'      => (isset($_POST['profundidade']) && $_POST['profundidade'] != "") ? $_POST['profundidade'] : 0
                )
            );

            if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
                $this->coleta->setId( $_POST['id_coleta'] );
                $ok_coleta = $this->coleta->atualizar();
                ( $ok_coleta ) ? Mensagem::addOk("Coleta salva") : Mensagem::addErro("Ao atualizar coleta");

                $this->coletaParametro->excluir( $_POST['id_coleta'] );
            } else {
                if ($this->coleta->localizar()) {
                    $this->go("/GerenciarColeta/editar/{$this->coleta->getId()}/A coleta já existe, faça as alterações necessárias.");
                } else {
                    try {
                        $ok_coleta = $this->coleta->inserir();
                    } catch (Exception $e) {
                        $ok_coleta = false;
                    }
                    ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao salvar coleta");
                }
            }

            if ($ok_coleta) {
                // regras de parametros
                if($this->temParametro($_POST['parametros'])) {
                    foreach ($_POST['parametros'] as $parametro) {
                        if (isset($parametro['id']) && $parametro['id'] != "") {
                            if (isset($parametro['valor']) && $parametro['valor'] != "") {
                                $tmpValor = $parametro['valor'];
                            } elseif (isset($parametro['especie']) && is_array($parametro['especie']) && count($parametro['especie']) > 0) {
                                $tmpValor = $this->contaEspecie($parametro['especie']);
                            } else {
                                $tmpValor = 0;
                            }
                            $this->coletaParametro->setData(array(
                                'id_coleta'    => $this->coleta->getId(),
                                'id_parametro' => $parametro['id'],
                                'valor'        => $tmpValor
                            ));
                            $this->coletaParametro->inserir();
                            
                            if (isset($parametro['especie']) && is_array($parametro['especie']) && count($parametro['especie']) > 0) {
                                foreach ($parametro['especie'] as $especie) {
                                    if (isset($especie['id']) && $especie['id'] != "") {
                                        $this->coletaParametroEspecie->setData(array(
                                            'id_coleta_parametro' => $this->coletaParametro->getId(),
                                            'id_especie'          => $especie['id'],
                                            'quantidade'          => $especie['qtde']
                                        ));
                                        $this->coletaParametroEspecie->inserir();
                                    }
                                }
                            }
                        }
                    }
                    $ok_parametros = true;
                } else {
                    $ok_parametros = false;
                }
            } else {
                $ok_parametros = false;
            }

            if( 
                $ok_projeto          !== false &&
                $ok_lagoa            !== false && 
                $ok_pontoAmostal     !== false && 
                $ok_categoria        !== false && 
                $ok_parametros       !== false && 
                $ok_coleta           !== false && 
                $ok_coletaParametro  !== false &&
                $ok_data             !== false
            ) {
                $dbh->commit();
            } else {
                $dbh->rollBack();
            }
        } else {
            $dbh->rollBack();
            Mensagem::addErro("Um dos campos estava em branco: Data, Projeto, Lagoa, Ponto amostral ou categoria");
        }

        $smarty->displayHBF( 'mensagem.tpl' );
    }

    public function listar() {
        $smarty = $this->getSmarty();

        $acoes = array(
            array(
                'modulo' => 'GerenciarColeta',
                'metodo' => 'editar',
                'alt'    => 'Altera coleta',
                'texto'  => '[ A ]',
                'icone'  => 'editar.png'
            ),
            array(
                'modulo' => 'GerenciarColeta',
                'metodo' => 'excluir',
                'alt'    => 'Exclui coleta',
                'texto'  => '[ E ]',
                'class'  => 'excluir',
                'icone'  => 'excluir.png'
            )
        );

        $permissao = new Permissao();
        $smarty->assign('acoesLista', $permissao->getListaPermitida($_SESSION[$_SESSION['SID']]['idPerfil'], $acoes));

        $smarty->assign( 'lagoa', $this->lagoa->getData() ); 

        if( $this->coleta->getDataAll() ) {
            $dadosColeta = $this->coleta->getDataAll();
        } else {
            $dadosColeta = $this->coleta->listar();
        }

        foreach ($dadosColeta as &$dado) {
            $dado['categoria']      = $this->categoria->getFieldById($dado['id_categoria'], 'nome');
            $dado['ponto_amostral'] = $this->pontoAmostral->getFieldById($dado['id_ponto_amostral'], 'nome');
        }
        $smarty->assign('coletas', $dadosColeta);
        $smarty->displayHBF('listar.tpl');
    }

    public function buscar($campos = '', $dados = false) {
        $smarty = $this->getSmarty();

        if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
            if( !$dados ) {
                $dados = $_REQUEST['dados'];
            }

            if( is_numeric( $dados ) ) {
                $this->lagoa->setId( $dados );
                $this->lagoa->pegar();
            }

            $num_linhas = $this->coleta->buscar( $dados, $campos );

            if( $num_linhas > 0 ) {
                $this->listar();
            }
            else {
                $smarty->assign('msg', "N&atilde;o foram encontradas informa&ccedil;&otilde;es com a palavra {$dados}");
                $smarty->displayHBF('buscar.tpl');
            }
        } else {
            $smarty->displayHBF('buscar.tpl');
        }
    }

    public function excluir( $idColeta ) {
        $smarty = $this->getSmarty();
        
        try{
            if( isset( $idColeta ) && $idColeta != '' ) {
                $this->coleta->setId( $idColeta );
                if ($this->coleta->excluir()) {
                    Mensagem::addOk('Registro excluido.' );
                } else {
                    Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
                }
            } else {
                Mensagem::addErro(latinToUTF('Não foi possível excluir o registro'));
            }

            $smarty->displayHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            Mensagem::addErro('Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->displayError();
        }
    }

    public function ajaxExibeProfundidade($id_categoria) {
        $this->categoria->setId($id_categoria); 

        $this->getSmarty()->displayJson($this->categoria->temProfundidade());
    }

    private function temParametro($parametros) {
        foreach($parametros as $parametro) {
            if(isset($parametro['id']) && $parametro['id'] != "") {
                return true;
            }
        }
        return false;
    }

    private function contaEspecie($especies) {
        $count = 0;
        foreach ($especies as $especie) {
            if(isset($especie['id']) && $especie['id'] != "") {
                $count++;
            }
        }
        return $count;
    }

}
