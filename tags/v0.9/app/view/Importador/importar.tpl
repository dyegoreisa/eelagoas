<script type="text/javascript" language="javascript" src="{$site}/app/view/Importador/importar.js"></script>
<script type="text/javascript" language="javascript">
$(document).ready(onLoad);
</script>
{if $mensagem neq ""}
<span id="mostrar_log_mensagem">[ <a href="#">Log de mensagens</a> ]</span>
<span id="esconder_log_mensagem" class="escondido">[ <a href="#">Fechar</a> ]</span>
<div class="log_mensagem escondido">
    <div class="scroll_log"><pre>{$mensagem}</pre></div>
</div>
{/if}
<fieldset>
    <legend>Importar Excel</legend>

    <p>{$msg}</p>

</fieldset>
[ <a href="{$site}/index.php/Importador/selecionar">Voltar</a> ] 
