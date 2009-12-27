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
        <ul>
            {foreach from=$classes item=classe}
            <li>{$classe.modulo}
                <ul>
                    {foreach from=$classe.metodos key=metodo item=acesso}
                    <li>
                        {$metodo}:
                        <select name="{$classe.class}_{$metodo}">
                            {html_options options=$simNao selected=$acesso}
                        </select>
                    </li>
                    {/foreach}
                </ul>
            </li>
            {/foreach}
        </ul>
    </fieldset>

    <br/>

    {if $perfil.id_perfil neq ""}
        <input type="hidden" name="id_perfil" value="{$perfil.id_perfil}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

