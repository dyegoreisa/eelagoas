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

    public function especiesSelecionadas($idColeta, $idParametro, $soId = false) {
        $sth = $this->dbh->prepare("
            SELECT
                e.id_especie,
                e.nome 
            FROM
                coleta_parametro_especie cpe 
                JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro 
                JOIN especie e ON cpe.id_especie = e.id_especie 
            WHERE
                cp.id_parametro = :id_parametro AND cp.id_coleta = :id_coleta
        ");

        $sth->execute(array(
            ':id_parametro' => $idParametro,
            ':id_coleta'    => $idColeta
        ));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        if ($soId) {
            foreach ($lista as &$val) {
                $val = $val['id_especie'];
            }
        }

        return $lista;
    }
}
