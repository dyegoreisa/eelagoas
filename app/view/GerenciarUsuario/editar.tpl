{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Cadastrar Usu&aacute;rio'}

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

    <br/>

    {if $usuario.id_usuario neq ""}
        <input type="hidden" name="id_usuario" value="{$usuario.id_usuario}">
    {/if}
    <input type="submit" value="Salvar">

<form>
</fieldset>

