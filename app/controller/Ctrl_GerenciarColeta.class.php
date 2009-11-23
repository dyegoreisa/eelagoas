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
            
            $this->lagoa->setId( $dadosColeta['id_lagoa'] );
            $this->lagoa->pegar();
            $idLagoa = $this->lagoa->getId();
            $dadosLagoa = $this->lagoa->getData();

            $smarty->assign( 'id_projeto', $dadosLagoa['id_projeto'] );
            $this->projeto->setId( $dadosLagoa['id_projeto'] );
            $this->projeto->pegar();
            $idProjeto = $this->projeto->getId();

        }

        $smarty->assign('dados_parametros', ABSOLUTE_PIECES . 'dados_parametro.tpl');
        $smarty->assign('dado_extra', ABSOLUTE_PIECES . 'dado_extra.tpl');

        $smarty->assign('select_parametro', $this->getSelectParametros($idColeta));
        $smarty->assign('select_especie', $this->especie->listarSelectAssoc());
        $smarty->assign('select_projeto', $this->projeto->listarSelectAssoc());
        $smarty->assign('select_lagoa', $this->lagoa->listarSelectAssoc($idProjeto));
        $smarty->assign('select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc($idLagoa));
        $smarty->assign('select_categoria', $this->categoria->listarSelectAssoc());
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

        Mensagem::begin();
        Mensagem::setSeparador('<br>');

        $dbh = $this->getDBH();
        $dbh->beginTransaction();
    
        if( isset( $_POST['nome_projeto'] ) && !isset( $_POST['id_projeto'] ) ) {

            $this->projeto->setData( array( 'nome' => $_POST['nome_projeto'] ) );
            $ok_projeto = $this->projeto->inserir();
            ( $ok_projeto ) ? Mensagem::addOk("Projeto salvo") : Mensagem::addErro("Ao salvar Projeto");

        } else {

            $this->projeto->setId( $_POST['id_projeto'] );
            $this->projeto->pegar();
            Mensagem::addOk("Selecionado o projeto " . $this->projeto->getData( 'nome' ));
            $ok_projeto = true;

        }

        if( isset( $_POST['nome_lagoa'] ) && !isset( $_POST['id_lagoa'] ) ) {

            $this->lagoa->setData( array( 
                'nome'       => $_POST['nome_lagoa'],
                'id_projeto' => $this->projeto->getId()
            ));
            $ok_lagoa = $this->lagoa->inserir();
            ( $ok_lagoa ) ? Mensagem::addOk("Lagoa salva") : Mensagem::addErro("Ao salvar Lagoa");

        } else {

            $this->lagoa->setId( $_POST['id_lagoa'] );
            $this->lagoa->pegar();
            Mensagem::addOk("Selecionada a lagoa " . $this->lagoa->getData( 'nome' ));
            $ok_lagoa = true;

        }

        if( isset( $_POST['nome_ponto_amostral'] ) && !isset( $_POST['id_ponto_amostral'] ) ) {

            $this->pontoAmostral->setData( 
                array( 
                    'nome'     => $_POST['nome_ponto_amostral'],
                    'id_lagoa' => $this->lagoa->getId()
                ) 
            );
            $ok_pontoAmostal = $this->pontoAmostral->inserir();
            ( $ok_pontoAmostal ) ? Mensagem::addOk("Ponto amostral salvo") : Mensagem::addErro("Ao salvar ponto amostral");

        } else {

            $this->pontoAmostral->setId( $_POST['id_ponto_amostral'] );
            $this->pontoAmostral->pegar();
            Mensagem::addOk("Selecionado o ponto amostral " . $this->pontoAmostral->getData( 'nome' ));
            $ok_pontoAmostal = true;

        }

        if( isset( $_POST['nome_categoria'] ) && !isset( $_POST['id_categoria'] ) ) { 

            $this->categoria->setData( array( 
                'nome'               => $_POST['nome_categoria'] ,
                'id_categoria_extra' => $_POST['id_categoria_extra']
            ));
            $ok_categoria = $this->categoria->inserir();
            ( $ok_categoria ) ? Mensagem::addOk("Categoria salva") : Mensagem::AddErro("Ao salar categoria");

        } else {

            $this->categoria->setId( $_POST['id_categoria'] );
            $this->categoria->pegar();
            Mensagem::addOk("Selecionada a categoria " . $this->categoria->getData( 'nome' ));
            $ok_categoria = true;

        }

        if( isset( $_POST['id_parametros'] ) && is_array( $_POST['id_parametros'] ) ) {
            foreach( $_POST['id_parametros'] as $id_parametro ) {
                $this->parametros[$id_parametro] = new Parametro( $dbh );
                $this->parametros[$id_parametro]->setId( $id_parametro );
                $this->parametros[$id_parametro]->pegar();
            }

            if (isset($_POST['valor']) && is_array($_POST['valor'])) {
                $valores = $_POST['valor'];
            } else {
                $mensagem_cache_valor = "N&atilde;o foi informado nenhum valor de parametro";
            }
        } else {
            $mensagem_cache = "N&atilde;o foi selecionado nenhum parametro";
        }



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
                Mensagem::addErro('A data informada est&aacute; no formato incorreto, favor informar no formato (dd/mm/aaaa hh) ou (dd/mm/aaaa hh).');
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
                ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao atualizar coleta");

                $this->coletaParametro->excluir( $_POST['id_coleta'] );
            } else {
                $ok_coleta = $this->coleta->inserir();
                ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao salvar coleta");
            }

            $ok_parametros = false;
            foreach( $this->parametros as $parametro ) {
                $dadosColetaParametro = array();

                $dadosColetaParametro['id_coleta']    = $this->coleta->getId();
                $dadosColetaParametro['id_parametro'] = $parametro->getId();
                $dadosColetaParametro['valor']        = $valores[$parametro->getId()];

                if (isset($_POST['relacao_extra'][$parametro->getId()]) 
                    && is_array($_POST['relacao_extra'][$parametro->getId()])
                ) {
                    // TODO: fazer com que a tabela espécie seja descobera
                    $dadosColetaParametro['valor_extra'] = 'especie';
                }

                if (isset($_POST['valor_categoria_extra']) && $_POST['valor_categoria_extra'] != '') {
                    $dadosColetaParametro['valor_categoria_extra'] = $_POST['valor_categoria_extra'];
                }

                $this->coletaParametros[$parametro->getId()] = new ColetaParametro($dbh);
                $this->coletaParametros[$parametro->getId()]->setData($dadosColetaParametro);
                $ok_coletaParametro = $this->coletaParametros[$parametro->getId()]->inserir();

                if( $ok_coletaParametro ) {
                    //Mensagem::addOk("Coleta do ponto amostral salvo");
                    $ok_parametros = true;
                } else {
                    
                    Mensagem::addErro("Ao salvar coleta do ponto amostral");
                    $ok_parametros = false;
                    break;
                }
            }

            // TODO: Esta faltando fazer a implementação para salvar parmetros novos
            /*
            Passos:
            1 - o id do array é o mesmo para todos
            2 - salvar o parametro com o id do parametro extra
            3 - se o parametro for 2 (especie) cadastrar as especies como o id dos parametros cadastrados
            4 - salvar a coleta parametro
            5 - salvar o colata parametro especie
            */
            /* é um exemplo do array que deve ser salvo.
[nome_parametros] => Array
        (
            [0] => Comp Exe
            [1] => Comp Lixo
        )

    [id_parametro_extra] => Array
        (
            [0] => 2
            [1] => 2
        )

    [item] => Array
        (
            [0] => Array
                (
                    [0] => 56
                    [1] => 78
                )

            [1] => Array
                (
                    [0] => 1112
                )

        )

    [valor_novo] => Array
        (
            [0] => 910
            [1] => 1314
        )

            */

            $countItensExtra = 0;
            $countErrors     = 0;
            foreach ($this->parametros as $parametro) {
                if (isset($_POST['relacao_extra'][$parametro->getId()]) 
                    && is_array($_POST['relacao_extra'][$parametro->getId()])
                ) {
                    $countItensExtra++;
                    // TODO: fazer com que a tabela espécie seja descobera
                    foreach ($_POST['relacao_extra'][$parametro->getId()] as $id_especie) {
                        $this->coletaParametroEspecie->setData(array(
                            'id_coleta_parametro' => $this->coletaParametros[$parametro->getId()]->getId(),
                            'id_especie'          => $id_especie
                        ));
                        $ok_coletaParametroEspecie = $this->coletaParametroEspecie->inserir();
                        if (!$ok_coletaParametroEspecie) {
                            Mensagem::addErro("Ao salvar item do parametro extra.");
                            $countErrors++;
                            break;
                        }
                    }
                } 
            }

            if ($countItensExtra > 0 && $countErrors > 0) {
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
                $ok_coletaParametros !== false
            ) {
                //$dbh->commit();
                $dbh->rollBack();
            } else {
                $dbh->rollBack();
            }
        } else {
            $dbh->rollBack();
            Mensagem::addErro("Um dos campos estava em branco: Data, Lagoa, Ponto amostral ou categoria");
        }

        $smarty->assign( 'mensagem', Mensagem::fetch() );
debug($_POST); die;
        $smarty->displayHBF( 'mensagem.tpl' );
    }

    public function listar() {
        $smarty = $this->getSmarty();

        $smarty->assign( 'lagoa', $this->lagoa->getData() ); 

        if( $this->coleta->getDataAll() ) {
            $smarty->assign( 'coletas', $this->coleta->getDataAll() );
        } else {
            $smarty->assign( 'coletas', $this->coleta->listar() );
        }

        $smarty->displayHBF('listar.tpl');
    }

    public function buscar( $dados = false, $campos = '' ) {
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
                $this->coleta->excluir(); 
                $smarty->assign( 'mensagem', 'Registro excluido.' );
            } else {
                $smarty->assign( 'mensagem', 'N&atilde;o foi poss&iacute;vel excluir o registro' );
            }

            $smarty->displayHBF( 'salvar.tpl' );
        }catch( Exception $e ) {
            $smarty->assign( 'mensagem', 'Erro ao tentar exluir um registro.' . $e->getMessage() );
            $smarty->display( 'error.tpl' );
        }
    }

}
