<?php
require_once 'Gerenciar.php';

class Ctrl_GerenciarColeta extends BaseController implements Gerenciar {
    private $lagoa;
    private $pontoAmostral;
    private $categoria;
    private $parametro;
    private $coleta;
    private $coletaParametro;
    private $parametros;
    private $coletaParametros;

    public function __construct() {
        parent::__construct();

        $dbh = $this->getDBH();
        
        $this->lagoa            = new Lagoa( $dbh );
        $this->pontoAmostral    = new PontoAmotral( $dbh );
        $this->categoria        = new Categoria( $dbh );
        $this->parametro        = new Parametro( $dbh );
        $this->coleta           = new Coleta( $dbh );
        $this->coletaParametro  = new ColetaParametro( $dbh );
        $this->parametros       = array();
        $this->coletaParametros = array();
    }

    public function editar( $id = false ) {
        $smarty = $this->getSmarty();
        //$smarty->debugging = true;

        $idLagoa = -1;

        if( $id ) {

            $this->coleta->setId( $id );
            $this->coleta->pegar();
            $dados_coleta = $this->coleta->getData();
            $smarty->assign( 'coleta', $dados_coleta );
            
            $this->lagoa->setId( $dados_coleta['id_lagoa'] );
            $this->lagoa->pegar();
            $idLagoa = $this->lagoa->getId();

            $smarty->assign( 'select_parametro', $this->coletaParametro->listarSelectAssoc( $id ));

        } else {
            $smarty->assign( 'select_parametro', $this->parametro->listarSelectAssoc() );
        }

        $smarty->assign( 'select_lagoa', $this->lagoa->listarSelectAssoc() );
        $smarty->assign( 'select_ponto_amostral', $this->pontoAmostral->listarSelectAssoc( $idLagoa ) );
        $smarty->assign( 'select_categoria', $this->categoria->listarSelectAssoc() );
        $this->getSmarty()->displayHBF( 'editar.tpl' );
    }

    public function salvar() {
        $smarty = $this->getSmarty();

        Mensagem::begin();
        Mensagem::setSeparador('<br>');

        $dbh = $this->getDBH();
        $dbh->beginTransaction();
    
        if( isset( $_POST['nome_lagoa'] ) && !isset( $_POST['id_lagoa'] ) ) {

            $this->lagoa->setData( array( 'nome' => $_POST['nome_lagoa'] ) );
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

            $this->categoria->setData( array( 'nome' => $_POST['nome_categoria'] ) );
            $ok_categoria = $this->categoria->inserir();
            ( $ok_categoria ) ? Mensagem::addOk("Categoria salva") : Mensagem::AddErro("Ao salar categoria");

        } else {

            $this->categoria->setId( $_POST['id_categoria'] );
            $this->categoria->pegar();
            Mensagem::addOk("Selecionada a categoria " . $this->categoria->getData( 'nome' ));
            $ok_categoria = true;

        }

        $count = 0;
        if( isset( $_POST['id_parametros'] ) && is_array( $_POST['id_parametros'] ) ) {
            foreach( $_POST['id_parametros'] as $id_parametro ) {
                $this->parametros[$count] = new Parametro( $dbh );
                $this->parametros[$count]->setId( $id_parametro );
                $this->parametros[$count]->pegar();

                $count++;
            }

        } else {
            $mensagem_cache = "N&atilde;o foi selecionado nenhum parametro";
        }

        if( isset( $_POST['nome_parametros'] ) && is_array( $_POST['nome_parametros'] ) ) {
            $novosParametrosIgnorados = array();
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
            if (preg_match('/\d{2}\/\d{4}/', $_POST['data'])) {
                $ok_data = true;                
            } else {
                $ok_data = false;
                Mensagem::addErro('A data informada est&aacute; no formato incorreto, favor informar no formato (mm/aaaa).');
            }

            $this->coleta->setData( 
                array( 
                    'data'              => preg_replace('/(\d{2})\/(\d{4})/', '\2-\1', $_POST['data']) . '-01',
                    'id_lagoa'          => $this->lagoa->getId(),
                    'id_ponto_amostral' => $this->pontoAmostral->getId(),
                    'id_categoria'      => $this->categoria->getId()
                )
            );

            if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
                $this->coleta->setId( $_POST['id_coleta'] );
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

            if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
                $this->coletaParametro->excluir( $_POST['id_coleta'] );
            }

            $count = 0;
            foreach( $this->parametros as $parametro ) {
                $this->coletaParametro->setData(
                    array(
                        'id_coleta'    => $this->coleta->getId(),
                        'id_parametro' => $parametro->getId(),
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

    public function excluir( $id ) {
        $smarty = $this->getSmarty();
        
        try{
            if( isset( $id ) && $id != '' ) {
                $this->coleta->setId( $id );
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
