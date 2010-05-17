{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
{if $erro eq 'erro'}
    [ <a href="{$site}/index.php/Importador/inserir">Voltar</a> ] 
{else}
    <fieldset>
        <legend>Importar Excel</legend>

        <p>Verifique abaixo os dados antes de importar.</p>

        <p>Os dados ser&atilde;o inseridos no projeto <span class="alerta">{$nome_projeto}</span></p>

        <br/>
        [ <a href="{$site}/index.php/Importador/importar">Importar</a> ] [ <a href="{$site}/index.php/Importador/selecionar">Cancelar</a> ] 

    </fieldset>
    <br/>
    <div class="box">
        <table class="grid" cellspacing="0">
            {foreach from=$cabecalho item=linha}
                <tr>
                {foreach from=$linha item=coluna key=chave}
                    {*{if isset($coluna.cellInfo.colspan)}
                        <th colspan="{$coluna.cellInfo.colspan}">{$coluna.data}</th>   
                    {elseif isset($coluna.cellInfo.rowspan)}
                        <th rowspan="{$coluna.cellInfo.rowspan}">{$coluna.data}</th>   
                    {else}*}
                        <th>{$coluna.data}</th>   
                    {*{/if}*}
                {/foreach}
                <tr>
            {/foreach}
            {foreach from=$dados item=linha}
                <tr class="{cycle values="par, impar"}">
                {foreach from=$linha item=coluna}
                    <td align="center">{$coluna.data}</td>
                {/foreach}
                </tr>
            {/foreach}
        </table>
    </div>

{/if}

