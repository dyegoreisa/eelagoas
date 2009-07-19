<?php /* Smarty version 2.6.18, created on 2009-07-09 02:53:30
         compiled from GerenciarParametro_editar.tpl */ ?>
<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Cadastrar Parametro</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="<?php echo $this->_tpl_vars['parametro']['nome']; ?>
">
  <br/><br/>

  <br/>

  <?php if ($this->_tpl_vars['parametro']['id_parametro'] != ""): ?>
    <input type="hidden" name="id_parametro" value="<?php echo $this->_tpl_vars['parametro']['id_parametro']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>
</fieldset>
