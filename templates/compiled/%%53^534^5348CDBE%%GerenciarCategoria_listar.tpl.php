<?php /* Smarty version 2.6.18, created on 2009-07-09 02:43:36
         compiled from GerenciarCategoria_listar.tpl */ ?>
<fieldset>
  <legend>Lista de categorias</legend>
  <ul>
    <?php $_from = $this->_tpl_vars['categorias']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['categoria']):
?>
      <li>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/editar/<?php echo $this->_tpl_vars['categoria']['id_categoria']; ?>
" alt="Altera categoria">[ A ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarCategoria/excluir/<?php echo $this->_tpl_vars['categoria']['id_categoria']; ?>
" alt="Exclui categoria" class="excluir">[ E ]</a>
        <?php echo $this->_tpl_vars['categoria']['nome']; ?>

      </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</fieldset>