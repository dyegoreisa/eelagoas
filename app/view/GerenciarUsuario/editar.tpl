{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
{if $usuario.id_usuario neq ""} 
    {assign var="label" value="Alterar usu&aacute;rio"}
{else}  
    {assign var="label" value="Cadastar usu&aacute;rio"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarUsuario/salvar" method="POST" class="cmxform">

    {if $usuario.id_usuario eq ""}
        <label for="login">Login:</label><br/>
        <input type="text" name="login" id="login" value="{$usuario.login}">
        <br/><br/>

        <label for="senha">Senha:</label><br/>
        <input type="password" name="senha" id="senha" value="">
        <br/><br/>

        <label for="confirma_senha">Confirmar senha:</label><br/>
        <input type="password" name="confirma_senha" id="confirma_senha" value="">
        <br/><br/>
    {else}
        <label for="login">Login:</label><br/>
        <input type="text" id="login" value="{$usuario.login}" readonly="readonly">
        <br/><br/>
    {/if}
    

    <label for="nome">Nome:</label><br/>
    <input type="text" name="nome" id="nome" value="{$usuario.nome}">
    <br/><br/>

    <label for="email">E-mail:</label><br/>
    <input type="text" name="email" id="email" value="{$usuario.email}">
    <br/><br/>

    <label for="id_perfil">Perfil:</label><br/>
    <select name="id_perfil" id="id_perfil">
        <option value="-1"> -- [Selecione] -- </option>
        {html_options options=$select_perfis selected=$usuario.id_perfil}
    </select>
    <br/><br/>

    <br/>

    {if $usuario.id_usuario neq ""}
        <input type="hidden" name="id_usuario" value="{$usuario.id_usuario}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

