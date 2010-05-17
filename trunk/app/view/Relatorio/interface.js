function loadCampo(seletor, url) {
    $(seletor).html($('#loading').html());
    $(seletor + ' img').removeClass('escondido');
    $(seletor).load(url);
}

function loadDatas(dir, idLagoa, tipoPeriodo) {
    loadCampo('#dia_selecionar' , dir + '/GerenciarLagoa/montarMultiSelectData/dia/diario/' + idLagoa);
    loadCampo('#mes_selecionar' , dir + '/GerenciarLagoa/montarMultiSelectData/mes/' + tipoPeriodo + '/' + idLagoa);
    loadCampo('#ano_selecionar' , dir + '/GerenciarLagoa/montarMultiSelectData/ano/' + tipoPeriodo + '/' + idLagoa);
    loadCampo('#hora_selecionar', dir + '/GerenciarLagoa/montarMultiSelectData/hora/' + tipoPeriodo + '/' + idLagoa);
}

function onLoad() {
    var dir = $('#dir').val();

    $('#projeto').livequery('change', function() {
        loadCampo('#lagoa_selecionar', dir + '/GerenciarLagoa/montarMultiSelect/' + $(this).val());

        // Apaga campos
        $('#ponto_amostral_selecionar > select > option').remove();
        $('#dia_selecionar > select > option').remove();
        $('#mes_selecionar > select > option').remove();
        $('#ano_selecionar > select > option').remove();
        $('#hora_selecionar > select > option').remove();
    });

    $('#lagoa').livequery('change', function () {
        idLagoa     = $(this).val();
        tipoPeriodo = $('input:[name=tipo_periodo][checked]').val(); 

        loadCampo('#ponto_amostral_selecionar', dir + '/GerenciarPontoAmostral/montarMultiSelect/' + idLagoa);

        loadDatas(dir, idLagoa, tipoPeriodo);
    });

    $('input:[name=tipo_periodo]').livequery('click', function () {
        idLagoa     = $('#lagoa').val();
        tipoPeriodo = $(this).val();

        if (tipoPeriodo == 'mensal') {
            $('#campo_dia').hide();
        } else {
            $('#campo_dia').show();
        }

        loadDatas(dir, idLagoa, tipoPeriodo);
    });

    $('#categoria').livequery('change', function () {
        categorias = $(this).val();
        lagoas     = $('#lagoa').val();
        $.getJSON(dir + '/GerenciarCategoria/temProfundidade/' + categorias, function(dados) {
            if (dados[0] == true) { 
                $('#campo_profundidade').removeClass('escondido');
                loadCampo('#campo_profundidade', dir + '/GerenciarCategoria/montarMultiSelectProfundidade/' + categorias);
            } else {
                $('#campo_profundidade').addClass('escondido');
                $('#campo_profundidade').html('');
            }
        });
    });

    $('#parametro').livequery('change', function () {
        parametro = $(this).val();
        $.getJSON(dir + '/GerenciarParametro/eComposicao/' + parametro, function(dados) {
            if (dados[0] == true) { 
                $('#campo_especie').removeClass('escondido');
                loadCampo('#campo_especie', dir + '/GerenciarParametro/montarMultiSelectEspecie/' + parametro);
            } else {
                $('#campo_especie').addClass('escondido');
                $('#campo_especie').html('');
            }
        });
    });

    $('input:[name=tipo_relatorio]').livequery('change', function () {
        if ($(this).val() == 'pdf') {
            if ($(this).attr('checked')) {
                $('#opcao_pdf').removeClass('escondido');
            } else {
                $('#opcao_pdf').addClass('escondido');
            }
        } else {
            $('#opcao_pdf').addClass('escondido');
        }
    });

}
