<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=latin1" />
    <title>Estudos Ecol&oacute;gicos nas Lagoas</title>
    <link rel="stylesheet" type="text/css" href="/eelagoas/css/eelagoas.css" />
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
    <script type="text/javascript" src="/eelagoas/js/lib.js"></script>
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
<div>
    <a href="{$site}"><img src="{$site}/images/logo_principal.jpg" id="logo_principal" border="0"></a>
    <p id="nome_sistema">Sistema de cadastro Limnologia UFRJ</p>
</div>
<br/>
<div id="myjquerymenu" class="jquerycssmenu">
    <ul>
        {$menu}
        <li class="left"><a href="#">{$nomeUsuario|upper} > {$nomePerfil|upper}</a>
            <ul>
                <li><a href="{$dir}/GerenciarUsuario/alterarSenha/{$idUsuario}"><img src="{$site}/images/senha.png" border="0"/>Alterar senha</a></li>
                <li><a href="{$dir}/Sessao/sair"><img src="{$site}/images/sair.png" border="0"/>Sair</a></li>
            </ul>
        </li>
    </ul>
<br class="jquerycssmenu" />
</div>
<div id="main">
