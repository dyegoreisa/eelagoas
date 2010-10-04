<?php

require_once 'Base.class.php';

class import_models_coletaParametro extends import_models_base
{
    private $idColetaParametro;
    private $idParametro;
    private $nomeParametro;
    private $valor;
    private $composicao;
    private $especies;

    public function __set($propriedade, $valor)
    {
        if (property_exists($this, $propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("SET: Propriedade {$propriedade} não existe na classe Parametro.");
        }
    }

    public function __get($propriedade) 
    {
        if (property_exists($this, $propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("GET: Propriedade {$propriedade} não existe na classe Parametro.");
        }
    }

    public function salvar($idColeta) 
    {
        $this->idParametro = $this->inserirParametro($this->nomeParametro, $this->composicao);

        $dados = array(
            ':idColeta'    => $idColeta,
            ':idParametro' => $this->idParametro,
            ':valor'       => $this->valor
        );

        $sthSelect = $this->dbh->prepare("
            SELECT id_coleta_parametro
            FROM coleta_parametro
            WHERE id_coleta = :idColeta
                AND id_parametro = :idParametro
                AND valor = :valor
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $coletaParametro = $sthSelect->fetch();
        $this->idColetaParametro = $coletaParametro['id_coleta_parametro'];

        if (empty($this->idColetaParametro)) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO coleta_parametro (
                    id_coleta
                    , id_parametro
                    , valor
                ) 
                VALUES (
                    :idColeta
                    , :idParametro
                    , :valor
                )
            ");
            $ok = $sthInsert->execute($dados);
            if ($ok) {
                $this->idColetaParametro = $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            $this->idColetaParametro = $coletaParametro['id_coleta_parametro'];
        }

    }
}
