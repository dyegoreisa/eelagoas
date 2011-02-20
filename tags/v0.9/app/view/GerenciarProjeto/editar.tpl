{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
{if $projeto.id_projeto neq ""}
    {assign var="label" value="Alterar projeto"}
{else}
    {assign var="label" value="Cadastrar projeto"}
{/if}
<fieldset>
{include file=$submenu titulo=$label}

<form action="{$dir}/GerenciarProjeto/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$projeto.nome}">
  <br/><br/>

  <br/>

  {if $projeto.id_projeto neq ""}
    <input type="hidden" name="id_projeto" value="{$projeto.id_projeto}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

