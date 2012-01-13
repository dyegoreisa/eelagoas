<?php
if (is_file('../config/instaled')) {
    header('Location: ../index.php');
}

function senha($texto) {
    $vogais  = array('/[Aa]/', '/[Ee]/', '/[Ii]/', '/[Oo]/', '/[Uu]/');
    $numeros = array(4, 3, 1, 0, 8);
    $senha   = preg_replace($vogais, $numeros, $texto);

    return $senha;
}

session_start();

$pasta = $_SESSION['pasta'];

if (
    isset($_POST['dir'])            && $_POST['dir']            != '' &&
    isset($_POST['DB_DRIVER'])      && $_POST['DB_DRIVER']      != '' &&
    isset($_POST['DB_HOST'])        && $_POST['DB_HOST']        != '' &&
    isset($_POST['DB_NAME'])        && $_POST['DB_NAME']        != '' &&
    isset($_POST['DB_USER'])        && $_POST['DB_USER']        != '' &&
    isset($_POST['DB_PASSWD'])      && $_POST['DB_PASSWD']      != '' &&
    isset($_POST['DB_USER_ROOT'])   && $_POST['DB_USER_ROOT']   != '' &&
    isset($_POST['DB_PASSWD_ROOT']) && $_POST['DB_PASSWD_ROOT'] != ''
) {
    $_SESSION['DB_DRIVER']      = $_POST['DB_DRIVER'];
    $_SESSION['DB_HOST']        = $_POST['DB_HOST'];  
    $_SESSION['DB_NAME']        = $_POST['DB_NAME'];  
    $_SESSION['DB_USER']        = $_POST['DB_USER'];  
    $_SESSION['DB_PASSWD']      = $_POST['DB_PASSWD'];
    $_SESSION['DB_USER_ROOT']   = $_POST['DB_USER_ROOT'];  
    $_SESSION['DB_PASSWD_ROOT'] = $_POST['DB_PASSWD_ROOT'];

    $config[] = "define('DB_DRIVER', '{$_POST['DB_DRIVER']}');\n";
    $config[] = "define('DB_HOST',   '{$_POST['DB_HOST']}');\n";
    $config[] = "define('DB_NAME',   '{$_POST['DB_NAME']}');\n";
    $config[] = "define('DB_USER',   '{$_POST['DB_USER']}');\n";
    $config[] = "define('DB_PASSWD', '{$_POST['DB_PASSWD']}');\n";
    
    $fileName = 'config.inc.php';
    $baseConfig = file($fileName);

    $comparacao = "define('ROOT_FOLDER', '');\n";

    foreach ($baseConfig as &$linha) {
        if ($linha == $comparacao) {
            $linha = "define('ROOT_FOLDER', '/{$_POST['dir']}');\n";
        }
    }

    file_put_contents("../config/{$fileName}", array_merge($baseConfig, $config));

    header('Location: install03.php');
} else {
    $mensagem[] = 'Todos os campos devem ser preenchidos.';
}
?>
<html>
<head>
    <title>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</title>
</head>
<body>
<h2>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</h2>
<? if (isset($mensagem) && isset($_POST['banco']) && $_POST['banco'] == 'inserir'): ?>
    <p style="color:red"><?= implode('<br>', $mensagem) ?></p>
<? endif ?>
<p>Prencha os campos abaixo:</p>
<form action="install02.php" method="POST">
<fieldset>
    <legend>Dados do Sistema</legend>
    <label for="dir">Diret&oacute;rio:</label>
    <input type="text" id="dir" name="dir" value="<?= $pasta; ?>"/>
    <br/>
    <label for="DB_DRIVER">Driver:</label>
    <input type="text" id="DB_DRIVER" name="DB_DRIVER" value="mysql"/>
    <br/>
</fieldset>
<fieldset>
    <legend>Dados do MySQL</legend>
    <label for="DB_HOST">Host:</label>
    <input type="text" id="DB_HOST" name="DB_HOST"/>
    <br/>
    <label for="DB_USER_ROOT">Usu&aacute;rio root:</label>
    <input type="text" id="DB_USER_ROOT" name="DB_USER_ROOT" value="root"/> Este dado n&atilde;o &eacute; armazenado.
    <br/>
    <label for="DB_PASSWD_ROOT">Senha de root:</label>
    <input type="password" id="DB_PASSWD_ROOT" name="DB_PASSWD_ROOT"/> Este dado n&atilde;o &eacute; armazenado.
    <br/>
</fieldset>
<h3>Se existir outro banco com o mesmo nome ele ser&aacute; apagado!</h3>
<fieldset>
    <legend>Dados gerados para o sistema</legend>
    <label for="DB_NAME">Nome do banco:</label>
    <input type="text" id="DB_NAME" name="DB_NAME" value="<?= $pasta; ?>"/>
    <br/>
    <label for="DB_USER">Usu&aacute;rio:</label>
    <input type="text" id="DB_USER" name="DB_USER" value="<?= $pasta; ?>"/>
    <br/>
    <label for="DB_PASSWD">Senha:</label>
    <input type="text" id="DB_PASSWD" name="DB_PASSWD" value="<?= senha($pasta); ?>"/>
    <br/>
</fieldset>
    <input type="hidden" name="banco" value="inserir"/>
    <input type="submit" value="Enviar"/>
</form>
</body>
</html>
