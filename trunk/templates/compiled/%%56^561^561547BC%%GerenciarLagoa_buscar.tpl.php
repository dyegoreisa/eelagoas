<?php /* Smarty version 2.6.18, created on 2009-07-08 01:04:43
         compiled from GerenciarLagoa_buscar.tpl */ ?>
<fieldset>
  <legend>Buscar Lagoa</legend>
  <?php if ($this->_tpl_vars['msg'] != ""): ?>
    <p><?php echo $this->_tpl_vars['msg']; ?>
</p>
  <?php endif; ?>
  <form action="" method="GET">
    <input type="text" name="dados">
    <input type="submit" value="Buscar">
  </form>
</fieldset>