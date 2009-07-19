<?php /* Smarty version 2.6.18, created on 2009-07-09 18:50:30
         compiled from GerenciarUsuario_listar.tpl */ ?>
<fieldset>
  <legend>Lista de usuarios</legend>
  <ul>
    <?php $_from = $this->_tpl_vars['usuarios']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['usuario']):
?>
      <li>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/editar/<?php echo $this->_tpl_vars['usuario']['id_usuario']; ?>
" alt="Altera usuario">[ A ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/excluir/<?php echo $this->_tpl_vars['usuario']['id_usuario']; ?>
" alt="Exclui usuario" class="excluir">[ E ]</a>
        <?php echo $this->_tpl_vars['usuario']['login']; ?>
 - <?php echo $this->_tpl_vars['usuario']['nome']; ?>

      </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</fieldset>