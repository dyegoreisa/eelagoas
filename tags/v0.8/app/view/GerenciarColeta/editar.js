function exibeProfundidade() {
    var dir = $('#dir').val();

    $.get(dir + '/GerenciarColeta/ajaxExibeProfundidade/' + $(this).val(), function (profundidade) {
        campoProfundidade(profundidade);
    });
}

function campoProfundidade(tk) {
    $("#v_campo_profundidade").val("");
    if (tk == 'true') {
        $('#campo_profundidade').removeClass('escondido');
        $("#v_campo_profundidade").attr("disabled","");
    } else {
        $('#campo_profundidade').addClass('escondido');
        $("#v_campo_profundidade").attr("disabled","disabled");
    }
}

function carregarLagoa(disabled) {
    if (typeof(disabled) == 'undefined' || typeof(disabled) == 'object') {
        disabled = '';
    }

    var dir = $('#dir').val()
    $('#lagoa_selecionar').load(dir + '/GerenciarLagoa/montarSelect/' + $(this).val(), function() {
        $('#id_lagoa').val(0);
        $('#id_lagoa').attr("disabled", disabled);
    });
    $('#ponto_amostral_selecionar').load(dir + '/GerenciarPontoAmostral/montarSelect/-1', function() {
        $('#id_ponto_amostral').val(0);
        $('#id_ponto_amostral').attr("disabled", disabled);
    });
}

function carregarPontoAmostral(disabled) {
    if (typeof(disabled) == 'undefined' || typeof(disabled) == 'object') {
        disabled = '';
    }

    var dir = $('#dir').val()
    $('#ponto_amostral_selecionar').load(dir + '/GerenciarPontoAmostral/montarSelect/' + $(this).val(), function () {
        $('#id_ponto_amostral').val(0);
        $('#id_ponto_amostral').attr("disabled", disabled);
    });
}

// Comportamentos 
function onLoad() {
    $('#id_projeto').livequery('change', carregarLagoa);

    $('#id_lagoa').livequery('change', carregarPontoAmostral);

    $('#id_categoria').livequery('change', exibeProfundidade);

    $(":checkbox").click( function() {
        campos = $(this).attr('alt');

        if( $(this).attr('checked') ) {
            $("#" + campos).removeClass('escondido');
            $("#v_" + campos).attr("disabled","");
        } else {
            $("#" + campos).addClass('escondido');
            $("#v_" + campos).attr("disabled","disabled");
        }
    });

    $("#editar_coleta").validate({
        rules: {
            data: {
                required: true,
                dataColeta: true
            }
        },
        messages: {
            data: {
                required: "Este campo n&atilde;o pode ser vazio.",
                dataColeta: "A data deve est&aacute; no padr&atilde;o informado ao lado."
            }
        }
    });
};
