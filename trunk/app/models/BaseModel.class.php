<?php
/**
 * BaseModel 
 * 
 * @abstract
 * @package 
 * @version     $id$
 * @copyright 2009 - Link Simbólico
 * @author        Dyego Reis de Azevedo <dyegoreisa@yahoo.com.br> 
 * @license     PHP Version 5.2 {@link http://www.php.net/license/}
 */
abstract class BaseModel {
    protected $dbh;

    protected $table;

    protected $nameId;

    protected $id;

    protected $data;

    protected $dataAll;

    /**
     * search 
     *
     *    Armazena parametros para usar na busca
     * 
     *    Array (
     *            [field]    => operator
     *            [field]    => Array ( [type] => operator    )
     *    )
     *    
     * 
     * @var array
     * @access protected
     */
    protected $search;

    /**
     * restricao 
     *
     *    Armazena as informações para fazer uma restrição
     * 
     * @var array
     * @access protected
     */
    protected $restricao;
    
    /**
     * __construct 
     * 
     * @access public
     * @return void
     */
    public function __construct( $dbh ) {
        $this->dbh = $dbh;
    }

    public function getNameId() {
        return $this->nameId;
    }

    public function setNameId( $nameId ) {
        $this->nameId = $nameId;
    }

    public function getId(){
        return $this->id;
    }

    public function setId( $id ) {
        $this->id = $id;
    }

    public function getData( $field = false ) {
        if( $field !== false )
            return $this->data[$field];
        else
            return $this->data;
    }
    
    public function setData( $dados ) {
        $this->data = $dados;
    }

    public function getDataAll() {
        return $this->dataAll;
    }

    public function setDataAll( $dataAll ) {
        $this->dataAll = $dataAll;
    }

    /**
     * inserir 
     * 
     * @param array $dados 
     * @access public
     * @return void
     */
    public function inserir() {
        $sql = $this->gerarInsert();
        //debug( $sql, '$sql' );
        $ok = $this->dbh->exec( $sql );
        if( $this->dbh->errorCode() != 00000 ) {
            $e = $this->dbh->errorInfo();
            throw new Exception( "<hr>{$e[2]}<br><br><strong>SQL:</strong> {$sql}<hr>" );
        }

        if( $ok ) {
            $this->id = $this->dbh->lastInsertId();
            return $this->id;
        } else {
            return false;
        }
    }
    
    /**
     * gerarInsert 
     * 
     * @access public
     * @return string
     */
    public function gerarInsert() {
        $sql = "INSERT INTO {$this->table}";
        
        $campos    = " (";
        $valores = " VALUES(";
        foreach( $this->data as $campo => $valor ) {
            if( !is_string( $campo ) )
                throw new Exception('<hr>O nome do campo na tabela n&atilde;o foi informado.<hr>');

            if( $valor !== '' ) {
                $campos    .= $campo . ',';
                $valores .= $this->dbh->quote($valor) . ',';
            }
        }
        $campos     = substr_replace( $campos, ')', -1, 1 );    // Subistitui a última virgula por parenteses
        $valores    = substr_replace( $valores, ')', -1, 1 ); // Subistitui a última virgula por parenteses

        $sql .= $campos.$valores;

        return $sql;
    }

    /**
     * listar 
     * 
     * @param array $order 
     * @access public
     * @return void
     */
    public function listar( $order = false ){
        $sql = "SELECT * FROM {$this->table}";

        if( $this->restricao ) {
            $sql .= " WHERE {$this->restricao['campo']} = {$this->restricao['valor']}";
        }

        if( $order )
            $sql .= " ORDER BY {$order['campo']} {$order['ordem']}";

        $sql .= ' LIMIT ' . LIMIT;

        $sth = $this->dbh->prepare( $sql );
        //$sth->debugDumpParams(); //DEBUG 
        $sth->execute();

        return $sth->fetchAll();
    }
    
    /**
     * Pega dados com o id informado
     *
     * @param Int $id
     * @return Array
     */
    public function pegar() {
        $sth = $this->dbh->prepare( 
            "SELECT * FROM {$this->table} WHERE {$this->nameId} = :value", 
            array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY) 
        );

        $sth->execute( array( ':value' => $this->id ) );
        if( $sth->errorCode() != 00000 ) {
            $e = $sth->errorInfo();
            throw new Exception( "<hr>{$e[2]}<br><br><strong>SQL:</strong> {$sql}<hr>" );
        }

        $this->data = $sth->fetch();

