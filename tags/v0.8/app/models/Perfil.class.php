<?php
class Perfil extends BaseModel 
{
    private $listaAssoc;

    public function __construct( $dbh ) 
    {
        parent::__construct( $dbh );

        $this->table    = 'perfil';
        $this->nameId   = 'id_perfil';
        $this->nameDesc = 'nome';
        $this->data     = array();
        $this->dataAll  = array();
        $this->search   = array(
            'id_perfil'   => '=',
            'nome'         => 'LIKE'
        );
    }

    private function selectPermissao($modulo, $acao)
    {
        $sth = $this->dbh->prepare('
            SELECT * 
            FROM acao 
            WHERE id_perfil = :idPerfil 
                AND nome = :acao
                AND modulo = :modulo 
        ');

        $sth->execute(array(
            'idPerfil' => $this->getId(),
            'acao'     => $acao,
            'modulo'   => $modulo
        ));

        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetch();
    }

    private function updatePermissao($modulo, $acao, $acesso)
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
            ':idPerfil' => $this->getId(),
            ':acao'     => $acao,
            ':modulo'   => $modulo
        ));
    }

    private function insertPermissao($modulo, $acao, $acesso)
    {
        $sth = $this->dbh->prepare('
            INSERT INTO acao (id_perfil, nome, modulo, acesso)
            VALUES (:idPerfil, :acao, :modulo, :acesso)
        ');

        return $sth->execute(array(
            ':idPerfil' => $this->getId(),
            ':acao'     => $acao,
            ':modulo'   => $modulo,
            ':acesso'   => $acesso
        ));
    }

    public function pegaPermissoes()
    {
        $sth = $this->dbh->prepare('
            SELECT * 
            FROM acao 
            WHERE id_perfil = :idPerfil 
            ORDER BY modulo, nome
        ');

        $sth->execute(array('idPerfil' => $this->getId));
        $sth->setFetchMode(PDO::FETCH_ASSOC);
        return $sth->fetchAll();
    }

    public function carregarClasses($dir, $acessaBanco = false)
    {
        $classes = array();
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $tmp = array();
                if (preg_match('/^Ctrl_\w+\.class\.php$/', $file)) {
                    $tmp['class']    = str_replace('.class.php', '', $file);
                    $moduloCamelCase = str_replace('Ctrl_', '', $tmp['class']);
                    $tmp['modulo']   = preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $moduloCamelCase);
                    $tmp['metodos']  = $this->metodosInterface($tmp['class'], $moduloCamelCase, $acessaBanco);
                    if (count($tmp['metodos']) > 0) {
                        $classes[$tmp['class']] = $tmp;
                    }
                }
            }
            closedir($dh);
        }
        return $classes;
    }

    private function metodosInterface($class, $modulo, $acessaBanco = false)
    {
        if($acessaBanco) {
            $permissao = new Permissao();
        }
        $refClass  = new ReflectionClass($class);
        $interface = $refClass->getInterfaces();
        $tmp = array();
        if (!empty($interface)) {
            $nameInterface = key($interface);
            $refInterface  = new ReflectionClass($nameInterface);
            $methods       = $refInterface->getMethods();
            foreach ($methods as $method) {
                if($acessaBanco) {
                    $tmp[$method->name] = $permissao->temAcesso($this->getId(), $modulo, $method->name);
                } else {
                    $tmp[$method->name] = 'N';
                }
            }
        }
        return $tmp;
    }

    public function perfilTemAcessoAoMetodo($modulo, $metodo)
    {
        $permissao = new Permissao();
        $acesso = $permissao->temAcesso($this->getId(), $modulo, $metodo);

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

    private function prepararPermissoesParaSalvar(array $permissoes)
    {
        ksort($permissoes);

        $permissoesFiltradas = array();
        $moduloAtual = '';
        $acoes = '';
        foreach ($permissoes as $key => $permissao) {
            // Explode string no formato "Ctrl_GerenciarUsuario_editar"
            $partes = explode('_', $key); 

            if ($partes[1] != $moduloAtual) {
                if ($moduloAtual != '') {
                    $permissoesFiltradas[] = array('modulo' => $moduloAtual, 'acoes' => $acoes);
                }
                $moduloAtual = $partes[1];
                unset($acoes);
                $acoes = array($partes[2] => $permissao);
            } else {
                $acoes[$partes[2]] = $permissao;
            }
        }
        $permissoesFiltradas[] = array('modulo' => $moduloAtual, 'acoes' => $acoes);
        return $permissoesFiltradas;
    }
    
    public function salvarPermissoesDoPerfil($permissoesBrutas)
    {
        $permissoes = $this->prepararPermissoesParaSalvar($permissoesBrutas);
        foreach ($permissoes as $permissao) {
            foreach ($permissao['acoes'] as $nomeAcao => $valorAcao) {
                $acesso = $this->selectPermissao($permissao['modulo'], $nomeAcao);
                if ($acesso) {
                    if ($acesso['acesso'] != $valorAcao) {
                        $this->updatePermissao($permissao['modulo'], $nomeAcao, $valorAcao);
                    }
                } else {
                    $this->insertPermissao($permissao['modulo'], $nomeAcao, $valorAcao);
                }
            }
        }
    }
}
?>
