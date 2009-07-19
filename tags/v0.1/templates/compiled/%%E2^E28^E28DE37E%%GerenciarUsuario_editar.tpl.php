<?php /* Smarty version 2.6.18, created on 2009-07-09 19:45:06
         compiled from GerenciarUsuario_editar.tpl */ ?>
<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Cadastrar Usuario</legend>

<form action="<?php echo $this->_tpl_vars['dir']; ?>
/GerenciarUsuario/salvar" method="POST" class="cmxform">

  <label for="login">Login:</label><br/>
  <input type="text" name="login" id="login" value="<?php echo $this->_tpl_vars['usuario']['login']; ?>
">
  <br/><br/>

  <label for="senha">Senha:</label><br/>
  <input type="password" name="senha" id="senha" value="">
  <br/><br/>

  <label for="confirma_senha">Confirmar senha:</label><br/>
  <input type="password" name="confirma_senha" id="confirma_senha" value="">
  <br/><br/>

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="<?php echo $this->_tpl_vars['usuario']['nome']; ?>
">
  <br/><br/>

  <label for="email">E-mail:</label><br/>
  <input type="text" name="email" id="email" value="<?php echo $this->_tpl_vars['usuario']['email']; ?>
">
  <br/><br/>

  <br/>

  <?php if ($this->_tpl_vars['usuario']['id_usuario'] != ""): ?>
    <input type="hidden" name="id_usuario" value="<?php echo $this->_tpl_vars['usuario']['id_usuario']; ?>
">
  <?php endif; ?>
  <input type="submit" value="Salvar">

<form>
</fieldset>
