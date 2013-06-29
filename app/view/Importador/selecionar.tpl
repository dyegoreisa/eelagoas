{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
<legend>Importar Excel</legend>

<form action="{$dir}/Importador/verificar" method="POST" class="cmxform" enctype="multipart/form-data">

    <a href="{$dir}/Importador/tabelaPadrao" id="tabela_padrao" target="_blank">Baixar tabela padr&atilde;o</a>
    <br/><br/>
    
    <label for="id_projeto">Projeto:</label><br/>
    <select id="id_projeto" name="id_projeto">
        {html_options options=$select_projeto selected=''}
    </select>
    <br/><br/>

    <label for="arquivo">Arquivo Excel:</label><br/>
    <input type="file" name="arquivo" id="arquivo" value="{$usuario.arquivo}">
    <br/><br/>

    <input type="submit" value="Enviar">

<form>
</fieldset>

