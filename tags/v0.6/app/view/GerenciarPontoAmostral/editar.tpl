{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
{if $pontoAmostral.id_ponto_amostral neq ""}
    {assign var="label" value="Alterar ponto amostral"}
{else}
    {assign var="label" value="Cadastrar ponto amostral"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarPontoAmostral/salvar" method="POST" class="cmxform">

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$pontoAmostral.nome}">
    <br/>
    <label for="id_lagoa">Lagoa:</label><br/>
    <select name="id_lagoa" id="id_lagoa">
        <option value="-1"> -- [Selecione] -- </option>
        {html_options options=$select_lagoas selected=$pontoAmostral.id_lagoa}
    </select>
    <br/><br/>

    {if $pontoAmostral.id_ponto_amostral neq ""}
        <input type="hidden" name="id_ponto_amostral" value="{$pontoAmostral.id_ponto_amostral}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

