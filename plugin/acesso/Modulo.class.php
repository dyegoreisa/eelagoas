<?php

require_once 'Acao.class.php';

class Acesso_Modulo
{
    private $nome;
    private $acoes;

    public function __construct($nome = '', array $acoes = array())
    {
        $this->nome = $nome;
        foreach ($acoes as $nome => $valor) {
            $this->acoes[] = new Acesso_Acao($nome, $valor);
        }
    }

    public function __set($propriedade, $valor)
    {
        if (isset($this->$propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Modulo não existe.");
        }   
    }

    public function __get($propriedade)
    {
        if (isset($this->$propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("Propriedade {$propriedade} da classe Acesso_Modulo não existe.");
        }
    }
}
?>
