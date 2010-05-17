{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
{if $parametro.id_parametro_extra neq ""}
    {assign var="label" value="Alerar par&acirc;metro"}
{else}
    {assign var="label" value="Cadastrar par&acirc;metro"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarParametro/salvar" method="POST" class="cmxform">

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$parametro.nome}">
    <br/><br/>
    <label for="composicao">&Eacute; composi&ccedil;&atilde;o:</label><br/>
    <input type="checkbox" id="composicao" name="composicao" value="1" {$composicao}/>
    <label for="composicao">Marque est&aacute; op&ccedil;&atilde;o quando o par&acirc;metro fizer refer&ecirc;ncia a uma composi&ccedil;&atilde;o.</label>
    <br/><br/>

    <br/>

    {if $parametro.id_parametro neq ""}
        <input type="hidden" name="id_parametro" value="{$parametro.id_parametro}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

