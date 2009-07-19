<?php /* Smarty version 2.6.18, created on 2009-07-09 23:43:34
         compiled from pieces/select_ponto_amostral.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'html_options', 'pieces/select_ponto_amostral.tpl', 3, false),)), $this); ?>
<select name="id_ponto_amostral" id="id_ponto_amostral">
  <option value="-1"> -- [Selecione] -- </option>
  <?php echo smarty_function_html_options(array('options' => $this->_tpl_vars['select_ponto_amostral'],'selected' => -1), $this);?>

</select>
<input type="button" class="novo" alt="ponto_amostral" value="Novo">