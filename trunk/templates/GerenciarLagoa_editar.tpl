{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
  <legend>Cadastrar Lagoa</legend>

<form action="{$dir}/GerenciarLagoa/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$lagoa.nome}">
  <br/><br/>

  <br/>

  {if $lagoa.id_lagoa neq ""}
    <input type="hidden" name="id_lagoa" value="{$lagoa.id_lagoa}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

