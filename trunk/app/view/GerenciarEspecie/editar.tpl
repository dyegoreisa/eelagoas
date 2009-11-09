{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
  <legend>Cadastrar Especie</legend>

<form action="{$dir}/GerenciarEspecie/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$especie.nome}">
  <br/>
  <label for="id_parametro">Parametro:</label><br/>
    <select name="id_parametro" id="id_parametro">
        <option value="-1"> -- [Selecione] -- </option>
        {html_options options=$select_parametros selected=$especie.id_parametro}
    </select>
  <br/><br/>

  <br/>

  {if $especie.id_especie neq ""}
    <input type="hidden" name="id_especie" value="{$especie.id_especie}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

