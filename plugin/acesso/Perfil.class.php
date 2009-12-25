<?php

require_once 'Modulo.class.php';

class Acesso_Perfil
{
    private $id;
    private $nome;
    private $modulos;

    public function __construct($id = null, $nome = '', array $modulos = array())
    {
        $this->id      = $id;
        $this->nome    = $nome;
        $this->modulos = $modulos;
    }

    public function __set($propriedade, $valor)
    {
        if (isset($this->$propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Perfil não existe.");
        }   
    }

    public function __get($propriedade)
    {
        if (isset($this->$propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Perfil não existe.");
        }
    }
}
?>
