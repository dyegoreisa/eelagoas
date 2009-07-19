<?php /* Smarty version 2.6.20, created on 2009-07-15 22:45:35
         compiled from GerenciarPontoAmostral_listar.tpl */ ?>
<fieldset>
  <legend>Lista de pontos amostrais <?php echo $this->_tpl_vars['lagoa']['nome']; ?>
</legend>
  <ul>
    <?php $_from = $this->_tpl_vars['pontosAmostrais']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['pontoAmostral']):
?>
      <li>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarPontoAmostral/editar/<?php echo $this->_tpl_vars['pontoAmostral']['id_ponto_amostral']; ?>
" alt="Altera pontoAmostral">[ A ]</a>
        <a href="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarPontoAmostral/excluir/<?php echo $this->_tpl_vars['pontoAmostral']['id_ponto_amostral']; ?>
" alt="Exclui pontoAmostral" class="excluir">[ E ]</a>
        <?php echo $this->_tpl_vars['pontoAmostral']['nome']; ?>

      </li>
    <?php endforeach; endif; unset($_from); ?>
  </ul>
</fieldset>