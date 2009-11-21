{assign var=sufixo value='_selected'}
{assign var=campo_extra_selecionado value=$parametro.nome_campo_extra$sufixo}
<span id="{$parametro.nome_campo_extra}_selecionar">
    <label for="{$parametro.nome_campo_extra}_{$id_parametro}">{$parametro.descricao}:</label>
    <select name="relacao_extra[{$id_parametro}][]" id="{$parametro.nome_campo_extra}_{$id_parametro}" size="5" multiple="multiple" class="campo">
        {html_options options=$parametro[$parametro.nome_campo_extra] selected=$parametro[$campo_extra_selecionado]}
    </select>
</span>
