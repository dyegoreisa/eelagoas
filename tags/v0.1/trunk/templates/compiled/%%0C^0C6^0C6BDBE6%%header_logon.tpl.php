<?php /* Smarty version 2.6.18, created on 2009-07-09 18:43:20
         compiled from header_logon.tpl */ ?>
<html>
<head>
  <title>Estudos Ecol&oacute;gicos nas Lagoas</html>
  <link rel="stylesheet" type="text/css" href="/eelagoas/css/form.css" />
  <link rel="stylesheet" type="text/css" href="/eelagoas/css/jquerycssmenu.css" />
  <?php echo '
  <!--[if lte IE 7]>
  <style type="text/css">
  html .jquerycssmenu{height: 1%;} /*Holly Hack for IE7 and below*/
  </style>
  <![endif]-->
  '; ?>

  <script type="text/javascript" src="/eelagoas/js/jquery-1.3.2.min.js"></script>
  <script type="text/javascript" src="/eelagoas/js/jquery.livequery.js"></script>
  <script type="text/javascript" src="/eelagoas/js/jquery.validate.min.js"></script>
  <script type="text/javascript" src="/eelagoas/js/jquerycssmenu.js"></script>
  <?php echo '
  <script type="text/javascript">
    //droplinemenu.buildmenu("menu")  
  </script>
  '; ?>

</head>
<body>
<div id="myjquerymenu" class="jquerycssmenu">
  <ul>
    <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/Main/run">Principal</a></li>
    <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarColeta/editar">Cadastrar Coleta</a></li>
    <li><a href="#">Gerenciar Lagoa</a>
      <ul>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/editar">Cadastrar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/buscar">Buscar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/listar">Listar</a></li>
      </ul>
    </li>
    <li><a href="#">Gerenciar Categoria</a>
      <ul>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/editar">Cadastrar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/buscar">Buscar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/listar">Listar</a></li>
      </ul>
    </li>
    <li><a href="#">Gerenciar Parametro</a>
      <ul>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/editar">Cadastrar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/buscar">Buscar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/listar">Listar</a></li>
      </ul>
    </li>
    <li><a href="#">Gerenciar Usuario</a>
      <ul>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/editar">Cadastrar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/buscar">Buscar</a></li>
        <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/listar">Listar</a></li>
      </ul>
    </li>
    <li><a href="<?php echo $this->_tpl_vars['dir']; ?>
/Sessao/sair">Sair</a></li>
  </ul>
<br style="clear: left" />
</div>
<div id="main">