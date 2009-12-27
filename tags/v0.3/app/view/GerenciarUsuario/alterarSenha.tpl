{literal}
<script type="text/javascript">
    $(document).ready( function() {
        $("#alterar_senha").validate({
            rules: {
                senha_atual: 'required',
                nova_senha: 'required',
                confirma_senha: {
                    required: true,
                    equalTo: '[name=nova_senha]'
                }
            },
            messages: {
                senha_atual: 'N&atilde;o pode ser vazio.',
                nova_senha: 'N&atilde;o pode ser vazio.',
                confirma_senha: {
                    required: 'N&atilde;o pode ser vazio.',
                    equalTo: 'Deve ser igual ao campo Nova Senha.'
                }
            }
        });
    });
</script>
{/literal}
{if $mensagem neq ""}
    <p>{$mensagem}</p>
{/if}
<fieldset>
{include file=$submenu titulo='Alterar senha'}

<form action="{$dir}/GerenciarUsuario/salvarSenha" method="POST" class="cmxform" id="alterar_senha">

    <label for="login">Login:</label><br/>
    <input type="text" id="login" value="{$usuario.login}" readonly="readonly">
    <br/><br/>

    <label for="senha_atual">Senha Atual:</label><br/>
    <input type="password" name="senha_atual" id="senhya_atual" value="">
    <br/><br/>

    <label for="nova_senha">Nova Senha:</label><br/>
    <input type="password" name="nova_senha" id="nova_senha" value="">
    <br/><br/>

    <label for="confirma_senha">Confirmar senha:</label><br/>
    <input type="password" name="confirma_senha" id="confirma_senha" value="">
    <br/><br/>
    
    <input type="hidden" name="id_usuario" value="{$usuario.id_usuario}">

    <input type="submit" value="Salvar">

<form>
</fieldset>

