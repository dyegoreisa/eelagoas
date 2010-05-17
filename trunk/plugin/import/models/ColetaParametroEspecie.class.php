<?php

require_once 'Base.class.php';

class import_models_coletaParametroEspecie extends import_models_base
{
    private $idColetaParametroEspecie;
    private $idEspecie;
    private $nomeEspecie;
    private $quantidade;

    public function __set($propriedade, $valor)
    {
        if (property_exists($this, $propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("SET: Propriedade {$propriedade} não existe classes Especie.");
        }
    }

    public function __get($propriedade) 
    {
        if (property_exists($this, $propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("GET: Propriedade {$propriedade} não existe na classe Especie.");
        }
    }

    public function salvar($idColetaParametro, $idParametro) 
    {
        $this->idEspecie = $this->inserirEspecie($this->nomeEspecie, $idParametro);

        $dados = array(
            'idColetaParametro' => $idColetaParametro,
            'idEspecie'         => $this->idEspecie,
            'quantidade'        => $this->quantidade
        ); 

        $sthSelect = $this->dbh->prepare("
            SELECT id_coleta_parametro_especie
            FROM coleta_parametro_especie
            WHERE id_coleta_parametro = :idColetaParametro
                AND id_especie = :idEspecie
                AND quantidade = :quantidade
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $coletaParametroEspecie = $sthSelect->fetch();
        $this->idColetaParametroEspecie = $coletaParametroEspecie['id_coleta_parametro_especie'];

        if (empty($this->idColetaParametroEspecie)) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO coleta_parametro_especie (
                    id_coleta_parametro
                    , id_especie
                    , quantidade
                ) 
                VALUES (
                    :idColetaParametro
                    , :idEspecie
                    , :quantidade
                )
            ");
            $ok = $sthInsert->execute($dados);
            if ($ok) {
                $this->idColetaParametroEspecie = $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            $this->idColetaParametroEspecie = $coletaParametroEspecie['id_coleta_parametro_especie'];
        }
    }
}
