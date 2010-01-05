<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarColeta extends BaseController implements Gerenciar {
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $categoriaExtra;
    private $parametro;
    private $coleta;
    private $coletaParametro;
    private $coletaParametroEspecie;
    private $extra;
    private $especie;
    private $parametroExtra;
    private $parametros;
    private $coletaParametros;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();
        
        $this->projeto                = new Projeto( $dbh );
        $this->lagoa                  = new Lagoa( $dbh );
        $this->pontoAmostral          = new PontoAmotral( $dbh );
        $this->categoria              = new Categoria( $dbh );
        $this->categoriaExtra         = new CategoriaExtra( $dbh );
        $this->parametro              = new Parametro( $dbh );
        $this->coleta                 = new Coleta( $dbh );
        $this->coletaParametro        = new ColetaParametro( $dbh );
        $this->coletaParametroEspecie = new ColetaParametroEspecie( $dbh );
        $this->especie                = new Especie( $dbh );
        $this->parametroExtra         = new ParametroExtra( $dbh );
        $this->parametros             = array();
        $this->coletaParametros       = array();
    }

    public function editar( $idColeta = false ) {
        $smarty = $this->getSmarty();
        //$smarty->debugging = true;

        $idProjeto = -1;
        $idLagoa   = -1;

        if( $idColeta ) {

            $this->coleta->setId( $idColeta );
            $this->coleta->pegar();
            $dadosColeta = $this->coleta->getDataFormated();
            $smarty->assign( 'coleta', $dadosColeta );

            $dadosCategoriaExtra = $this->categoriaExtra->getCampoExtraByIdColeta($idColeta);
            $smarty->assign('campoExtraCategoria', $dadosCategoriaExtra);

            
            // Obtém dados de lagoa para saber qual o projeto está selecionado
            $this->lagoa->setId( $dadosColeta['id_lagoa'] );
            $this->lagoa->pegar();
            $idLagoa = $this->lagoa->getId();
            $dadosLagoa = $this->lagoa->getData();

            $smarty->assign( 'id_projeto', $dadosLagoa['id_projeto'] );
            $this->projeto->setId( $dadosLagoa['id_projeto'] );
            $this->projeto->pegar();
            $idProjeto = $this->projeto->getId();

        }

        // Arquivos de pedaços de templates para carregar
        $smarty->assign('dados_parametros', ABSOLUTE_PIECES . 'dados_parametro.tpl');
        $smarty->assign('dado_extra', ABSOLUTE_PIECES . 'dado_extra.tpl');
        $smarty->assign('parametro_categoria_extra', ABSOLUTE_PIECES . 'parametro_categoria_extra.tpl');

        $listaParameros = $this->getSelectParametros($idColeta);

        // FIXME: Descubir espécie de forma dinâmica
        foreach ($listaParameros as $key => $val) {
            $tmp = $this->coletaParametroEspecie->especiesSelecionadas($idColeta, $key, true);
            if (count($tmp) > 0) {
                $listaEspeceisSelecionadas[$key] = $tmp;
            }
        }
        $smarty->assign('especiesSelecionadas', $listaEspeceisSelecionadas);

        $smarty->assign('select_parametro', $listaParameros);
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
        $smarty->assign('select_categoria_extra', $this->categoriaExtra->listarSelectAssoc());
        $smarty->displayHBF( 'editar.tpl' );
    }

    private function getSelectParametros($idColeta) {
        if ($idColeta) {
            $lista = $this->coletaParametro->listarSelectAssoc($idColeta);
        } else {
            $lista = $this->parametro->listarCheckBoxAssoc();
        }

        foreach ($lista as &$item) {
            $campoExtra = $item['nome_campo_extra'];
            if ($item['tem_relacao']) {
                $tmp = $this->$campoExtra->listarAssocPorParametro($item['id_parametro'], $idColeta);
                $item[$campoExtra]              = $tmp['select_assoc'];
                $item["{$campoExtra}_selected"] = $tmp['selected'];
            }
        }

        return $lista;
    }

    public function ajaxNovoParametro($count, $idCategoria) {
        $smarty = $this->getSmarty();
        
        $args = func_get_args();
        if (isset($args[2])) { 
            $campoExtraCategoria = $this->categoriaExtra->getCampoExtraByIdExtra($args[2]);
        } else  {
            $campoExtraCategoria = $this->categoriaExtra->getCampoExtraByIdCategoria($idCategoria);
        }

        $smarty->assign('count', $count);
        $smarty->assign('campoExtraCategoria', $campoExtraCategoria);
        $smarty->assign('select_parametro_extra', $this->parametroExtra->listarSelectAssoc());
        $smarty->assign('parametro_categoria_extra', ABSOLUTE_PIECES . 'parametro_categoria_extra.tpl');
        $smarty->displayPiece('novo_parametro.tpl');
    }

    public function ajaxParametroCategoriaExtra($idCategoria) {
        $smarty = $this->getSmarty();

        $campoExtraCategoria = $this->categoriaExtra->getCampoExtraByIdCategoria($idCategoria);

        if ($campoExtraCategoria != '' && $campoExtraCategoria['nome'] != 'nenhum') {
            $smarty->assign('campoExtraCategoria', $campoExtraCategoria);
            $smarty->displayPiece('parametro_categoria_extra.tpl');
        }
    }

    public function ajaxParametroNovaCategoriaExtra($idExtra) {
        $smarty = $this->getSmarty();

        $campoExtraCategoria = $this->categoriaExtra->getCampoExtraByIdExtra($idExtra);

        if ($campoExtraCategoria != '' && $campoExtraCategoria['nome'] != 'nenhum') {
            $smarty->assign('campoExtraCategoria', $campoExtraCategoria);
            $smarty->displayPiece('parametro_categoria_extra.tpl');
        }
    }

    public function ajaxNovoItemParametroExtra($count, $countItens, $idExtra, $origem) {
        $smarty = $this->getSmarty();

        $campoExtra = $this->parametroExtra->getCampoExtraByIdExtra($idExtra);

        if ($campoExtra != '' && $campoExtra['nome'] != 'nenhum') {
            $smarty->assign('campoExtra', $campoExtra);
            $smarty->assign('count', $count);
            $smarty->assign('countItens', $countItens);
            $smarty->assign('origem', $origem);
            $smarty->displayPiece('novo_item_parametro_extra.tpl');
        }
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        $dbh = $this->getDBH();
        $dbh->beginTransaction();
    
        if(isset($_POST['nome_projeto']) && $_POST['nome_projeto'] != '') {

            $this->projeto->setData( array( 'nome' => $_POST['nome_projeto'] ) );
            $ok_projeto = $this->projeto->inserir();
            ( $ok_projeto ) ? Mensagem::addOk("Projeto salvo") : Mensagem::addErro("Ao salvar Projeto");

        } elseif (isset($_POST['id_projeto']) && $_POST['id_projeto'] != -1) {

            $this->projeto->setId( $_POST['id_projeto'] );
            $this->projeto->pegar();
            Mensagem::addOk('Selecionado o projeto ' . $this->projeto->getData( 'nome' ));
            $ok_projeto = true;

        } else {
            $ok_projeto = false;
            Mensagem::addErro(latinToUTF('Campo Projeto está vazio.'));
        }

        if(isset($_POST['nome_lagoa']) && $_POST['nome_lagoa'] != '') {

            $this->lagoa->setData( array( 
                'nome'       => $_POST['nome_lagoa'],
                'id_projeto' => $this->projeto->getId()
            ));
            $ok_lagoa = $this->lagoa->inserir();
            ( $ok_lagoa ) ? Mensagem::addOk("Lagoa salva") : Mensagem::addErro("Ao salvar Lagoa.");

        } elseif (isset($_POST['id_lagoa']) && $_POST['id_lagoa'] != -1) {

            $this->lagoa->setId( $_POST['id_lagoa'] );
            $this->lagoa->pegar();
            Mensagem::addOk('Selecionada a lagoa ' . $this->lagoa->getData( 'nome' ));
            $ok_lagoa = true;

        } else {
            $ok_lagoa = false;
            Mensagem::addErro(latinToUTF('Campo lagoa está vazio.'));
        }

        if(isset($_POST['nome_ponto_amostral']) && $_POST['nome_ponto_amostral'] != '') {

            $this->pontoAmostral->setData( 
                array( 
                    'nome'     => $_POST['nome_ponto_amostral'],
                    'id_lagoa' => $this->lagoa->getId()
                ) 
            );
            $ok_pontoAmostal = $this->pontoAmostral->inserir();
            ( $ok_pontoAmostal ) ? Mensagem::addOk("Ponto amostral salvo") : Mensagem::addErro("Ao salvar ponto amostral");

        } elseif (isset($_POST['id_ponto_amostral']) && $_POST['id_ponto_amostral'] != -1) {

            $this->pontoAmostral->setId( $_POST['id_ponto_amostral'] );
            $this->pontoAmostral->pegar();
            Mensagem::addOk("Selecionado o ponto amostral " . $this->pontoAmostral->getData( 'nome' ));
            $ok_pontoAmostal = true;

        } else {
            $ok_pontoAmostal = false;
            Mensagem::addErro(latinToUTF('Campo ponto amostral está vazio.'));
        }

        if(isset($_POST['nome_categoria']) && $_POST['nome_categoria'] != '') { 

            $this->categoria->setData( array( 
                'nome'               => $_POST['nome_categoria'] ,
                'id_categoria_extra' => $_POST['id_categoria_extra']
            ));
            $ok_categoria = $this->categoria->inserir();
            ( $ok_categoria ) ? Mensagem::addOk("Categoria salva") : Mensagem::AddErro("Ao salar categoria");

        } elseif (isset($_POST['id_categoria']) && $_POST['id_categoria'] != -1) {

            $this->categoria->setId( $_POST['id_categoria'] );
            $this->categoria->pegar();
            Mensagem::addOk("Selecionada a categoria " . $this->categoria->getData( 'nome' ));
            $ok_categoria = true;

        } else {
            $ok_categoria = false;
            Mensagem::addErro(latinToUTF('Campo categoria está vazio.'));
        }

        if( isset( $_POST['id_parametros'] ) && is_array( $_POST['id_parametros'] ) ) {
            foreach( $_POST['id_parametros'] as $id_parametro ) {
                if (!empty($id_parametro)) { 
                    $this->parametros[$id_parametro] = new Parametro( $dbh );
                    $this->parametros[$id_parametro]->setId( $id_parametro );
                    $this->parametros[$id_parametro]->pegar();
                }
            }
        } else {
            $mensagem_cache = "N&atilde;o foi selecionado nenhum parametro";
        }

        // Verifica se foi enviado valores para os parametros
        if (isset($_POST['valor']) && is_array($_POST['valor'])) {
            $valores = $_POST['valor'];
        } else {
            $valores = array();
            $mensagem_cache_valor = "N&atilde;o foi informado nenhum valor de parametro.";
        }

        // Verifica se foi enviado valores para os novos parametros
        if (isset($_POST['valor_novo']) && is_array($_POST['valor_novo'])) {
            $valoresNovos = $_POST['valor_novo'];
        } else {
            $valoresNovos = array();
            $mensagem_cache_novo_valor = "N&atilde;o foi informado nenhum valor de parametro novo;";
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
                    'id_categoria'      => $this->categoria->getId()
                )
            );

            if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
                $this->coleta->setId( $_POST['id_coleta'] );
                $ok_coleta = $this->coleta->atualizar();
                ( $ok_coleta ) ? Mensagem::addOk("Coleta salva") : Mensagem::addErro("Ao atualizar coleta");

                $this->coletaParametro->excluir( $_POST['id_coleta'] );
            } else {
                $ok_coleta = $this->coleta->inserir();
                ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao salvar coleta");
            }

            $relacaoExtra  = (isset($_POST['relacao_extra'])) ? $_POST['relacao_extra'] : array();
            $valoresExtras = array();

            $ok_novosParametros = false;
            if (isset($_POST['nome_parametros']) && is_array($_POST['nome_parametros']) &&
                isset($_POST['id_parametro_extra']) && is_array($_POST['id_parametro_extra'])
            ) {
                $nomesParametros     = $_POST['nome_parametros'];
                $idsParametrosExtras = $_POST['id_parametro_extra'];
                $itensExtras         = (isset($_POST['item_extra'])) ? $_POST['item_extra'] : array();
                $ok_parametrosCount  = 0;
                $ok_itensExtraCount  = 0;
                foreach ($nomesParametros as $key => $nomeParametro) {
                    $this->parametros[] = $novoParametro = new Parametro($dbh);
                    $novoParametro->setData(array(
                        'nome'               => $nomeParametro,
                        'id_parametro_extra' => $idsParametrosExtras[$key]
                    ));
                    $idParametro = $novoParametro->inserir();
                    if (!$idParametro) {
                        $ok_parametrosCount++;
                    }
                    
                    $parametroExtra = new ParametroExtra($dbh);
                    $parametroExtra->setId($idsParametrosExtras[$key]);
                    $parametroExtra->pegar();
                    $nomeClasse = ucfirst($parametroExtra->getData('tabela'));

                    if ($nomeClasse != 'Nenhuma' && is_array($itensExtras) 
                        && isset($itensExtras[$key])
                    ) {
                        $reflection = new ReflectionClass($nomeClasse);
                        $ok_itensExtraCount = 0;
                        foreach ($itensExtras[$key] as $nomeItemExtra) {
                            $itemExtra = $reflection->newInstance($dbh);
                            // FIXME: Deve pegar os nomes dos campo de forma dinâmica
                            $itemExtra->setData(array(
                                'id_parametro' => $idParametro,
                                'nome'         => $nomeItemExtra
                            ));
                            $idItemExtra = $itemExtra->inserir();
                            if (!$idItemExtra) {
                                $ok_itensExtraCount++;
                            }

                            $relacaoExtra[$idParametro][] = $idItemExtra;
                        }
                        $valoresExtras[$idParametro] = $parametroExtra->getData('tabela');
                    }

                    $valores[$idParametro] = (isset($valoresNovos[$key])) ? $valoresNovos[$key] : '';
                }

                if ($ok_parametrosCount == 0 && $ok_itensExtraCount == 0) {
                    $ok_novosParametros = true;
                } else {
                    Mensagem::addErro("Ao salvar parametros.");
                    $ok_novosParametros = false;
                }
            } else {
                $ok_novosParametros = true; // Seta como true quando não tem parametros novos
            }

            $ok_parametros = false;
            foreach( $this->parametros as $parametro ) {
                $dadosColetaParametro = array();

                $dadosColetaParametro['id_coleta']    = $this->coleta->getId();
                $dadosColetaParametro['id_parametro'] = $parametro->getId();
                if (isset($valores[$parametro->getId()])) {
                    $dadosColetaParametro['valor'] = $valores[$parametro->getId()];
                }

                if (isset($relacaoExtra[$parametro->getId()]) 
                    && is_array($relacaoExtra[$parametro->getId()])
                ) {
                    // FIXME: fazer com que a tabela espécie seja descobera o html tem que de alguma forma enviar os idsParametrosExtras
                    $dadosColetaParametro['valor_extra'] = 'especie';
                }

                if (isset($_POST['valor_categoria_extra']) && $_POST['valor_categoria_extra'] != '') {
                    $dadosColetaParametro['valor_categoria_extra'] = $_POST['valor_categoria_extra'];
                }

                $this->coletaParametros[$parametro->getId()] = new ColetaParametro($dbh);
                $this->coletaParametros[$parametro->getId()]->setData($dadosColetaParametro);
                $ok_coletaParametro = $this->coletaParametros[$parametro->getId()]->inserir();

                if( $ok_coletaParametro ) {
                    $ok_parametros = true;
                } else {
                    Mensagem::addErro("Ao salvar coleta parametros.");
                    $ok_parametros = false;
                    break;
                }
            }

            $countItensExtra = 0;
            $countErrors     = 0;
            foreach ($this->parametros as $parametro) {
                if (isset($relacaoExtra[$parametro->getId()]) 
                    && is_array($relacaoExtra[$parametro->getId()])
                ) {
                    $countItensExtra++;
                    // TODO: fazer com que a tabela espécie seja descobera
                    foreach ($relacaoExtra[$parametro->getId()] as $idEspecie) {
                        $this->coletaParametroEspecie->setData(array(
                            'id_coleta_parametro' => $this->coletaParametros[$parametro->getId()]->getId(),
                            'id_especie'          => $idEspecie
                        ));
                        $ok_coletaParametroEspecie = $this->coletaParametroEspecie->inserir();
                        if (!$ok_coletaParametroEspecie) {
                            $countErrors++;
                        }
                    }
                }
            }

            if ($countItensExtra > 0 && $countErrors > 0) {
                Mensagem::addErro("Ao salvar item do parametro extra.");
                $ok_coletaParametros = false;
            } else {
                $ok_coletaParametros = true;
            }

            if( 
                $ok_projeto          !== false &&
                $ok_lagoa            !== false && 
                $ok_pontoAmostal     !== false && 
                $ok_categoria        !== false && 
                $ok_parametros       !== false && 
                $ok_coleta           !== false && 
                $ok_coletaParametro  !== false &&
                $ok_data             !== false &&
                $ok_novosParametros  !== false &&
                $ok_coletaParametros !== false
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
            $smarty->assign( 'coletas', $this->coleta->getDataAll() );
        } else {
            $smarty->assign( 'coletas', $this->coleta->listar() );
        }

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

}
