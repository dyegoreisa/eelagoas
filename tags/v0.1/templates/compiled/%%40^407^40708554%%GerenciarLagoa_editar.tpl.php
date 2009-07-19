<?php /* Smarty version 2.6.18, created on 2009-07-09 02:29:20
         compiled from GerenciarLagoa_editar.tpl */ ?>
<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Cadastrar Lagoa</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="<?php echo $this->_tpl_vars['lagoa']['nome']; ?>
">
  <br/><br/>

  <br/>

  <?php if ($this->_tpl_vars['lagoa']['id_lagoa'] != ""): ?>
    <input type="hidden" name="id_lagoa" value="<?php echo $this->_tpl_vars['lagoa']['id_lagoa']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>
</fieldset>
