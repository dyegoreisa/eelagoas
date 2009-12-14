<script type="text/javascript" language="javascript" src="{$site}/app/view/GerenciarColeta/editar.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(onLoad);
</script>
{literal}
<style>
    .escondido{
        display: none;
    }
    .campos_parametros{
        padding-left: 20px;
    }
</style>
{/literal}

<!-- Form com informações para enviar para o JavaScript -->
<form>
<input type="hidden" id="dir" value="{$dir}"/>
<input type="hidden" id="count" value="0"/>
<input type="hidden" id="countItens" value="0"/>
</form>

<fieldset>
<legend>Cadastrar Coleta</legend>

<form action="{$dir}/GerenciarColeta/salvar" method="POST" class="cmxform" id="editar_coleta">

    <label for="data">Data da coleta:</label><br/>
    <input type="text" name="data" id="data" value="{$coleta.data}">
    <i>(mm/aaaa hh) ou (dd/mm/aaaa hh)</i>
    <br/><br/>

    <label for="nome_projeto">Projeto:</label><br/>
    <span id="projeto_selecionar">
        <select name="id_projeto" id="id_projeto">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_projeto selected=$id_projeto}
        </select>
        <input type="button" class="novo" alt="projeto" value="Novo">
    </span>
    <span id="projeto_inserir" class="escondido">
        <input type="text" name="nome_projeto" id="nome_projeto" value="" disabled>
        <input type="button" class="cancelar" alt="projeto" value="Cancelar">
    </span>
    <br/><br/>

    <label for="nome_lagoa">Lagoa:</label><br/>
    <span id="lagoa_selecionar">
        <select name="id_lagoa" id="id_lagoa">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_lagoa selected=$coleta.id_lagoa}
        </select>
        <input type="button" class="novo" alt="lagoa" value="Novo">
    </span>
    <span id="lagoa_inserir" class="escondido">
        <input type="text" name="nome_lagoa" id="nome_lagoa" value="" disabled>
        <input type="button" class="cancelar" alt="lagoa" value="Cancelar">
    </span>
    <br/><br/>

    <label for="nome_ponto_amostral">Ponto amostral:</label><br/>
    <span id="ponto_amostral_selecionar">
        <select name="id_ponto_amostral" id="id_ponto_amostral">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_ponto_amostral selected=$coleta.id_ponto_amostral}
        </select>
        <input id="button_ponto_amostral" type="button" class="novo" alt="ponto_amostral" value="Novo">
    </span>
    <span id="ponto_amostral_inserir" class="escondido">
        <input type="text" name="nome_ponto_amostral" id="nome_ponto_amostral" value="" disabled>
        <input type="button" class="cancelar" alt="ponto_amostral" value="Cancelar">
    </span>
    <br/><br/>

    <label for="nome_categoria">Categoria:</label><br/>
    <span id="categoria_selecionar">
        <select name="id_categoria" id="id_categoria">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_categoria selected=$coleta.id_categoria}
        </select>
        <input type="button" class="novo" alt="categoria" value="Novo">

    </span>
    <span id="categoria_inserir" class="escondido">
        <input type="text" name="nome_categoria" id="nome_categoria" value="" disabled>
        <br/>
        <label for="id_categoria_extra">Informa&ccedil;&atilde;o extra:</label><br/>
        <select name="id_categoria_extra" id="id_categoria_extra" disabled="disabled">
            {html_options options=$select_categoria_extra}
        </select>
        <input type="button" class="cancelar" alt="categoria" value="Cancelar">
    </span><br/>
    {if $campoExtraCategoria.nome neq 'nenhum' and $campoExtraCategoria.nome neq ''}
        <span id="categoria_extra">
            {include file=$parametro_categoria_extra}
        </span>
    {else}
        <span id="categoria_extra"></span>
    {/if}
    <br/><br/>

    <fieldset id="lista_parametros">
        <legend>Parametros: <input type="button" class="novo_item" alt="parametro" value="Novo"></legend>
        <div class="box">
            {foreach from=$select_parametro key=id_parametro item=parametro}

                <label>
                    <input type="checkbox" name="id_parametros[]" value="{$id_parametro}" alt="valor{$id_parametro}" {if $parametro.id_coleta_parametro neq ""} checked {/if} />{$parametro.nome}
                </label>
                <br>

                {include file=$dados_parametros}

            {/foreach}
        </div>
    </fieldset>

    <fieldset id="lista_novos_parametros" class="escondido">
        <legend>Novos parametros:</legend>
        <div class="box">
            <span id="parametro_inserir"></span>
        </div>
    </fieldset>
    <br/>
    
    {if $coleta.id_coleta neq ""}
        <input type="hidden" name="id_coleta" value="{$coleta.id_coleta}">
    {/if}
    <input type="submit" value="Salvar">

<form>

</fieldset>
