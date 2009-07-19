{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
  <legend>Cadastrar Parametro</legend>

<form action="{$dir}/GerenciarParametro/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$parametro.nome}">
  <br/><br/>

  <br/>

  {if $parametro.id_parametro neq ""}
    <input type="hidden" name="id_parametro" value="{$parametro.id_parametro}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

