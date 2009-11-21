<span id="parametro{$count}"><br>
    <input type="text" name="nome_parametros[]" value=""><br/>
    <select name="id_parametro_extra{$count}" id="id_categoria_extra{$count}" class="parametro_extra" alt="itens_extra{$count}">
        {html_options options=$select_parametro_extra}
    </select>
    <span id="itens_extra{$count}"></span>
    <div class="campos_parametros">
        <label>Valor:<br/> <input type="text" name="valor_novo[]" size="10"></label>
        <br />
        {if $campoExtra neq '' and $campoExtra.nome neq 'nenhum'}
            {include file=$parametro_categoria_extra}
        {/if}
    </div>
    <input type="button" class="cancelar_item" alt="parametro{$count}" value="Cancelar">
</span>
