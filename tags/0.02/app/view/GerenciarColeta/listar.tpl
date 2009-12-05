<fieldset>
    <legend>Lista de coletas da lagoa {$lagoa.nome}</legend>
    <ul>
    {foreach from=$coletas item=coleta}
        <li>
            <a href="{$dir}/GerenciarColeta/editar/{$coleta.id_coleta}">[ A ]</a>
            <a href="{$dir}/GerenciarColeta/excluir/{$coleta.id_coleta}" alt="Deseja excluir o registro?" class="excluir">[ E ]</a>
            {if $coleta.tipo_periodo eq 'mensal'}
                {$coleta.data|date_format:"%m/%Y %H"}
            {else}
                {$coleta.data|date_format:"%d/%m/%Y %H"}
            {/if}
        </li>
    {/foreach}
    </ul>
</fieldset>
