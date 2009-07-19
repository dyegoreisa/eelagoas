<?php /* Smarty version 2.6.18, created on 2009-07-09 02:39:29
         compiled from GerenciarCategoria_editar.tpl */ ?>
<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Cadastrar Categoria</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="<?php echo $this->_tpl_vars['categoria']['nome']; ?>
">
  <br/><br/>

  <br/>

  <?php if ($this->_tpl_vars['categoria']['id_categoria'] != ""): ?>
    <input type="hidden" name="id_categoria" value="<?php echo $this->_tpl_vars['categoria']['id_categoria']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>
</fieldset>
