<?php
class ItemMenu
{
    private $permissao;
    private $texto;
    private $modulo;
    private $metodo;
    private $acesso;
    private $icone;
    private $itensMenu;

    public function __construct(Permissao $permissao, $idPerfil, $texto, $modulo = null, $metodo = null, array $itensMenu = array(), $icone = '')
    {
        $this->permissao = $permissao;
        $this->texto     = $texto;
        $this->modulo    = $modulo;
        $this->metodo    = $metodo;
        $this->icone     = $icone;
        $this->itensMenu = $itensMenu;

        if (isset($modulo) && isset($metodo)) {
            $this->acesso = $this->permissao->perfilTemAcessoAoMetodo($idPerfil, $this->modulo, $this->metodo);
        } else {
            $this->acesso = 'S';
        }
    }

    public function __set($propriedade, $valor)
    {
        if (isset($this->$propriedade)) {
            $this->$propriedade = $valor;
        } else {
            throw new Exception("ERROR SET: Propriedade '{$propriedade}' da classe 'ItemMenu' não existe.");
        }   
    }

    public function __get($propriedade)
    {
        if (isset($this->$propriedade)) {
            return $this->$propriedade;
        } else {
            throw new Exception("ERROR GET: Propriedade '{$propriedade}' da classe 'ItemMenu' não existe.");
        }
    }
}
?>
