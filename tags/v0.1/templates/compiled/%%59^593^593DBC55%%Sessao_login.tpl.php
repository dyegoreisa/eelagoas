<?php /* Smarty version 2.6.18, created on 2009-07-05 18:30:36
         compiled from Sessao_login.tpl */ ?>
<?php echo '
  <script type="text/javascript" language="javascript">
    $(document).ready(function () {
      $("#login").validate({
        rules: {
          login: "required",
          senha: "required"
        },
        messages: {
          login: "Este campo n&atilde;o pode ser vazio.",
          senha: "Este campo n&atilde;o pode ser vazio."
        }
      });
    });
  </script>
'; ?>

<?php if ($this->_tpl_vars['mensagem'] != ""): ?>
  <p><?php echo $this->_tpl_vars['mensagem']; ?>
</p>
<?php endif; ?>
<fieldset>
  <legend>Identifica&ccedil;&atilde;o</legend>
  <form id="login" action='<?php echo $this->_tpl_vars['dir']; ?>
/Sessao/login' method='post' class="cmxform">
    <label for='login'>login:</label><br/>
      <input type='text' name='login' id='login' />
      <br/>
    <label for='senha'>senha:</label><br/>
      <input type='password' name='senha' id='senha' />
      <br/>
    <input type='submit' value='entrar' />
  </form>
</fieldset>