        if( $this->data == true )
            return true;
        else
            return false;
    }
    
    /**
     * Atualiza os dados
     *
     * @param Int $id
     * @param Array $dados
     * @return Boolean
     */
    public function atualizar(){
        $sql = $this->gerarUpdate();
        //debug( $sql, 'SQL UPDATE gerado');

        $ok = $this->dbh->exec( $sql );
        if( $this->dbh->errorCode() != 00000 ) {
            $e = $this->dbh->errorInfo();
            throw new Exception( "<hr>{$e[2]}<br><br><strong>SQL:</strong> {$sql}<hr>" );
        } else {
            $ok = true;
        }

        if($ok) {
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * gerarUpdate 
     * 
     * @access public
     * @return void
     */
    public function gerarUpdate(){
        $sql = "UPDATE {$this->table} SET ";
        foreach( $this->data as $campo => $valor ) {
            if( $valor !== '' ) {
                $sql .= $campo . ' = ' . $this->dbh->quote($valor) . ', ';
            }
        }
        $sql = substr( $sql, 0, -2 );    // Retira a última virgula

        $sql .= " WHERE {$this->nameId} = {$this->id}";

        return $sql;
    }

    /**
     * excluir 
     * 
     * @access public
     * @return void
     */
    public function excluir(){
        $sql = "DELETE FROM {$this->table} WHERE {$this->nameId} = {$this->id}";
        $ok = $this->dbh->exec( $sql );
        if( $this->dbh->errorCode() != 00000 ) {
            $e = $this->dbh->errorInfo();
            throw new Exception( "<hr>{$e[2]}<br><br><strong>SQL:</strong> {$sql}<hr>" );
        }

        if( $ok )
            return true;
        
        else
            return false;
    }

    /**
     * montarParteBusca 
     * 
     * @param mixed $campo 
     * @param mixed $operador 
     * @param mixed $dados 
     * @access protected
     * @return string
     */
    protected function montarParteBusca( $campo, $operador, $dados ) {
        switch( $operador ) {
            case "LIKE":
                return " {$campo} {$operador} '%{$dados}%' OR";
            
            case "=":
                return " {$campo} {$operador} '{$dados}' OR";
        }
    }

    /**
     * montarBusca 
     * 
     * @param mixed $dados 
     * @access public
     * @return void
     *
     * TODO: Fazer critério de relevancia.
     */
    public function montarBusca( $dados, $campos = '') {
        $sql = "SELECT * FROM {$this->table} WHERE ";

        $sth_column = $this->dbh->prepare( "SHOW COLUMNS FROM {$this->table} WHERE field = :campo" );

        if (!empty($campos)) {
            $camposInformados = explode(',', $campos);
        } else {
            $camposInformados = array();
        }

        $this->search = remove_elementos_array($this->search, $camposInformados, true);
        foreach( $this->search as $campo => $operador ) {
            $sth_column->execute( array( ':campo' => $campo ) );
            $dados_campo = $sth_column->fetch();

            $info_campo = array();
            preg_match("/int|char|varchar|blob|double|float|date|enum|datetime/", $dados_campo['Type'], $info_campo );

            switch( $info_campo[0] ) {
                case "int":
                case "float":
                case "double":
                    if( is_numeric( $dados ) )
                        $sql .= $this->montarParteBusca( $campo, $operador, $dados );
                    break;

                case "varchar":
                case "char":
                case "blob":
                case "enum":
                    $sql .= $this->montarParteBusca( $campo, $operador, $dados );
                    break;

                case "date":
                case "datetime":
                    $sql .= $this->montarParteBusca( $campo, $operador, $dados );
                    break;
            }
            
        }

        $sql = substr( $sql, 0, -3 ); // Remove o último OR

        return $sql;
    }

    /**
     * buscar 
     * 
     * @param mixed $dados 
     * @access public
     * @return int
     */
    public function buscar( $dados, $campos = '' ) {
        $sql = $this->montarBusca( $dados, $campos );

        $sth = $this->dbh->prepare( $sql );

        $sth->execute();
        //$sth->debugDumpParams(); // DEBUG 
        if( $sth->errorCode() != 00000 ) {
            $e = $sth->errorInfo();
            throw new Exception( "<hr>{$e[4]}<br><br><strong>SQL:</strong> {$sql}<hr>" );
        }

        $row_count = $sth->rowCount();
        if( $row_count > 1 ) {
            $this->setDataAll( $sth->fetchAll() );
            $this->setData( array() );
            $this->setId( '' );
        } 
        elseif( $row_count == 1 ) {
            $this->setDataAll( $sth->fetchAll() );
            $this->setData( $sth->fetch() );
            $this->setId( $this->getData( $this->getNameId() ) );
        }
        else {
            $this->setDataAll( array() );
            $this->setData( array() );
            $this->setId( '' );
        }

        return $row_count;
    }
}