<?php
class Permissao
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
            print "Error!: " . $e->getMessage() . "<hr/>";
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

    public function perfilTemAcessoAoMetodo($idPerfil, $modulo, $metodo)
    {
        $acesso = $this->temAcesso($idPerfil, $modulo, $metodo);

        $refClass = new ReflectionClass('Ctrl_' . $modulo);
        $interface = $refClass->getInterfaces();
        if (!empty($interface)) {
            $nameInterface = key($interface);
            $refInterface  = new ReflectionClass($nameInterface);
            $methods       = $refInterface->getMethods();
            foreach ($methods as $method) {
                if ($method->name == $metodo) {
                    return $acesso;
                }
            }
            return 'S';
        } else {
            return 'S';
        }
    }

    public function getListaPermitida($idPerfil, array $lista)
    {
        $listaPermitida = array();
        foreach ($lista as $item) {
            if ($this->perfilTemAcessoAoMetodo($idPerfil, $item['modulo'], $item['metodo']) == 'S') {
                $listaPermitida[] = $item;
            }
        }
        return $listaPermitida;
    }
}
?>
