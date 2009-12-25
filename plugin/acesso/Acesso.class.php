<?php

require_once 'Banco.class.php';
require_once 'Perfil.class.php';

class Acesso_Acesso
{
    public function carregarClasses($dir, $idPerfil = false)
    {
        $classes = array();
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                $tmp = array();
                if (preg_match('/^Ctrl_\w+\.class\.php$/', $file)) {
                    $tmp['class']    = str_replace('.class.php', '', $file);
                    $moduloCamelCase = str_replace('Ctrl_', '', $tmp['class']);
                    $tmp['modulo']   = preg_replace('/(?<=[a-z])(?=[A-Z])/', ' ', $moduloCamelCase);
                    $tmp['metodos']  = $this->metodosInterface($tmp['class'], $idPerfil, $moduloCamelCase);
                    if (count($tmp['metodos']) > 0) {
                        $classes[$tmp['class']] = $tmp;
                    }
                }
            }
            closedir($dh);
        }
        return $classes;
    }

    private function metodosInterface($class, $idPerfil, $modulo)
    {
        $banco = new Acesso_Banco();
        $refClass  = new ReflectionClass($class);
        $interface = $refClass->getInterfaces();
        $tmp = array();
        if (!empty($interface)) {
            $nameInterface = key($interface);
            $refInterface  = new ReflectionClass($nameInterface);
            $methods       = $refInterface->getMethods();
            foreach ($methods as $method) {
                $tmp[$method->name] = $banco->temAcesso($idPerfil, $modulo, $method->name);
            }
        }
        return $tmp;
    }

    public function salvarPermissoesDoPerfil($idPerfil, $nomePerfil, $permissoesBrutas)
    {
        $permissoes = $this->prepararPermissoesParaSalvar($permissoesBrutas);
        $perfil     = new Acesso_Perfil($idPerfil, $nomePerfil, $permissoes);
        $banco      = new Acesso_Banco();
        $banco->salvarPermissoesPerfil($perfil);
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
                    $permissoesFiltradas[] = new Acesso_Modulo($moduloAtual, $acoes);
                }
                $moduloAtual = $partes[1];
                unset($acoes);
                $acoes = array($partes[2] => $permissao);
            } else {
                $acoes[$partes[2]] = $permissao;
            }
        }
        return $permissoesFiltradas;
    }
    
    public function perfilTemAcessoAoMetodo($idPerfil, $modulo, $metodo)
    {
        $banco = new Acesso_Banco();
        $acesso = $banco->temAcesso($idPerfil, $modulo, $metodo);

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
}
?>
