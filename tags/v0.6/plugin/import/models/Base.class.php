<?php
abstract class import_models_base
{
    protected $dbh;

    public function __construct(PDO $dbh)
    {
        $this->dbh = $dbh;
    }

    private function pegarId($tabela, $valor)
    {
        $sth = $this->dbh->prepare("
            SELECT id_{$tabela}
            FROM {$tabela}
            WHERE nome = :valor;
        ");

        $sth->execute(array(':valor' => $valor));

        $sth->setFetchMode(PDO::FETCH_ASSOC);

        $lagoa = $sth->fetch();
    
        if (empty($lagoa["id_{$tabela}"])) {
            return false;
        } else {
            return $lagoa["id_{$tabela}"];
        }
    }

    protected function inserirLagoa($idProjeto, $nomeLagoa)
    {
        $dados = array(
            ':idProjeto' => $idProjeto,
            ':nomeLagoa' => $nomeLagoa
        );

        $sthSelect = $this->dbh->prepare("
            SELECT id_lagoa
            FROM lagoa
            WHERE nome = :nomeLagoa
                AND id_projeto = :idProjeto
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $lagoa = $sthSelect->fetch();

        if (empty($lagoa['id_lagoa'])) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO lagoa(id_projeto, nome) 
                VALUES (:idProjeto, :nomeLagoa)
            ");
            $ok = $sthInsert->execute($dados);

            if ($ok) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            return $lagoa['id_lagoa'];
        }
    }

    protected function inserirPontoAmostral($idLagoa, $nomePontoAmostral)
    {
        $dados = array(
            ':idLagoa'           => $idLagoa,
            ':nomePontoAmostral' => $nomePontoAmostral
        );

        $sthSelect = $this->dbh->prepare("
            SELECT id_ponto_amostral
            FROM ponto_amostral
            WHERE nome = :nomePontoAmostral
                AND id_lagoa = :idLagoa
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $pontoAmostral = $sthSelect->fetch();

        if (empty($pontoAmostral['id_ponto_amostral'])) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO ponto_amostral(id_lagoa, nome) 
                VALUES (:idLagoa, :nomePontoAmostral)
            ");

            $ok = $sthInsert->execute($dados);

            if ($ok) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            return $pontoAmostral['id_ponto_amostral'];
        }
    }

    protected function inserirCategoria($nomeCategoria)
    {
        $dados = array(':nomeCategoria' => $nomeCategoria);

        $sthSelect = $this->dbh->prepare("
            SELECT id_categoria
            FROM categoria
            WHERE nome = :nomeCategoria
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $categoria = $sthSelect->fetch();

        if (empty($categoria['id_categoria'])) {
            $sth = $this->dbh->prepare("
                INSERT INTO categoria(nome) 
                VALUES (:nomeCategoria)
            ");

            $ok = $sth->execute($dados);

            if ($ok) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            return $categoria['id_categoria'];
        }
    }

    protected function inserirParametro($nomeParametro, $composicao)
    {
        $dados = array(
            ':nomeParametro' => $nomeParametro,
            ':composicao'    => (empty($composicao)) ? 0 : 1
        );

        $sthSelect = $this->dbh->prepare("
            SELECT id_parametro
            FROM parametro
            WHERE nome = :nomeParametro
                AND composicao = :composicao
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $parametro = $sthSelect->fetch();

        if (empty($parametro['id_parametro'])) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO parametro(nome, composicao) 
                VALUES (:nomeParametro, :composicao)
            ");
            $ok = $sthInsert->execute($dados);

            if ($ok) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            return $parametro['id_parametro'];
        }
    }

    protected function inserirEspecie($nomeEspecie, $idParametro)
    {
        $dados = array(
            ':nomeEspecie' => $nomeEspecie,
            ':idParametro' => $idParametro
        );

        $sthSelect = $this->dbh->prepare("
            SELECT id_especie
            FROM especie
            WHERE nome = :nomeEspecie
                AND id_parametro = :idParametro
        ");
        $sthSelect->execute($dados);
        $sthSelect->setFetchMode(PDO::FETCH_ASSOC);
        $especie = $sthSelect->fetch();

        if (empty($especie['id_especie'])) {
            $sthInsert = $this->dbh->prepare("
                INSERT INTO especie(nome, id_parametro) 
                VALUES (:nomeEspecie, :idParametro)
            ");
            $ok = $sthInsert->execute($dados);

            if ($ok) {
                return $this->dbh->lastInsertId();
            } else {
                return false;
            }
        } else {
            return $especie['id_especie'];
        }
    }
}

