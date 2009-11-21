{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
    <legend>Cadastrar Categoria</legend>

<form action="{$dir}/GerenciarCategoria/salvar" method="POST" class="cmxform">

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$categoria.nome}">
    <br/>
    <label for="id_categoria_extra">Informa&ccedil;&atilde;o extra:</label><br/>
    <select name="id_categoria_extra" id="id_categoria_extra">
        {html_options options=$select_extra selected=$categoria.id_categoria_extra}
    </select>
    <br/><br/>

    <br/>

    {if $categoria.id_categoria neq ""}
        <input type="hidden" name="id_categoria" value="{$categoria.id_categoria}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

