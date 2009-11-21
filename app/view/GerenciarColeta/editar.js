function abreNovo(seletor){
    var esconder = "#" + seletor + "_inserir";
    var mostrar = "#" + seletor + "_selecionar";
    var desativar = "select[name=id_" + seletor + "]";
    var ativar = "input[name=nome_" + seletor + "]";

    $(esconder).removeClass("escondido");
    $(mostrar).addClass("escondido");
    $(desativar).val(0);
    $(desativar).attr("disabled","disabled");
    $(ativar).attr("disabled","");

    if (seletor == 'projeto') {
        carregarLagoa();
    }

    if (seletor == 'lagoa') {
        carregarPontoAmostral();
    }

    if (seletor == 'categoria') {
        $('.parametro_categoria_extra').remove();
    }
}

function fechaNovo(seletor) {
    var esconder = "#" + seletor + "_selecionar";
    var mostrar = "#" + seletor + "_inserir";
    var desativar = "input[name=nome_" + seletor + "]";
    var ativar = "select[name=id_" + seletor + "]";

    $(esconder).removeClass("escondido");
    $(mostrar).addClass("escondido");
    $(desativar).val('');
    $(desativar).attr("disabled","disabled");
    $(ativar).attr("disabled","");
    $(ativar).val(0);

    if (seletor == 'categoria') {
        $('#id_categoria_extra').val(0);
        $('.parametro_categoria_extra').remove();
    }
}

function novoParametro() {
    var i = $('#count').val();
    var dir = $('#dir').val();
    var idCategoria = $('#id_categoria').val();

    $('#count').val(++i);
    
    $.get(dir + '/GerenciarColeta/ajaxNovoParametro/' + i + '/' + idCategoria, function (conteudo) {
        $("#parametro_inserir").append(conteudo);
    });
}

function parametroCategoriaExtra() {
    var dir = $('#dir').val();

    $.get(dir + '/GerenciarColeta/ajaxParametroCategoriaExtra/' + $(this).val(), function (conteudo) {
        $('.parametro_categoria_extra').remove();
        if (conteudo != '') {
            $(".campos_parametros").append(conteudo);
        }
    });
}

function parametroNovaCategoriaExtra() {
    var dir = $('#dir').val();

    $.get(dir + '/GerenciarColeta/ajaxParametroNovaCategoriaExtra/' + $(this).val(), function (conteudo) {
        $('.parametro_categoria_extra').remove();
        if (conteudo != '') {
            $(".campos_parametros").append(conteudo);
        }
    });
}

function removeParametro() {
    var parametro = $(this).attr('alt');

    $("#" + parametro ).remove();
}

function carregarLagoa() {
    var dir = $('#dir').val()
    $("#lagoa_selecionar").load( dir + "/GerenciarLagoa/montarSelect/" + $(this).val() );
    $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarSelect/-1" );
}

function carregarPontoAmostral() {
    var dir = $('#dir').val()
    $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarSelect/" + $(this).val() );
}

function itensExtra() {
    var dir = $('#dir').val()
    var seletor = $(this).attr('alt');
    $.getJSON(dir + '/GerenciarColeta/jsonNovoParametroExtra/' + $(this).val(), function (dados) {
        if (dados.nome != 'nenhum') {
            addItensExtra(dados, seletor);
        }
    });
}

function addItensExtra(dados, seletor) {
    var conteudo = '<input type="text" name="item[]" id="item' + dados.id_parametro_extra + '" value="" size="15"/>'
                 + '<input type="button" value="-" class="btnAddItemExtra"/><br/>'
                 + '<input type="button" value="+" class="btnRemItemExtra"/>';
    $('#' + seletor).append(conteudo);
}

// Comportamentos 
function onLoad() {
    $('.novo_item').livequery('click', novoParametro);

    $('.cancelar_item').livequery('click', removeParametro);

    $('#id_projeto').livequery('change', carregarLagoa);

    $('#id_lagoa').livequery('change', carregarPontoAmostral);

    $('#id_categoria').livequery('change', parametroCategoriaExtra);

    $('#id_categoria_extra').livequery('change', parametroNovaCategoriaExtra);

    $('.parametro_extra').livequery('change', itensExtra);

    $('.novo').livequery('click', function() {
        seletor = $(this).attr('alt');
        switch (seletor) {
            case 'projeto':
                abreNovo('lagoa');
                abreNovo('ponto_amostral');
                break;

            case 'lagoa':
                abreNovo('ponto_amostral');
                break;
        }
        abreNovo(seletor);
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
