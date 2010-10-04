<?php
class Permissao
{
    private $dbh;
    private $cache;
    private $perfil;

    public function __construct()
    {
        $this->connect();
        $this->cache = TRUE;
        $this->perfil = $_SESSION[$_SESSION['SID']]['idPerfil'];
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
    
    public function semCache($cache = FALSE)
    {
        $this->cache = $cache;
    }

    public function temAcesso($modulo, $acao)
    {
        $rs = $this->selectPermissao($modulo, $acao);
        if ($rs) {
            return $rs['acesso'];
        } else {
            return 'N';
        }
    }

    private function selectPermissao($modulo, $acao)
    {
        if ($this->cache && isset($_SESSION[$_SESSION['SID']]['permissoes'][$this->perfil][$acao][$modulo])) {
            $resultado = $_SESSION[$_SESSION['SID']]['permissoes'][$this->perfil][$acao][$modulo];
        } else {
            $sth = $this->dbh->prepare('
                SELECT * 
                FROM acao 
                WHERE id_perfil = :idPerfil 
                    AND nome = :acao
                    AND modulo = :modulo 
            ');

            $sth->execute(array(
                'idPerfil' => $this->perfil,
                'acao'     => $acao,
                'modulo'   => $modulo
            ));

            $sth->setFetchMode(PDO::FETCH_ASSOC);
            $resultado = $_SESSION[$_SESSION['SID']]['permissoes'][$this->perfil][$acao][$modulo] = $sth->fetch();
        }
        
        return $resultado;
    }

    public function perfilTemAcessoAoMetodo($modulo, $metodo)
    {
        $acesso = $this->temAcesso($modulo, $metodo);

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

    public function getListaPermitida(array $lista)
    {
        $listaPermitida = array();
        foreach ($lista as $item) {
            if ($this->perfilTemAcessoAoMetodo($item['modulo'], $item['metodo']) == 'S') {
                $listaPermitida[] = $item;
            }
        }
        return $listaPermitida;
    }
}
?>
