<?php
class Acesso_Acao
{
    private $nome;
    private $valor;

    public function __construct($nome = '', $valor = '')
    {
        $this->nome  = $nome;
        $this->valor = $valor;
    }

    public function __set($propriedade, $valor)
    {
        if (isset($this->$propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Acao não existe.");
        }   
    }

    public function __get($propriedade)
    {
        if (isset($this->$propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Acao não existe.");
        }
    }
}
?>
