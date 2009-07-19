<?php /* Smarty version 2.6.20, created on 2009-07-15 22:53:43
         compiled from GerenciarColeta_listar.tpl */ ?>
<fieldset>
  <legend>Lista de coletas da lagoa <?php echo $this->_tpl_vars['lagoa']['nome']; ?>
</legend>
  <?php $_from = $this->_tpl_vars['coletas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['coleta']):
?>
    <li>
      <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarColeta/editar/<?php echo $this->_tpl_vars['coleta']['id_coleta']; ?>
">[ A ]</a>
      <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarColeta/excluir/<?php echo $this->_tpl_vars['coleta']['id_coleta']; ?>
">[ E ]</a>
      <?php echo $this->_tpl_vars['coleta']['data']; ?>

    </li>
  <?php endforeach; endif; unset($_from); ?>
</fieldset>