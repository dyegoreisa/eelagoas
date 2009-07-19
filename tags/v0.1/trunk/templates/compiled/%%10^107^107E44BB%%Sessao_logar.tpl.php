<?php /* Smarty version 2.6.18, created on 2009-07-04 02:08:56
         compiled from Sessao_logar.tpl */ ?>
<fieldset>
  <legend>Identifica&ccedil;&atilde;o</legend>
  <form action='<?php echo $this->_tpl_vars['dir']; ?>
/Login/logar' method='post'>
    <label for='login'>login:</label>
      <input type='text' name='login' id='login' />
    <label for='senha'>senha:</label>
      <input type='password' name='senha' id='senha' />
    <input type='submit' value='entrar' />
  </form>
</fieldset>