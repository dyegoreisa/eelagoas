{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Cadastrar Par&acirc;metro'}

<form action="{$dir}/GerenciarParametro/salvar" method="POST" class="cmxform">

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$parametro.nome}">
    <br/>
    <label for="id_parametro_extra">Informa&ccedil;&atilde;o extra:</label><br/>
    <select name="id_parametro_extra" id="id_parametro_extra">
        {html_options options=$select_extra selected=$parametro.id_parametro_extra}
    </select>
    <br/><br/>

    <br/>

    {if $parametro.id_parametro neq ""}
        <input type="hidden" name="id_parametro" value="{$parametro.id_parametro}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

