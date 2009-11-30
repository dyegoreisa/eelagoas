{if $mensagem neq ""}
  <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Cadastrar Lagoa'}

<form action="{$dir}/GerenciarLagoa/salvar" method="POST" class="cmxform">

  <label for="nome">Nome:</label><br/>
  <input type="text" name="nome" id="nome" value="{$lagoa.nome}">
  <br/>
  <label for="id_projeto">Projeto:</label><br/>
    <select name="id_projeto" id="id_projeto">
        <option value="-1"> -- [Selecione] -- </option>
        {html_options options=$select_projetos selected=$lagoa.id_projeto}
    </select>
  <br/><br/>

  <br/>

  {if $lagoa.id_lagoa neq ""}
    <input type="hidden" name="id_lagoa" value="{$lagoa.id_lagoa}">
  {/if}
  <input type="submit" value="Salvar">

<form>
</fieldset>

