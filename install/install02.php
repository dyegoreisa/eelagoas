<?php
if (
    isset($_POST['DB_DRIVER']) && $_POST['DB_DRIVER'] != '' &&
    isset($_POST['DB_HOST']) && $_POST['DB_HOST'] != '' &&
    isset($_POST['DB_NAME']) && $_POST['DB_NAME'] != '' &&
    isset($_POST['DB_USER']) && $_POST['DB_USER'] != '' &&
    isset($_POST['DB_PASSWD']) && $_POST['DB_PASSWD'] != ''
) {

    session_start();
    
    $_SESSION['DB_DRIVER'] = $_POST['DB_DRIVER'];
    $_SESSION['DB_HOST']   = $_POST['DB_HOST'];  
    $_SESSION['DB_NAME']   = $_POST['DB_NAME'];  
    $_SESSION['DB_USER']   = $_POST['DB_USER'];  
    $_SESSION['DB_PASSWD'] = $_POST['DB_PASSWD'];

    $config[] = "define('DB_DRIVER', '{$_POST['DB_DRIVER']}');\n";
    $config[] = "define('DB_HOST',   '{$_POST['DB_HOST']}');\n";
    $config[] = "define('DB_NAME',   '{$_POST['DB_NAME']}');\n";
    $config[] = "define('DB_USER',   '{$_POST['DB_USER']}');\n";
    $config[] = "define('DB_PASSWD', '{$_POST['DB_PASSWD']}');\n";
    
    $fileName = 'config.inc.php';
    $baseConfig = file($fileName);
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
    <label for="DB_DRIVER">Driver:</label>
    <input type="text" id="DB_DRIVER" name="DB_DRIVER" value="mysql"/>
    <br/>
    <label for="DB_HOST">Host:</label>
    <input type="text" id="DB_HOST" name="DB_HOST"/>
    <br/>
    <label for="DB_NAME">Nome:</label>
    <input type="text" id="DB_NAME" name="DB_NAME" value="ecolagoas"/> Certifique-se de criar o banco com este nome.
    <br/>
    <label for="DB_USER">Usu&aacute;rio:</label>
    <input type="text" id="DB_USER" name="DB_USER"/> Certifique-se de que o usu&aacute;rio tenha todas as pemiss&otilde;es no banco criado acima.
    <br/>
    <label for="DB_PASSWD">Senha:</label>
    <input type="text" id="DB_PASSWD" name="DB_PASSWD"/>
    <br/>
    <input type="hidden" name="banco" value="inserir"/>
    <input type="submit" value="Enviar"/>
</form>
</body>
</html>
