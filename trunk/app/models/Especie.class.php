<?php
class Especie extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'especie';
        $this->nameId   = 'id_especie';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_especie'   => '=',
            'id_parametro' => '=',
            'nome'         => 'LIKE'
        );

        $this->listarAssoc();
    }

    public function listarAssoc() 
    {
        $sth = $this->dbh->prepare("
            SELECT 
                id_especie 
                , nome 
            FROM 
                especie 
        ");

        $sth->execute();
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $this->listaAssoc = $sth->fetchAll();
    }

    public function listarAssocPorParametro($idParametro, $idColeta) 
    {
        $sth = $this->dbh->prepare('
            SELECT
                e.id_especie 
                , e.nome 
                , IF(cp.id_coleta IS NOT NULL, cpe.id_especie, NULL) especie_coleta 
            FROM
                especie e 
                    LEFT JOIN coleta_parametro_especie cpe ON cpe.id_especie = e.id_especie 
                    LEFT JOIN coleta_parametro cp ON cp.id_coleta_parametro = cpe.id_coleta_parametro 
                        AND cp.id_coleta = :idColeta
            WHERE
                e.id_parametro = :idParametro
        ');

        $sth->execute(array(
            ':idColeta'    => $idColeta,
            ':idParametro' => $idParametro
        ));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        $lista = $sth->fetchAll();

        $lista2 = array();
        $lista3 = array();
        foreach ($lista as $item) {
            $lista2[$item['id_especie']] = $item['nome'];
            $lista3[$item['id_especie']] = $item['especie_coleta'];
        }

        return array(
            'select_assoc' => $lista2,
            'selected'     => $lista3
        );
    }
}
