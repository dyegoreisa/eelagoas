<div class="campos_parametros {if $parametro.id_coleta_parametro eq ""}escondido{/if}" id="valor{$id_parametro}">
    <label for="v_valor{$id_parametro}">Valor:<br/> 
    <input type="text" id="v_valor{$id_parametro}" name="valor[{$id_parametro}]" size="10" value="{$parametro.valor}"></label>
    <br />
    {if $parametro.tem_valor eq true}
        {if $parametro.tem_relacao eq true}
            {include file=$dado_extra}
        {/if}
        <br/>
    {/if}
</div>
