<?php

require_once 'ItemMenu.class.php';

class Menu
{
    private $itensMenu;

    public function __construct(Permissao $permissao)
    {
        $this->itensMenu = array(
            new ItemMenu($permissao, 'Cadastrar Coleta', 'GerenciarColeta', 'editar'),
            new ItemMenu($permissao, 'Importar Excel', 'Importador', 'selecionar'),
            new ItemMenu($permissao, 'Relat&oacute;rios', 'Relatorio', 'selecionar'),
            new ItemMenu($permissao, 'Gerenciar', null, null, array(
                new ItemMenu($permissao, 'Projeto', 'GerenciarProjeto', 'listar', array(), 'projeto.png'),
                new ItemMenu($permissao, 'Lagoa', 'GerenciarLagoa', 'listar', array(), 'lagoa.png'),
                new ItemMenu($permissao, 'Ponto amostral', 'GerenciarPontoAmostral', 'listar', array(), 'pontoamostral.png'),
                new ItemMenu($permissao, 'Categoria', 'GerenciarCategoria', 'listar', array(), 'categoria.png'),
                new ItemMenu($permissao, 'Par&acirc;metro', 'GerenciarParametro', 'listar', array(), 'parametro.png'),
                new ItemMenu($permissao, 'Esp&eacute;cie', 'GerenciarEspecie', 'listar', array(), 'especie.png'),
                new ItemMenu($permissao, 'Usu&aacute;rio', 'GerenciarUsuario', 'listar', array(), 'usuario.png'),
                new ItemMenu($permissao, 'Perfil', 'GerenciarPerfil', 'listar', array(), 'perfil.png')
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
                            $htmlSubItem .= '<li><a href="' . $dir . '/' . $subItem->modulo . '/' . $subItem->metodo .'">'
                                          . '<img src="' . R_SITE . '/images/' . $subItem->icone . '" border="0" alt=""/>'
                                          . $subItem->texto . '</a></li>';
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
