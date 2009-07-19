<?php /* Smarty version 2.6.20, created on 2009-07-16 02:28:15
         compiled from GerenciarColeta_editar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'GerenciarColeta_editar.tpl', 103, false),)), $this); ?>
<?php echo '
  <script type="text/javascript" language="javascript">
  '; ?>
 dir = '<?php echo $this->_tpl_vars['dir']; ?>
'; <?php echo '
    $(document).ready(function () {

      $(".novo").livequery( \'click\', function(e) {
        seletor = $(this).attr(\'alt\');

        esconder = "#" + seletor + "_inserir";
        mostrar = "#" + seletor + "_selecionar";
        desativar = "select[name=id_" + seletor + "]";
        ativar = "input[name=nome_" + seletor + "]";

        $(esconder).removeClass("escondido");
        $(mostrar).addClass("escondido");
        $(desativar).attr("disabled","disabled");
        $(ativar).attr("disabled","");
      });

      $(".cancelar").livequery( \'click\', function(e) {
        seletor = $(this).attr(\'alt\');

        esconder = "#" + seletor + "_selecionar";
        mostrar = "#" + seletor + "_inserir";
        desativar = "input[name=nome_" + seletor + "]";
        ativar = "select[name=id_" + seletor + "]";

        $(esconder).removeClass("escondido");
        $(mostrar).addClass("escondido");
        $(desativar).val(\'\');
        $(desativar).attr("disabled","disabled");
        $(ativar).attr("disabled","");
      });

      $(".novo_item").livequery( \'click\', function(e) {
        i++;
        conteudo = $("#parametro_inserir").html();

        conteudo += \'<span id="parametro\' + i + \'"><br>\'
                 + \'<input type="text" name="nome_parametros[]" value="">\'
                 + \'<div class="campos_parametros">\'
                 + \'<label>N&iacute;vel: <input type="text" name="nivel_novo[]" size="10"></label>\'
                 + \'<br>\'
                 + \'<label>Valor: <input type="text" name="valor_novo[]" size="10"></label>\'
                 + \'<br />\'
                 + \'</div>\'
                 + \'<input type="button" class="cancelar_item" alt="parametro\' + i + \'" value="Cancelar">\'
                 + \'</span>\';
        $("#parametro_inserir").html( conteudo );
      });

      $(".cancelar_item").livequery( \'click\', function(e) {
        parametro = $(this).attr(\'alt\');

        $("#" + parametro ).remove();
      });


      $("#id_lagoa").change( function () {
        $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarSelect/" + $(this).val() );
      });

      $(":checkbox").click( function() {
        campos = $(this).attr(\'alt\');

        if( $(this).attr(\'checked\') ) {
          $("#" + campos).removeClass(\'escondido\');
          $("#n_" + campos).attr("disabled","");
          $("#v_" + campos).attr("disabled","");
        } else {
          $("#" + campos).addClass(\'escondido\');
          $("#n_" + campos).attr("disabled","disabled");
          $("#v_" + campos).attr("disabled","disabled");
        }
      });

    });

    i = 0;
  </script>
  <style>
    .escondido{
      display: none;
    }
    .campos_parametros{
      padding-left: 20px;
    }
  </style>
'; ?>

<fieldset>
<legend>Cadastrar Coleta</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarColeta/salvar" method="POST" class="cmxform">

  <label for="data">Data da coleta:</label><br/>
  <input type="text" name="data" id="data" value="<?php echo $this->_tpl_vars['coleta']['data']; ?>
">
  <br/><br/>

  <label for="nome_lagoa">Lagoa:</label><br/>
  <span id="lagoa_selecionar">
    <select name="id_lagoa" id="id_lagoa">
      <option value="-1"> -- [Selecione] -- </option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['select_lagoa'],'selected' => $this->_tpl_vars['coleta']['id_lagoa']), $this);?>

    </select>
    <input type="button" class="novo" alt="lagoa" value="Novo">
  </span>
  <span id="lagoa_inserir" class="escondido">
    <input type="text" name="nome_lagoa" id="nome_lagoa" value="" disabled>
    <input type="button" class="cancelar" alt="lagoa" value="Cancelar">
  </span>
  <br/><br/>

  <label for="nome_ponto_amostral">Ponto amostral:</label><br/>
  <span id="ponto_amostral_selecionar">
    <select name="id_ponto_amostral" id="id_ponto_amostral">
      <option value="-1"> -- [Selecione] -- </option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['select_ponto_amostral'],'selected' => $this->_tpl_vars['coleta']['id_ponto_amostral']), $this);?>

    </select>
    <input id="button_ponto_amostral" type="button" class="novo" alt="ponto_amostral" value="Novo">
  </span>
  <span id="ponto_amostral_inserir" class="escondido">
    <input type="text" name="nome_ponto_amostral" id="nome_ponto_amostral" value="" disabled>
    <input type="button" class="cancelar" alt="ponto_amostral" value="Cancelar">
  </span>
  <br/><br/>

  <label for="nome_categoria">Categoria:</label><br/>
  <span id="categoria_selecionar">
    <select name="id_categoria" id="id_categoria">
      <option value="-1"> -- [Selecione] -- </option>
      <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['select_categoria'],'selected' => $this->_tpl_vars['coleta']['id_categoria']), $this);?>

    </select>
    <input type="button" class="novo" alt="categoria" value="Novo">
  </span>
  <span id="categoria_inserir" class="escondido">
    <input type="text" name="nome_categoria" id="nome_categoria" value="" disabled>
    <input type="button" class="cancelar" alt="categoria" value="Cancelar">
  </span>
  <br/><br/>

  <fieldset>
    <legend>Parametro:</legend>

    <?php $_from = $this->_tpl_vars['select_parametro']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id_parametro'] => $this->_tpl_vars['parametro']):
?>

      <label>
        <input type="checkbox" name="id_parametros[]" value="<?php echo $this->_tpl_vars['id_parametro']; ?>
" alt="valor<?php echo $this->_tpl_vars['id_parametro']; ?>
" <?php if ($this->_tpl_vars['parametro']['id_coleta_parametro'] != ""): ?> checked <?php endif; ?> /><?php echo $this->_tpl_vars['parametro']['nome']; ?>

      </label>
      <br>

      <?php if ($this->_tpl_vars['parametro']['id_coleta_parametro'] != ""): ?>
        <div class="campos_parametros" id="valor<?php echo $this->_tpl_vars['id_parametro']; ?>
">
          <label>N&iacute;vel: <input type="text" id="n_valor<?php echo $this->_tpl_vars['id_parametro']; ?>
" name="nivel[]" size="10" value="<?php echo $this->_tpl_vars['parametro']['valor']; ?>
"></label>
          <br>
          <label>Valor: <input type="text" id="v_valor<?php echo $this->_tpl_vars['id_parametro']; ?>
" name="valor[]" size="10" value="<?php echo $this->_tpl_vars['parametro']['nivel']; ?>
"></label>
          <br />
        </div>
      <?php else: ?>
        <div class="campos_parametros escondido" id="valor<?php echo $this->_tpl_vars['id_parametro']; ?>
">
          <label>N&iacute;vel: <input type="text" id="n_valor<?php echo $this->_tpl_vars['id_parametro']; ?>
" name="nivel[]" size="10" disabled></label>
          <br>
          <label>Valor: <input type="text" id="v_valor<?php echo $this->_tpl_vars['id_parametro']; ?>
" name="valor[]" size="10" disabled></label>
          <br />
        </div>
      <?php endif; ?>

    <?php endforeach; endif; unset($_from); ?>

    <input type="button" class="novo_item" alt="parametro" value="Novo">

    <span id="parametro_inserir">
       
    </span>

  </fieldset>
  <br/>
  
  <?php if ($this->_tpl_vars['coleta']['id_coleta'] != ""): ?>
    <input type="hidden" name="id_coleta" value="<?php echo $this->_tpl_vars['coleta']['id_coleta']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>

</fieldset>