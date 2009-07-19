<?php /* Smarty version 2.6.18, created on 2009-07-09 19:47:59
         compiled from GerenciarLagoa_listar.tpl */ ?>
<fieldset>
  <legend>Lista de lagoas</legend>
  <ul>
    <?php $_from = $this->_tpl_vars['lagoas']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['lagoa']):
?>
      <li>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarColeta/buscar/<?php echo $this->_tpl_vars['lagoa']['id_lagoa']; ?>
" alt="Lista coletas">[ C ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarPontoAmostral/buscar/<?php echo $this->_tpl_vars['lagoa']['id_lagoa']; ?>
" alt="Lista pontos amostrais">[ P ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/editar/<?php echo $this->_tpl_vars['lagoa']['id_lagoa']; ?>
" alt="Altera lagoa">[ A ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarLagoa/excluir/<?php echo $this->_tpl_vars['lagoa']['id_lagoa']; ?>
" alt="Exclui lagoa" class="excluir">[ E ]</a>
        <?php echo $this->_tpl_vars['lagoa']['nome']; ?>

      </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</fieldset>