{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
{if $especie.id_parametro neq ""}
    {assign var="label" value="Alterar esp&eacute;cie"}
{else}
    {assign var="label" value="Cadastar esp&eacute;cie"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarEspecie/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$especie.nome}">
  <br/><br/>
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

