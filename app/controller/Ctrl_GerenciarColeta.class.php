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
    //debug( $_POST, '', false, true  );
    $smarty = $this->getSmarty();
    
    $mensagem = "";

    $dbh = $this->getDBH();
    $dbh->beginTransaction();
  
    if( isset( $_POST['nome_lagoa'] ) && !isset( $_POST['id_lagoa'] ) ) {

      $this->lagoa->setData( array( 'nome' => $_POST['nome_lagoa'] ) );
      $ok_lagoa = $this->lagoa->inserir();
      $mensagem .= ( $ok_lagoa ) ? "lagoa salvo<br>" : "Erro: lagoa<br>";

    } else {

      $this->lagoa->setId( $_POST['id_lagoa'] );
      $this->lagoa->pegar();
      $mensagem .= "Selecionada a lagoa " . $this->lagoa->getData( 'nome' ) . "<br>";
      $ok_lagoa = true;

    }

    if( isset( $_POST['nome_ponto_amostral'] ) && !isset( $_POST['id_ponto_amostral'] ) ) {

      $this->pontoAmostral->setData( 
        array( 
          'nome'      => $_POST['nome_ponto_amostral'],
          'id_lagoa'  => $this->lagoa->getId()
        ) 
      );
      $ok_pontoAmostal = $this->pontoAmostral->inserir();
      $mensagem .= ( $ok_pontoAmostal ) ? "ponto amostral salvo<br>" : "Erro: ponto amostral<br>";

    } else {

      $this->pontoAmostral->setId( $_POST['id_ponto_amostral'] );
      $this->pontoAmostral->pegar();
      $mensagem .= "Selecionado o ponto amostral " . $this->pontoAmostral->getData( 'nome' ) . "<br>";
      $ok_pontoAmostal = true;

    }

    if( isset( $_POST['nome_categoria'] ) && !isset( $_POST['id_categoria'] ) ) { 

      $this->categoria->setData( array( 'nome' => $_POST['nome_categoria'] ) );
      $ok_categoria = $this->categoria->inserir();
      $mensagem .= ( $ok_categoria ) ? "categoria salvo<br>" : "Erro: categoria<br>";

    } else {

      $this->categoria->setId( $_POST['id_categoria'] );
      $this->categoria->pegar();
      $mensagem .= "Selecionada a categoria " . $this->categoria->getData( 'nome' ) . "<br>";
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
      foreach( $_POST['nome_parametros'] as $nome_parametro ) {
        $this->parametros[$count] = new Parametro( $dbh );
        $this->parametros[$count]->setData( array( 'nome' => $nome_parametro ) );
        $ok_parametros = $this->parametros[$count]->inserir();

        $count++;
        
        if( $ok_parametros ) {
          $mensagem .= "parametros salvos<br>";
        } else {
          $mensagem .= "Erro: parametros<br>";
          break;
        }
      }
    } else {
      if( isset( $mensagem_cache ) && $mensagem_cache != "" ) {
        $mesagem .= $mensagem_cache . " e n&atilde;o foi criado nenhum novo parametro";
        $ok_parametros = false;
      }
    }

    $this->coleta->setData( 
      array( 
        'data'              => $_POST['data'],
        'id_lagoa'          => $this->lagoa->getId(),
        'id_ponto_amostral' => $this->pontoAmostral->getId(),
        'id_categoria'      => $this->categoria->getId()
      )
    );

    if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
      $this->coleta->setId( $_POST['id_coleta'] );
      $ok_coleta = $this->coleta->atualizar();
      $mensagem .= ( $ok_coleta ) ? "coleta salvo<br>" : "Erro: coleta<br>";
    } else {
      $ok_coleta = $this->coleta->inserir();
      $mensagem .= ( $ok_coleta ) ? "coleta salvo<br>" : "Erro: coleta<br>";
    }

    $niveis   = unir_arrays( $_POST['nivel'], $_POST['nivel_novo']);
    $valores  = unir_arrays( $_POST['valor'], $_POST['valor_novo']);

    if( isset( $_POST['id_coleta'] ) && $_POST['id_coleta'] != "" ) {
      $this->coletaParametro->excluir( $_POST['id_coleta'] );
    }

    $count = 0;
    foreach( $this->parametros as $parametro ) {
      $this->coletaParametro->setData(
        array(
          'id_coleta'         => $this->coleta->getId(),
          'id_parametro'      => $parametro->getId(),
          'nivel'             => $niveis[$count],
          'valor'             => $valores[$count]
        )
      );
      $ok_coletaParametro = $this->coletaParametro->inserir();

      $count++;
    
      if( $ok_coletaParametro ) {
        $mensagem .= "coleta ponto salvo<br>";
      } else {
        $mensagem .= "Erro: coleta ponto<br>";
        break;
      }
    }

    if( 
      $ok_lagoa           !== false && 
      $ok_pontoAmostal    !== false && 
      $ok_categoria       !== false && 
      $ok_parametros      !== false && 
      $ok_coleta          !== false && 
      $ok_coletaParametro !== false 
    ) {
      $dbh->commit();
    } else {
      $dbh->rollBack();
      $mensagem .= "N&atilde;o foram salvos, opera&ccedil;&otilde;es estornadas<br>";
    }

    $smarty->assign( 'mensagem', $mensagem );

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

  public function buscar( $dados = false ) {
    $smarty = $this->getSmarty();

    if( $dados || isset( $_REQUEST['dados'] ) && $_REQUEST['dados'] != '' ) {
      if( !$dados ) {
        $dados = $_REQUEST['dados'];
      }

      if( is_numeric( $dados ) ) {
        $this->lagoa->setId( $dados );
        $this->lagoa->pegar();
      }

      $num_linhas = $this->coleta->buscar( $dados );

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
