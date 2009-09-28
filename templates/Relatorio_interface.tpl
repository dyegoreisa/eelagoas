{literal}
<script type="text/javascript" language="javascript">
{/literal} dir = '{$dir}'; {literal}
    $(document).ready(function () {
        $("#lagoa").change( function () {
            $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarMultiSelect/" + $(this).val() );
        });
    });
</script>
{/literal}
{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
    <legend>Relat&oacute;rio Configur&aacute;vel</legend>

<form action="{$dir}/Relatorio/reportExecute" method="POST" class="cmxform" >
    
    <div class="campo">
    <label for="lagoa">Lagoas:</label><br/>
    <select name="lagoa[]" id="lagoa" multiple="multiple" size="5" class="campo">
        {html_options options=$select_lagoa selected=''}
    </select>
    </div>

    <div class="campo">
    <label for="ponto_amotral">Pontos amostrais:</label><br/>
    <span id="ponto_amostral_selecionar">
        <select name="ponto_amostral[]" id="ponto_amostral" multiple="multiple" size="5" class="campo">
        </select>
    </span>
    </div>

    <div class="campo">
    <label for="id_categoria">Categoria:</label><br/>
    <select name="id_categoria" id="id_categoria" class="campo">
        {html_options options=$select_categoria selected=''}
    </select>
    </div>

    <div class="campo">
    <label for="parametro">Parametros:</label><br/>
    <select name="parametro[]" id="parametro" multiple="multiple" size="5" class="campo">
        {html_options options=$select_parametro selected=''}
    </select>
    </div>
    <br/>

    <fieldset>
        <legend>Per&iacute;odo</legend>
        <label for="data_inicio">Data inicial:</label><br/>
        <input type="text" name="data_inicio" id="data_inicio" size="10">(mm/aaaa)<br/>
        <label for="data_fim">Data final:</label><br/>
        <input type="text" name="data_fim" id="data_fim" size="10">(mm/aaaa)
    </fieldset>

    <fieldset>
        <legend>Tipo de relat&oacute;rio</legend>
        {*<input type="radio" name="tipo_relatorio" value="html" id="radio_html"><label for="radio_html">HTML</label>*}
        <input type="radio" name="tipo_relatorio" value="pdf" id="radio_pdf" checked><label for="radio_pdf">PDF</label>
        {*<input type="radio" name="tipo_relatorio" value="xls" id="radio_xls"><label for="radio_xls">Excell</label>*}
    </fieldset>
    <input type="submit" value="Gerar">

<form>
</fieldset>

