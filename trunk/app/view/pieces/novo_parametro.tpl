<span id="parametro{$count}"><br>
    <legend>Novo parametro: <input type="button" class="cancelar_item" alt="parametro{$count}" value="Cancelar"></legend>
    <input type="text" name="nome_parametros[{$count}]" value=""><br/>
    <select name="id_parametro_extra[{$count}]" id="id_categoria_extra{$count}" class="parametro_extra" alt="itens_extra{$count}" count="{$count}">
        {html_options options=$select_parametro_extra}
    </select>
    <span id="add_itens{$count}"></span>
    <br/>
    <span id="itens_extra{$count}"></span>
    <div class="campos_parametros">
        <label>Valor:<br/> <input type="text" name="valor_novo[{$count}]" size="10"></label>
        <br />
    </div>
    <hr>
</span>
