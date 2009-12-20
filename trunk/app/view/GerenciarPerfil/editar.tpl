{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Cadastrar Perfil'}

<form action="{$dir}/GerenciarPerfil/salvar" method="POST" class="cmxform">

  <label for="login">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$perfil.nome}">
  <br/><br/>

  <br/>

  {if $perfil.id_perfil neq ""}
    <input type="hidden" name="id_perfil" value="{$perfil.id_perfil}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

