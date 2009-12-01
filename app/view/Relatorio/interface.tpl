{literal}
<script type="text/javascript" language="javascript">

    function loadDatas(idLagoa, tipoPeriodo) {
        $("#dia_selecionar").load(dir + "/GerenciarLagoa/montarMultiSelectData/dia/diario/" + idLagoa);
        $("#mes_selecionar").load(dir + "/GerenciarLagoa/montarMultiSelectData/mes/" + tipoPeriodo + '/' + idLagoa);
        $("#ano_selecionar").load(dir + "/GerenciarLagoa/montarMultiSelectData/ano/" + tipoPeriodo + '/' + idLagoa);
        $("#hora_selecionar").load(dir + "/GerenciarLagoa/montarMultiSelectData/hora/" + tipoPeriodo + '/' + idLagoa);
    }

    $(document).ready(function () {
        dir = $('#dir').val();

        $('#projeto').livequery('change', function() {
            $("#lagoa_selecionar").load( dir + "/GerenciarLagoa/montarMultiSelect/" + $(this).val() );

            // Apaga campos
            $("#ponto_amostral_selecionar > select > option").remove();
            $("#dia_selecionar > select > option").remove();
            $("#mes_selecionar > select > option").remove();
            $("#ano_selecionar > select > option").remove();
            $("#hora_selecionar > select > option").remove();
        });

        $("#lagoa").livequery('change', function () {
            idLagoa     = $(this).val();
            tipoPeriodo = $("input:[name=tipo_periodo][checked]").val(); 

            $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarMultiSelect/" + idLagoa);

            loadDatas(idLagoa, tipoPeriodo);
        });

        $("input:[name=tipo_periodo]").livequery('click', function () {
            idLagoa     = $("#lagoa").val();
            tipoPeriodo = $(this).val();

            if (tipoPeriodo == 'mensal') {
                $("#campo_dia").hide();
            } else {
                $("#campo_dia").show();
            }

            loadDatas(idLagoa, tipoPeriodo);
        });

    });
</script>
{/literal}
{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<form/>
    <input type="hidden" id="dir" value="{$dir}"/>
</form>
<fieldset>
    <legend>Relat&oacute;rio Configur&aacute;vel</legend>

<form action="{$dir}/Relatorio/reportExecute" method="POST" class="cmxform" target="_blank" >
    
    <div class="campo">
    <label for="projeto">Projetos:</label><br/>
    <select name="projeto[]" id="projeto" multiple="multiple" size="5" class="campo">
        {html_options options=$select_projeto selected=''}
    </select>
    </div>

    <div class="campo">
    <label for="lagoa">Lagoas:</label><br/>
    <span id="lagoa_selecionar">
        <select name="lagoa[]" id="lagoa" multiple="multiple" size="5" class="campo">
        </select>
    </span>
    </div>

    <div class="campo">
    <label for="ponto_amotral">Pontos amostrais:</label><br/>
    <span id="ponto_amostral_selecionar">
        <select name="ponto_amostral[]" id="ponto_amostral" multiple="multiple" size="5" class="campo">
        </select>
    </span>
    </div>

    <div class="campo">
    <label for="categorias">Categoria:</label><br/>
    <select name="categorias[]" id="categorias" multiple="multiple" size="5" class="campo">
        {html_options options=$select_categoria selected=''}
    </select>
    </div>

    <div class="campo">
    <label for="parametro">Parametros:</label><br/>
    <select name="parametro[]" id="parametro" multiple="multiple" size="5" class="campo">
        {html_options options=$select_parametro selected=''}
    </select>
    </div>

    <div class="campo" id="campo_dia">
    <label for="dia">Dias:</label><br/>
    <span id="dia_selecionar">
        <select name="dia[]" id="dia" multiple="multiple" size="5" class="campo"></select>
    </span>
    </div>

    <div class="campo">
    <label for="mes">Meses:</label><br/>
    <span id="mes_selecionar">
        <select name="mes[]" id="mes" multiple="multiple" size="5" class="campo"></select>
    </span>
    </div>

    <div class="campo">
    <label for="ano">Anos:</label><br/>
    <span id="ano_selecionar">
        <select name="ano[]" id="ano" multiple="multiple" size="5" class="campo"></select>
    </span>
    </div>

    <div class="campo">
    <label for="hora">Horas:</label><br/>
    <span id="hora_selecionar">
        <select name="hora[]" id="hora" multiple="multiple" size="5" class="campo"></select>
    </span>
    </div>
    <br/>

    <fieldset>
        <legend>Per&iacute;odo</legend>
        <input type="radio" name="tipo_periodo" id="periodo_mensal" value="mensal">
        <label for="periodo_mensal">Mensal</label>&nbsp;&nbsp;&nbsp;
        <input type="radio" name="tipo_periodo" id="periodo_diario" value="diario" checked>
        <label for="periodo_diario">Di&aacute;rio</label>
    </fieldset>

    <fieldset>
        <legend>Tipo de relat&oacute;rio</legend>
        <input type="radio" name="tipo_relatorio" value="html" id="radio_html" checked><label for="radio_html">HTML</label>
        <input type="radio" name="tipo_relatorio" value="pdf" id="radio_pdf" ><label for="radio_pdf">PDF</label>
        <input type="radio" name="tipo_relatorio" value="xls" id="radio_xls"><label for="radio_xls">Excell</label>
    </fieldset>
    <input type="submit" value="Gerar">

<form>
</fieldset>

