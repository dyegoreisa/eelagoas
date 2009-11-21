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
    private $extra;
    private $especie;
    private $parametroExtra;
    private $parametros;
    private $coletaParametros;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();
        
        $this->projeto          = new Projeto( $dbh );
        $this->lagoa            = new Lagoa( $dbh );
        $this->pontoAmostral    = new PontoAmotral( $dbh );
        $this->categoria        = new Categoria( $dbh );
        $this->categoriaExtra   = new CategoriaExtra( $dbh );
        $this->parametro        = new Parametro( $dbh );
        $this->coleta           = new Coleta( $dbh );
        $this->coletaParametro  = new ColetaParametro( $dbh );
        $this->especie          = new Especie( $dbh );
        $this->parametroExtra   = new ParametroExtra( $dbh );
        $this->parametros       = array();
        $this->coletaParametros = array();
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

        $smarty->assign('piece_parametros', ABSOLUTE_PIECES . 'dados_parametro.tpl');
        $smarty->assign('piece_extra', ABSOLUTE_PIECES . 'dado_extra.tpl');

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

        $campoExtra = $this->categoriaExtra->getCampoExtraByIdCategoria($idCategoria);

        $smarty->assign('count', $count);
        $smarty->assign('campoExtra', $campoExtra);
        $smarty->assign('select_parametro_extra', $this->parametroExtra->listarSelectAssoc());
        $smarty->assign('parametro_categoria_extra', ABSOLUTE_PIECES . 'parametro_categoria_extra.tpl');
        $smarty->displayPiece('dados_novo_parametro.tpl');
    }

    public function ajaxParametroCategoriaExtra($idCategoria) {
        $smarty = $this->getSmarty();

        $campoExtra = $this->categoriaExtra->getCampoExtraByIdCategoria($idCategoria);

        if ($campoExtra != '' && $campoExtra['nome'] != 'nenhum') {
            $smarty->assign('campoExtra', $campoExtra);
            $smarty->displayPiece('parametro_categoria_extra.tpl');
        }
    }

    public function ajaxParametroNovaCategoriaExtra($idExtra) {
        $smarty = $this->getSmarty();

        $campoExtra = $this->categoriaExtra->getCampoExtraByIdExtra($idExtra);

        if ($campoExtra != '' && $campoExtra['nome'] != 'nenhum') {
            $smarty->assign('campoExtra', $campoExtra);
            $smarty->displayPiece('parametro_categoria_extra.tpl');
        }
    }

    public function jsonNovoParametroExtra($idExtra) {
        $smarty = $this->getSmarty();

        $campoExtra = $this->parametroExtra->getCampoExtraByIdExtra($idExtra);
        $smarty->displayJson($campoExtra);
    }

    public function salvar() {
debug($_POST, '', false, true);
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

            $this->projeto->setId( $_POST['idColeta_projeto'] );
            $this->projeto->pegar();
            Mensagem::addOk("Selecionado o projeto " . $this->projeto->getData( 'nome' ));
            $ok_projeto = true;

        }

        if( isset( $_POST['nome_lagoa'] ) && !isset( $_POST['idColeta_lagoa'] ) ) {

            $this->lagoa->setData( array( 
                'nome'       => $_POST['nome_lagoa'],
                'idColeta_projeto' => $this->projeto->getId()
            ));
            $ok_lagoa = $this->lagoa->inserir();
            ( $ok_lagoa ) ? Mensagem::addOk("Lagoa salva") : Mensagem::addErro("Ao salvar Lagoa");

        } else {

            $this->lagoa->setId( $_POST['idColeta_lagoa'] );
            $this->lagoa->pegar();
            Mensagem::addOk("Selecionada a lagoa " . $this->lagoa->getData( 'nome' ));
            $ok_lagoa = true;

        }

        if( isset( $_POST['nome_ponto_amostral'] ) && !isset( $_POST['idColeta_ponto_amostral'] ) ) {

            $this->pontoAmostral->setData( 
                array( 
                    'nome'     => $_POST['nome_ponto_amostral'],
                    'idColeta_lagoa' => $this->lagoa->getId()
                ) 
            );
            $ok_pontoAmostal = $this->pontoAmostral->inserir();
            ( $ok_pontoAmostal ) ? Mensagem::addOk("Ponto amostral salvo") : Mensagem::addErro("Ao salvar ponto amostral");

        } else {

            $this->pontoAmostral->setId( $_POST['idColeta_ponto_amostral'] );
            $this->pontoAmostral->pegar();
            Mensagem::addOk("Selecionado o ponto amostral " . $this->pontoAmostral->getData( 'nome' ));
            $ok_pontoAmostal = true;

        }

        if( isset( $_POST['nome_categoria'] ) && !isset( $_POST['idColeta_categoria'] ) ) { 

            $this->categoria->setData( array( 'nome' => $_POST['nome_categoria'] ) );
            $ok_categoria = $this->categoria->inserir();
            ( $ok_categoria ) ? Mensagem::addOk("Categoria salva") : Mensagem::AddErro("Ao salar categoria");

        } else {

            $this->categoria->setId( $_POST['idColeta_categoria'] );
            $this->categoria->pegar();
            Mensagem::addOk("Selecionada a categoria " . $this->categoria->getData( 'nome' ));
            $ok_categoria = true;

        }

        $count = 0;
        if( isset( $_POST['idColeta_parametros'] ) && is_array( $_POST['idColeta_parametros'] ) ) {
            foreach( $_POST['idColeta_parametros'] as $idColeta_parametro ) {
                $this->parametros[$count] = new Parametro( $dbh );
                $this->parametros[$count]->setId( $idColeta_parametro );
                $this->parametros[$count]->pegar();

                $count++;
            }

        } else {
            $mensagem_cache = "N&atilde;o foi selecionado nenhum parametro";
        }

        $novosParametrosIgnorados = array();
        if( isset( $_POST['nome_parametros'] ) && is_array( $_POST['nome_parametros'] ) ) {
            foreach( $_POST['nome_parametros'] as $key => $nome_parametro ) {
                if ($nome_parametro != '') {
                    $this->parametros[$count] = new Parametro( $dbh );
                    $this->parametros[$count]->setData( array( 'nome' => $nome_parametro ) );
                    $ok_parametros = $this->parametros[$count]->inserir();

                    $count++;
                    
                    if( $ok_parametros ) {
                        Mensagem::addOk("parametros salvos");
                    } else {
                        Mensagem::addErro("Ao salvar parametros");
                        break;
                    }
                } else {
                    $novosParametrosIgnorados[] = $key;
                    $ok_parametros = true;
                }
            }
        } else {
            if( isset( $mensagem_cache ) && $mensagem_cache != "" ) {
                Mensagem::addErro($mensagem_cache);
                $ok_parametros = false;
            } else {
                $ok_parametros = true;
            }
        }

        if (isset($_POST['data']) && $_POST['data'] != '' &&
            $this->lagoa->getId() > 0 &&
            $this->pontoAmostral->getId() > 0 &&
            $this->categoria->getId() > 0
        ) {
            $padraoDMAH = '/^(0[1-9]|[12][0-9]|3[01])\/(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
            $padraoMAH  = '/^(0[1-9]|1[012])\/([12][0-9]{3})\ ([01][0-9]|2[0-3])$/';
            if (preg_match($padraoDMAH, $_POST['data'])) {
                $ok_data     = true;                
                $dataISO     = preg_replace($padraoDMAH, '\3-\2-\1 \4:00:00', $_POST['data']); 
                $tipoPeriodo = 'diario';
            } else {
                if (preg_match($padraoMAH, $_POST['data'])) {
                    $ok_data     = true;
                    $dataISO     = preg_replace($padraoMAH, '\2-\1-01 \3:00:00', $_POST['data']); 
                    $tipoPeriodo = 'mensal';
                } else {
                    $ok_data = false;
                    Mensagem::addErro('A data informada est&aacute; no formato incorreto, favor informar no formato (dd/mm/aaaa hh) ou (dd/mm/aaaa hh).');
                }
            }

            $this->coleta->setData( 
                array( 
                    'data'              => $dataISO,
                    'tipo_periodo'      => $tipoPeriodo,
                    'idColeta_lagoa'          => $this->lagoa->getId(),
                    'idColeta_ponto_amostral' => $this->pontoAmostral->getId(),
                    'idColeta_categoria'      => $this->categoria->getId()
                )
            );

            if( isset( $_POST['idColeta_coleta'] ) && $_POST['idColeta_coleta'] != "" ) {
                $this->coleta->setId( $_POST['idColeta_coleta'] );
                $ok_coleta = $this->coleta->atualizar();
                ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao atualizar coleta");
            } else {
                $ok_coleta = $this->coleta->inserir();
                ( $ok_coleta ) ? Mensagem::addOk("coleta salvo") : Mensagem::addErro("Ao salvar coleta");
            }

            $nivel     = (isset($_POST['nivel'])) ? $_POST['nivel'] : array();
            $valor     = (isset($_POST['valor'])) ? $_POST['valor'] : array();
            $nivelNovo = (isset($_POST['nivel_novo'])) ? $_POST['nivel_novo'] : array();
            $valorNovo = (isset($_POST['valor_novo'])) ? $_POST['valor_novo'] : array();
            $nivelNovo = remove_elementos_array($nivelNovo, $novosParametrosIgnorados);
            $valorNovo = remove_elementos_array($valorNovo, $novosParametrosIgnorados);
            $niveis    = unir_arrays( $nivel, $nivelNovo);
            $valores   = unir_arrays( $valor, $valorNovo);

            if( isset( $_POST['idColeta_coleta'] ) && $_POST['idColeta_coleta'] != "" ) {
                $this->coletaParametro->excluir( $_POST['idColeta_coleta'] );
            }

            $count = 0;
            foreach( $this->parametros as $parametro ) {
                $this->coletaParametro->setData(
                    array(
                        'idColeta_coleta'    => $this->coleta->getId(),
                        'idColeta_parametro' => $parametro->getId(),
                        'nivel'        => $niveis[$count],
                        'valor'        => $valores[$count]
                    )
                );
                $ok_coletaParametro = $this->coletaParametro->inserir();

                $count++;
            
                if( $ok_coletaParametro ) {
                    Mensagem::addOk("Coleta do ponto amostral salvo");
                } else {
                    Mensagem::addErro("Ao salvar coleta do ponto amostral");
                    break;
                }
            }

            if( 
                $ok_projeto         !== false &&
                $ok_lagoa           !== false && 
                $ok_pontoAmostal    !== false && 
                $ok_categoria       !== false && 
                $ok_parametros      !== false && 
                $ok_coleta          !== false && 
                $ok_coletaParametro !== false &&
                $ok_data            !== false
            ) {
                $dbh->commit();
            } else {
                $dbh->rollBack();
            }
        } else {
            $dbh->rollBack();
            Mensagem::addErro("Um dos campos estava em branco: Data, Lagoa, Ponto amostral ou categoria");
        }

        $smarty->assign( 'mensagem', Mensagem::fetch() );

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
                $smarty->assign( 'mensagem', 'Registro excluidColetao.' );
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
