<?php /* Smarty version 2.6.20, created on 2009-07-15 22:49:59
         compiled from GerenciarPontoAmostral_editar.tpl */ ?>
<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Cadastrar Ponto Amostral</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarPontoAmostral/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="<?php echo $this->_tpl_vars['pontoAmostral']['nome']; ?>
">
  <br/><br/>

  <br/>

  <?php if ($this->_tpl_vars['pontoAmostral']['id_ponto_amostral'] != ""): ?>
    <input type="hidden" name="id_ponto_amostral" value="<?php echo $this->_tpl_vars['pontoAmostral']['id_ponto_amostral']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>
</fieldset>
