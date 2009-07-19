{literal}
  <script type="text/javascript" language="javascript">
  {/literal} dir = '{$dir}'; {literal}
    $(document).ready(function () {

      $(".novo").livequery( 'click', function(e) {
        seletor = $(this).attr('alt');

        esconder = "#" + seletor + "_inserir";
        mostrar = "#" + seletor + "_selecionar";
        desativar = "select[name=id_" + seletor + "]";
        ativar = "input[name=nome_" + seletor + "]";

        $(esconder).removeClass("escondido");
        $(mostrar).addClass("escondido");
        $(desativar).attr("disabled","disabled");
        $(ativar).attr("disabled","");
      });

      $(".cancelar").livequery( 'click', function(e) {
        seletor = $(this).attr('alt');

        esconder = "#" + seletor + "_selecionar";
        mostrar = "#" + seletor + "_inserir";
        desativar = "input[name=nome_" + seletor + "]";
        ativar = "select[name=id_" + seletor + "]";

        $(esconder).removeClass("escondido");
        $(mostrar).addClass("escondido");
        $(desativar).val('');
        $(desativar).attr("disabled","disabled");
        $(ativar).attr("disabled","");
      });

      $(".novo_item").livequery( 'click', function(e) {
        i++;

        conteudo = '<span id="parametro' + i + '"><br>'
                 + '<input type="text" name="nome_parametros[]" value="">'
                 + '<div class="campos_parametros">'
                 + '<label>N&iacute;vel: <input type="text" name="nivel_novo[]" size="10"></label>'
                 + '<br>'
                 + '<label>Valor: <input type="text" name="valor_novo[]" size="10"></label>'
                 + '<br />'
                 + '</div>'
                 + '<input type="button" class="cancelar_item" alt="parametro' + i + '" value="Cancelar">'
                 + '</span>';
        $("#parametro_inserir").append( conteudo );
      });

      $(".cancelar_item").livequery( 'click', function(e) {
        parametro = $(this).attr('alt');

        $("#" + parametro ).remove();
      });


      $("#id_lagoa").change( function () {
        $("#ponto_amostral_selecionar").load( dir + "/GerenciarPontoAmostral/montarSelect/" + $(this).val() );
      });

      $(":checkbox").click( function() {
        campos = $(this).attr('alt');

        if( $(this).attr('checked') ) {
          $("#" + campos).removeClass('escondido');
          $("#n_" + campos).attr("disabled","");
          $("#v_" + campos).attr("disabled","");
        } else {
          $("#" + campos).addClass('escondido');
          $("#n_" + campos).attr("disabled","disabled");
          $("#v_" + campos).attr("disabled","disabled");
        }
      });

    });

    i = 0;
  </script>
  <style>
    .escondido{
      display: none;
    }
    .campos_parametros{
      padding-left: 20px;
    }
  </style>
{/literal}
<fieldset>
<legend>Cadastrar Coleta</legend>

<form action="{$dir}/GerenciarColeta/salvar" method="POST" class="cmxform">

  <label for="data">Data da coleta:</label><br/>
  <input type="text" name="data" id="data" value="{$coleta.data}">
  <br/><br/>

  <label for="nome_lagoa">Lagoa:</label><br/>
  <span id="lagoa_selecionar">
    <select name="id_lagoa" id="id_lagoa">
      <option value="-1"> -- [Selecione] -- </option>
      {html_options options=$select_lagoa selected=$coleta.id_lagoa}
    </select>
    <input type="button" class="novo" alt="lagoa" value="Novo">
  </span>
  <span id="lagoa_inserir" class="escondido">
    <input type="text" name="nome_lagoa" id="nome_lagoa" value="" disabled>
    <input type="button" class="cancelar" alt="lagoa" value="Cancelar">
  </span>
  <br/><br/>

  <label for="nome_ponto_amostral">Ponto amostral:</label><br/>
  <span id="ponto_amostral_selecionar">
    <select name="id_ponto_amostral" id="id_ponto_amostral">
      <option value="-1"> -- [Selecione] -- </option>
      {html_options options=$select_ponto_amostral selected=$coleta.id_ponto_amostral}
    </select>
    <input id="button_ponto_amostral" type="button" class="novo" alt="ponto_amostral" value="Novo">
  </span>
  <span id="ponto_amostral_inserir" class="escondido">
    <input type="text" name="nome_ponto_amostral" id="nome_ponto_amostral" value="" disabled>
    <input type="button" class="cancelar" alt="ponto_amostral" value="Cancelar">
  </span>
  <br/><br/>

  <label for="nome_categoria">Categoria:</label><br/>
  <span id="categoria_selecionar">
    <select name="id_categoria" id="id_categoria">
      <option value="-1"> -- [Selecione] -- </option>
      {html_options options=$select_categoria selected=$coleta.id_categoria}
    </select>
    <input type="button" class="novo" alt="categoria" value="Novo">
  </span>
  <span id="categoria_inserir" class="escondido">
    <input type="text" name="nome_categoria" id="nome_categoria" value="" disabled>
    <input type="button" class="cancelar" alt="categoria" value="Cancelar">
  </span>
  <br/><br/>

  <fieldset>
    <legend>Parametro:</legend>

    {foreach from=$select_parametro key=id_parametro item=parametro}

      <label>
        <input type="checkbox" name="id_parametros[]" value="{$id_parametro}" alt="valor{$id_parametro}" {if $parametro.id_coleta_parametro neq ""} checked {/if} />{$parametro.nome}
      </label>
      <br>

      {if $parametro.id_coleta_parametro neq ""}
        <div class="campos_parametros" id="valor{$id_parametro}">
          <label>N&iacute;vel: <input type="text" id="n_valor{$id_parametro}" name="nivel[]" size="10" value="{$parametro.valor}"></label>
          <br>
          <label>Valor: <input type="text" id="v_valor{$id_parametro}" name="valor[]" size="10" value="{$parametro.nivel}"></label>
          <br />
        </div>
      {else}
        <div class="campos_parametros escondido" id="valor{$id_parametro}">
          <label>N&iacute;vel: <input type="text" id="n_valor{$id_parametro}" name="nivel[]" size="10" disabled></label>
          <br>
          <label>Valor: <input type="text" id="v_valor{$id_parametro}" name="valor[]" size="10" disabled></label>
          <br />
        </div>
      {/if}

    {/foreach}

    <input type="button" class="novo_item" alt="parametro" value="Novo">

    <span id="parametro_inserir">
       
    </span>

  </fieldset>
  <br/>
  
  {if $coleta.id_coleta neq ""}
    <input type="hidden" name="id_coleta" value="{$coleta.id_coleta}">
  {/if}
  <input type="submit" value="Salvar">

<form>

</fieldset>
