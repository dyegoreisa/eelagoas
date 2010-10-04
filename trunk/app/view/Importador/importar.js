function onLoad() {
    $('#mostrar_log_mensagem > a').click(function() {
        $('.log_mensagem').removeClass('escondido'); 
        $('#esconder_log_mensagem').removeClass('escondido');
        $('#mostrar_log_mensagem').addClass('escondido');
    });

    $('#esconder_log_mensagem > a').click(function() {
        $('.log_mensagem').addClass('escondido');
        $('#mostrar_log_mensagem').removeClass('escondido');
        $('#esconder_log_mensagem').addClass('escondido');
    })
}
