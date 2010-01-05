{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Cadastrar Perfil'}

<form action="{$dir}/GerenciarPerfil/salvar" method="POST" class="cmxform">

    <label for="login">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$perfil.nome}">
    <br/><br/>

    <fieldset>
        <legend>Permiss&otilde;es</legend>
        <table class="grid" border="0">
            {foreach from=$classes item=classe}
                <tr><th colspan="2">{$classe.modulo}</th></tr>
                {foreach from=$classe.metodos key=metodo item=acesso}
                    <tr>
                        <td>{$metodo}:</td>
                        <td>
                            <select name="{$classe.class}_{$metodo}">
                                {html_options options=$simNao selected=$acesso}
                            </select>
                        </td>
                    </td></tr>
                {/foreach}
            {/foreach}
        </table>
    </fieldset>

    <br/>

    {if $perfil.id_perfil neq ""}
        <input type="hidden" name="id_perfil" value="{$perfil.id_perfil}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

