function abreNovo(seletor){
    var esconder = "#" + seletor + "_inserir";
    var mostrar = "#" + seletor + "_selecionar";
    var desativar = "#id_" + seletor;
    var ativar = "#nome_" + seletor;

    switch(seletor) {
        case 'projeto':
            carregarLagoa('disabled');
            break;

        case 'lagoa':
            carregarPontoAmostral('disabled');
            break;

        case 'categoria':
            $('#id_categoria_extra').attr("disabled","");
            $('#id_categoria_extra').val(0);
            $('#categoria_extra').html('');
            break;
    }

    $(esconder).removeClass("escondido");
    $(mostrar).addClass("escondido");
    $(desativar).val(0);
    $(desativar).attr("disabled","disabled");
    $(ativar).attr("disabled","");
}

function fechaNovo(seletor) {
    var esconder = "#" + seletor + "_selecionar";
    var mostrar = "#" + seletor + "_inserir";
    var desativar = "#nome_" + seletor;
    var ativar = "#id_" + seletor;

    $(esconder).removeClass("escondido");
    $(mostrar).addClass("escondido");
    $(desativar).val('');
    $(desativar).attr("disabled","disabled");
    $(ativar).attr("disabled","");
    $(ativar).val(0);

    if (seletor == 'categoria') {
        $('#id_categoria_extra').val(0);
        $('#categoria_extra').html('');
    }
}

function novoParametro() {
    var i = $('#count').val();
    var dir = $('#dir').val();
    var idCategoria = $('#id_categoria').val();
    var idExtra;
    var url;

    if (idCategoria == '-1') {
        var idExtra = $('#id_categoria_extra').val();
        if (idExtra != '1') {
            url = dir + '/GerenciarColeta/ajaxNovoParametro/' + i + '/' + idCategoria + '/' + idExtra;
        } else  {
            url = dir + '/GerenciarColeta/ajaxNovoParametro/' + i + '/' + idCategoria;
        }
    } else {
        url = dir + '/GerenciarColeta/ajaxNovoParametro/' + i + '/' + idCategoria;
    }

    $('#count').val(++i);
    
    $.get(url, function (conteudo) {
        $("#parametro_inserir").append(conteudo);
    });
}

function parametroCategoriaExtra() {
    var dir = $('#dir').val();

    $.get(dir + '/GerenciarColeta/ajaxParametroCategoriaExtra/' + $(this).val(), function (conteudo) {
        $('#categoria_extra').html('');
        if (conteudo != '') {
            $('#categoria_extra').append(conteudo);
        }
    });
}

function parametroNovaCategoriaExtra() {
    var dir = $('#dir').val();

    $.get(dir + '/GerenciarColeta/ajaxParametroNovaCategoriaExtra/' + $(this).val(), function (conteudo) {
        $('#categoria_extra').html('');
        if (conteudo != '') {
            $('#categoria_extra').append(conteudo);
        }
    });
}

function removeParametro() {
    var parametro = $(this).attr('alt');
    $("#" + parametro ).remove();
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

function novoItemParametroExtra() {
    var dir        = $('#dir').val()
    var seletor    = $(this).attr('alt');
    var countItens = $('#countItens').val();
    var count      = $('#count').val();
    var thisCount  = $(this).attr('count');
    var origem;
    var idExtra;

    $('#countItens').val(++countItens);

    if ($(this).val() == '+') {
        origem    = 'interno';
        idExtra   = $(this).attr('idExtra');
    } else {
        origem    = 'externo';
        idExtra   = $(this).val();
    }

    var url = dir + '/GerenciarColeta/ajaxNovoItemParametroExtra/' 
            + count + '/' + countItens + '/' + idExtra + '/' + origem;

    $.get(url, function (conteudo) {
        if (origem == 'externo') {
            $('#add_itens' + count).html('');
            $('#itens_extra' + count).html('');
        }
        
        if (conteudo != '') {
            if (origem == 'externo') {
                botao = '<input type="button" value="+" class="add_item_extra" alt="itens_extra' 
                      + count + '" idExtra="' + idExtra + '" count="' + thisCount + '"/>';
                $('#add_itens' + thisCount).append(botao);
            }
            $('#itens_extra' + thisCount).append(conteudo);
        }
    });

}

function removeItemExtra() {
    var parametro = $(this).attr('alt');
    $("#" + parametro ).remove();
}

function removeItensExtra() {
    
}

// Comportamentos 
function onLoad() {
    $('.novo_item').livequery('click', novoParametro);

    $('.cancelar_item').livequery('click', removeParametro);

    $('#id_projeto').livequery('change', carregarLagoa);

    $('#id_lagoa').livequery('change', carregarPontoAmostral);

    $('#id_categoria').livequery('change', parametroCategoriaExtra);

    $('#id_categoria_extra').livequery('change', parametroNovaCategoriaExtra);

    $('.parametro_extra').livequery('change', novoItemParametroExtra);

    $('.add_item_extra').livequery('click', novoItemParametroExtra);

    $('.rem_item_extra').livequery('click', removeItemExtra);

    $('.novo').livequery('click', function() {
        seletor = $(this).attr('alt');
        abreNovo(seletor);
        switch (seletor) {
            case 'projeto':
                abreNovo('lagoa');
                abreNovo('ponto_amostral');
                break;

            case 'lagoa':
                abreNovo('ponto_amostral');
                break;
        }
    });

    $(".cancelar").livequery('click', function() {
        fechaNovo($(this).attr('alt'));
    });

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
