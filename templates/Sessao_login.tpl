{literal}
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
{/literal}
{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
  <legend>Identifica&ccedil;&atilde;o</legend>
  <form id="login" action='{$dir}/Sessao/login' method='post' class="cmxform">
    <label for='login'>login:</label><br/>
      <input type='text' name='login' id='login' />
      <br/>
    <label for='senha'>senha:</label><br/>
      <input type='password' name='senha' id='senha' />
      <br/>
    <input type='submit' value='entrar' />
  </form>
</fieldset>
