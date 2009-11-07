<html>
<head>
    <title>Estudos Ecol&oacute;gicos nas Lagoas</title>
    <link rel="stylesheet" type="text/css" href="/eelagoas/css/form.css" />
    <link rel="stylesheet" type="text/css" href="/eelagoas/css/jquerycssmenu.css" />
    {literal}
    <!--[if lte IE 7]>
    <style type="text/css">
    html .jquerycssmenu{height: 1%;} /*Holly Hack for IE7 and below*/
    </style>
    <![endif]-->
    {/literal}
    <script type="text/javascript" src="/eelagoas/js/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="/eelagoas/js/jquery.livequery.js"></script>
    <script type="text/javascript" src="/eelagoas/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/eelagoas/js/jquerycssmenu.js"></script>
    {literal}
    <script>
        $(document).ready(function() {
            $('.excluir').click(function () {
                if (false == confirm('Deseja excluir este registro?')) {
                    return false;
                }
            });
        });
    </script>
    {/literal}
</head>
<body>
<div id="myjquerymenu" class="jquerycssmenu">
    <ul>
        <li><a href="{$dir}/GerenciarColeta/editar">Cadastrar Coleta</a></li>
        <li><a href="{$dir}/Relatorio/reportInterface">Relat&oacute;rios</a></li>
        <li><a href="#">Gerenciar</a>
            <ul>
                <li><a href="#">Projeto</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarProjeto/editar">cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarProjeto/buscar">buscar</a></li>
                        <li><a href="{$dir}/GerenciarProjeto/listar">listar</a></li>
                    </ul>
                </li>
                <li><a href="#">Lagoa</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarLagoa/editar">cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarLagoa/buscar">buscar</a></li>
                        <li><a href="{$dir}/GerenciarLagoa/listar">listar</a></li>
                    </ul>
                </li>
                <li><a href="#">Categoria</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarCategoria/editar">Cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarCategoria/buscar">Buscar</a></li>
                        <li><a href="{$dir}/GerenciarCategoria/listar">Listar</a></li>
                    </ul>
                </li>
                <li><a href="#">Parametro</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarParametro/editar">Cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarParametro/buscar">Buscar</a></li>
                        <li><a href="{$dir}/GerenciarParametro/listar">Listar</a></li>
                    </ul>
                </li>
                <li><a href="#">Especie</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarEspecie/editar">Cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarEspecie/buscar">Buscar</a></li>
                        <li><a href="{$dir}/GerenciarEspecie/listar">Listar</a></li>
                    </ul>
                </li>
                <li><a href="#">Usuario</a>
                    <ul>
                        <li><a href="{$dir}/GerenciarUsuario/editar">Cadastrar</a></li>
                        <li><a href="{$dir}/GerenciarUsuario/buscar">Buscar</a></li>
                        <li><a href="{$dir}/GerenciarUsuario/listar">Listar</a></li>
                    </ul>
                </li>
            </ul>
        <li class="left"><a href="{$dir}/Sessao/sair">Sair</a></li>
    </ul>
<br style="clear: left" />
</div>
<div id="main">