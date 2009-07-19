<?php /* Smarty version 2.6.18, created on 2009-07-09 02:54:32
         compiled from GerenciarParametro_listar.tpl */ ?>
<fieldset>
  <legend>Lista de parametros</legend>
  <ul>
    <?php $_from = $this->_tpl_vars['parametros']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['parametro']):
?>
      <li>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/editar/<?php echo $this->_tpl_vars['parametro']['id_parametro']; ?>
" alt="Altera parametro">[ A ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarParametro/excluir/<?php echo $this->_tpl_vars['parametro']['id_parametro']; ?>
" alt="Exclui parametro" class="excluir">[ E ]</a>
        <?php echo $this->_tpl_vars['parametro']['nome']; ?>

      </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</fieldset>