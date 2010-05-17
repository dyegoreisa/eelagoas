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
{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<!-- Form com informações para enviar para o JavaScript -->
<form>
<input type="hidden" id="dir" value="{$dir}"/>
<input type="hidden" id="count" value="0"/>
<input type="hidden" id="countItens" value="0"/>
</form>
{if $coleta.id_coleta neq ""}
    {assign var="label" value="Alterar coleta"}
{else}
    {assign var="label" value="Cadastar coleta"}
{/if}
<fieldset>
<legend>{$label}</legend>

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
    </span>
    <br/><br/>

    <label for="nome_lagoa">Lagoa:</label><br/>
    <span id="lagoa_selecionar">
        <select name="id_lagoa" id="id_lagoa">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_lagoa selected=$coleta.id_lagoa}
        </select>
    </span>
    <br/><br/>

    <label for="nome_ponto_amostral">Ponto amostral:</label><br/>
    <span id="ponto_amostral_selecionar">
        <select name="id_ponto_amostral" id="id_ponto_amostral">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_ponto_amostral selected=$coleta.id_ponto_amostral}
        </select>
    </span>
    <br/><br/>

    <label for="nome_categoria">Categoria:</label><br/>
    <span id="categoria_selecionar">
        <select name="id_categoria" id="id_categoria">
            <option value="-1"> -- [Selecione] -- </option>
            {html_options options=$select_categoria selected=$coleta.id_categoria}
        </select>
    </span>
    <br/><br/>

    <span id="campo_profundidade" class="{if $tem_profundidade neq 1}escondido{/if}">
        <label for="v_campo_profundidade">Profundidade:</label><br/>
        <input type="text" name="profundidade" id="v_campo_profundidade" size="10" value="{$coleta.profundidade}"/>
        <br/>
    </span>
    <br/>

    <fieldset id="lista_parametros">
        <legend>Parametros:</legend>
        <div class="box">
            {foreach from=$select_parametro key=id_parametro item=parametro}

                <input type="checkbox" name="parametros[{$id_parametro}][id]" id="parametro{$id_parametro}" value="{$id_parametro}" alt="valor{$id_parametro}" {if $parametro.id_coleta_parametro neq ""} checked {/if} />
                <label for="parametro{$id_parametro}">{$parametro.nome}</label>
                <br>

                <div class="campos_parametros {if $parametro.id_coleta_parametro eq ""}escondido{/if}" id="valor{$id_parametro}">
                    {if $parametro.composicao eq true}
                        <span>Esp&eacute;cies:</span><br/>
                        <div>
                            {foreach from=$parametro.especies item=especie}
                                <div class="campos_parametros">
                                    <input type="checkbox" name="parametros[{$id_parametro}][especie][{$especie.id_especie}][id]" id="especie{$especie.id_especie}" value="{$especie.id_especie}" alt="qtde{$especie.id_especie}" {if $especie.quantidade neq ""}checked{/if}>
                                    <label for="especie{$especie.id_especie}">{$especie.nome}</label><br/>
                                    <span id="qtde{$especie.id_especie}" class="{if $especie.quantidade eq ""}escondido{/if}">
                                        <label for="v_qtde_especie{$especie.id_especie}">Quantidade:</label>
                                        <input type="text" name="parametros[{$id_parametro}][especie][{$especie.id_especie}][qtde]" id="v_qtde_especie{$especie.id_especie}" size="5" value="{$especie.quantidade}">
                                    </span>
                                </div>
                            {/foreach}
                        </div>
                        <br/>
                    {else}
                        <label for="v_valor{$id_parametro}">Valor:</label><br/> 
                        <input type="text" id="v_valor{$id_parametro}" name="parametros[{$id_parametro}][valor]" size="10" value="{$parametro.valor}">
                    {/if}
                    <hr>
                </div>

            {/foreach}
        </div>
    </fieldset>
    
    {if $coleta.id_coleta neq ""}
        <input type="hidden" name="id_coleta" value="{$coleta.id_coleta}">
    {/if}
    <input type="submit" value="Salvar">

<form>

</fieldset>
