<?php

require_once 'Perfil.class.php';

final class Acesso_Banco
{
    private $dbh;

    public function __construct()
    {
        $this->connect();
    }

    private function connect()
    {
        try {
            $this->dbh = new PDO(DB_DRIVER . ':host=' . DB_HOST . ';dbname=' . DB_NAME, 
                                 DB_USER, 
                                 DB_PASSWD,
                                 array(PDO::ATTR_PERSISTENT => true));
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die;
        }
    }

    public function temAcesso($idPerfil, $modulo, $acao)
    {
        $rs = $this->selectPermissao($idPerfil, $modulo, $acao);
        if ($rs) {
            return $rs['acesso'];
        } else {
            return 'N';
        }
    }

    private function selectPermissao($idPerfil, $modulo, $acao)
    {
        $sth = $this->dbh->prepare('
            SELECT * 
            FROM acao 
            WHERE id_perfil = :idPerfil 
                AND nome = :acao
                AND modulo = :modulo 
        ');

        $sth->execute(array(
            'idPerfil' => $idPerfil,
            'acao'     => $acao,
            'modulo'   => $modulo
        ));

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }

    private function updatePermissao($idPerfil, $modulo, $acao, $acesso)
    {
        $sth = $this->dbh->prepare('
            UPDATE acao
            SET acesso = :acesso
            WHERE id_perfil = :idPerfil
                AND nome = :acao
                AND modulo = :modulo
        ');
        
        return $sth->execute(array(
            ':acesso'   => $acesso,
            ':idPerfil' => $idPerfil,
            ':acao'     => $acao,
            ':modulo'   => $modulo
        ));
    }

    private function insertPermissao($idPerfil, $modulo, $acao, $acesso)
    {
        $sth = $this->dbh->prepare('
            INSERT INTO acao (id_perfil, nome, modulo, acesso)
            VALUES (:idPerfil, :acao, :modulo, :acesso)
        ');

        return $sth->execute(array(
            ':idPerfil' => $idPerfil,
            ':acao'     => $acao,
            ':modulo'   => $modulo,
            ':acesso'   => $acesso
        ));
    }

    public function salvarPermissoesPerfil(Acesso_Perfil $perfil)
    {
        foreach ($perfil->modulos as $modulo) {
            $acoes = $modulo->acoes;
            foreach ($acoes as $acao) {
                $permissao = $this->selectPermissao($perfil->id, $modulo->nome, $acao->nome);
                if ($permissao) {
                    if ($permissao['acesso'] != $acao->valor) {
                        $this->updatePermissao($perfil->id, $modulo->nome, $acao->nome, $acao->valor);
                    }
                } else {
                    $this->insertPermissao($perfil->id, $modulo->nome, $acao->nome, $acao->valor);
                }
            }
        }
    }
}
?>
