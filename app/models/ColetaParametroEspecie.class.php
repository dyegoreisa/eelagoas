<?php
class ColetaParametroEspecie extends BaseModel {
    public function __construct( $dbh ) {
        parent::__construct( $dbh );

        $this->table   = 'coleta_parametro_especie';
        $this->nameId  = 'id_coleta_parametro_especie';
        $this->data    = array();
        $this->dataAll = array();
        $this->search  = array(
            'id_coleta_parametro_especie' => '=',
            'id_coleta_parametro'         => '=',
            'id_especie'                  => '='
        );
    }
}
