<script type="text/javascript" language="javascript" src="{$site}/app/view/Relatorio/interface.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(onLoad);
</script>
{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<span id="loading"><img border="0" src="{$site}/images/img_carregando.gif" class="escondido" style="width:140px;"></span>
<form/>
    <input type="hidden" id="dir" value="{$dir}"/>
</form>
<fieldset>
    <legend>Relat&oacute;rio Configur&aacute;vel</legend>

    <form action="{$dir}/Relatorio/gerar" method="POST" class="cmxform" target="_blank" >
    
        <table border="0">
            <tr>
                <td class="campo">
                    <label for="projeto">Projetos:</label><br/>
                    <select name="projeto[]" id="projeto" multiple="multiple" size="5" class="campo">
                        {html_options options=$select_projeto selected=''}
                    </select>
                </td>  
                <td class="campo">
                    <label for="lagoa">Lagoas:</label><br/>
                    <span id="lagoa_selecionar">
                        <select name="lagoa[]" id="lagoa" multiple="multiple" size="5" class="campo">
                        </select>
                    </span>
                </td>
                <td class="campo">
                    <label for="ponto_amotral">Pontos amostrais:</label><br/>
                    <span id="ponto_amostral_selecionar">
                        <select name="ponto_amostral[]" id="ponto_amostral" multiple="multiple" size="5" class="campo">
                        </select>
                    </span>
                </td>
            </tr>
            <tr>
                <td class="campo">
                    <label for="categoria">Categoria:</label><br/>
                    <select name="categoria[]" id="categoria" multiple="multiple" size="5" class="campo">
                        {html_options options=$select_categoria selected=''}
                    </select>
                </td>

                <td class="campo escondido" id="campo_profundidade"></td>
            </tr>
            <tr>
                <td class="campo">
                    <label for="parametro">Parametros:</label><br/>
                    <select name="parametro[]" id="parametro" multiple="multiple" size="5" class="campo">
                        {html_options options=$select_parametro selected=''}
                    </select>
                </td>

                <td class="campo escondido" id="campo_especie"></td>
            </tr>
            <tr>
                <td class="campo" id="campo_dia">
                    <label for="dia">Dias:</label><br/>
                    <span id="dia_selecionar">
                        <select name="dia[]" id="dia" multiple="multiple" size="5" class="campo"></select>
                    </span>
                </td>

                <td class="campo">
                    <label for="mes">Meses:</label><br/>
                    <span id="mes_selecionar">
                        <select name="mes[]" id="mes" multiple="multiple" size="5" class="campo"></select>
                    </span>
                </td>

                <td class="campo">
                    <label for="ano">Anos:</label><br/>
                    <span id="ano_selecionar">
                        <select name="ano[]" id="ano" multiple="multiple" size="5" class="campo"></select>
                    </span>
                </td>

                <td class="campo">
                    <label for="hora">Horas:</label><br/>
                    <span id="hora_selecionar">
                        <select name="hora[]" id="hora" multiple="multiple" size="5" class="campo"></select>
                    </span>
                </td>
            </tr>
        </table>

        <br/>

        <fieldset class="campo">
            <legend>Per&iacute;odo</legend>
            <input type="radio" name="tipo_periodo" id="periodo_mensal" value="mensal">
            <label for="periodo_mensal">Mensal</label>&nbsp;&nbsp;&nbsp;
            <input type="radio" name="tipo_periodo" id="periodo_diario" value="diario" checked>
            <label for="periodo_diario">Di&aacute;rio</label>
        </fieldset>

        <fieldset class="campo">
            <legend>Tipo de relat&oacute;rio</legend>
            <input type="radio" name="tipo_relatorio" value="html" id="radio_html" checked><label for="radio_html">HTML</label>
            {*<input type="radio" name="tipo_relatorio" value="pdf" id="radio_pdf" ><label for="radio_pdf">PDF</label>*]
            {*<input type="radio" name="tipo_relatorio" value="xls" id="radio_xls"><label for="radio_xls">Excel</label>*}
        </fieldset>

        <fieldset class="campo escondido" id="opcao_pdf">
            <legend>Op&ccedil;&otilde;es do PDF:</legend>
            <table>
                {*<tr>
                    <td><label>Orienta&ccedil;&atilde;o:</label></td>
                    <td>
                        <select name="orientacao">
                            <option value="P">Retrato</option>
                            <option value="L" selected>Paisagem</option>
                        </select>
                    </td>
                </tr>*}
                <tr>
                    <td><label>Formato:</label></td>
                    <td>
                        <select name="formato">
                            <option value="A3">A3</option>
                            <option value="A4" selected>A4</option>
                            {*<option value="A5">A5</option>*}
                            {*<option value="Letter">Carta</option>*}
                            <option value="Legal">Of&iacute;cio</option>
                        </select>
                    </td>
                </tr>
            </table>
        </fieldset>

        <br/>&nbsp;&nbsp;&nbsp;

        <input type="submit" value="Gerar">

    <form>
</fieldset>

