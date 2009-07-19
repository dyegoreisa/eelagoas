{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
  <legend>Cadastrar Ponto Amostral</legend>

<form action="{$dir}/GerenciarPontoAmostral/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$pontoAmostral.nome}">
  <br/><br/>

  <br/>

  {if $pontoAmostral.id_ponto_amostral neq ""}
    <input type="hidden" name="id_ponto_amostral" value="{$pontoAmostral.id_ponto_amostral}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

