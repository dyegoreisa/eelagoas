<?php

require_once 'ItemMenu.class.php';

class Menu
{
    private $itensMenu;

    public function __construct(Permissao $permissao, $idPerfil)
    {
        $this->itensMenu = array(
            new ItemMenu($permissao, $idPerfil, 'Cadastrar Coleta', 'GerenciarColeta', 'editar'),
            new ItemMenu($permissao, $idPerfil, 'Relat&oacute;rios', 'Relatorio', 'search'),
            new ItemMenu($permissao, $idPerfil, 'Gerenciar', null, null, array(
                new ItemMenu($permissao, $idPerfil, 'Projeto', 'GerenciarProjeto', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Lagoa', 'GerenciarLagoa', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Categoria', 'GerenciarCategoria', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Par&acirc;metro', 'GerenciarParametro', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Esp&eacute;cie', 'GerenciarEspecie', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Usu&aacute;rio', 'GerenciarUsuario', 'listar'),
                new ItemMenu($permissao, $idPerfil, 'Perfil', 'GerenciarPerfil', 'listar')
            ))
        );
    }

    public function getMenu($dir)
    {
        $html = '';
        foreach ($this->itensMenu as $item) {
            if ($item->acesso == 'S') {
                if (count($item->itensMenu) > 0) {
                    $htmlSubItem = '';
                    foreach ($item->itensMenu as $subItem) {
                        if ($subItem->acesso == 'S') {
                            $htmlSubItem .= '<li><a href="' . $dir . '/' . $subItem->modulo . '/' . $subItem->metodo .'">' . $subItem->texto . '</a></li>';
                        }
                    }
                    if (!empty($htmlSubItem)) {
                        $html .= '<li><a href="#">' . $item->texto . '</a><ul>';
                        $html .= $htmlSubItem;
                        $html .= '</ul>';
                    }
                } else {
                    $html .= '<li><a href="' . $dir . '/' . $item->modulo . '/' . $item->metodo .'">' . $item->texto . '</a></li>';
                }
            }
        }

        return $html;
    }
}
