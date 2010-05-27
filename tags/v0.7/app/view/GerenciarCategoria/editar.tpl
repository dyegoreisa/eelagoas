{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
{if $categoria.id_categoria_extra neq ""}
    {assign var="label" value="Alterar categoria"}
{else}
    {assign var="label" value="Cadastar categoria"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarCategoria/salvar" method="POST" class="cmxform">

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$categoria.nome}">
    <br/><br/>
    <label for="e_perfil">&Eacute perfil:</label><br/>
    <input type="checkbox" id="e_perfil" name="e_perfil" value="1" {$e_perfil}/>
    <label for="e_perfil">Marque esta op&ccedil;&atilde;o quando numa coleta desta categoria for necess&aacute;rio armazenar a profundidade.</label>
    <br/><br/>

    <br/>

    {if $categoria.id_categoria neq ""}
        <input type="hidden" name="id_categoria" value="{$categoria.id_categoria}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

