<?php
require 'config.inc.php';
// Verifica se o aquivo de configurações esta instalado
if (is_file('../config/config.inc.php')) {
    $mensagem[] = 'O sistema j&aacute; est&aacute; instalado.';
    $mensagem[] = "Para reinstalar o sistema apague o arquivo 'config/config.inc.php'";
    $mensagem[] = "Caso  n&atilde;o queira reinstalar renomeie a pasta 'install/'.";
    $mensagem[] = 'Tente novamente <a href="../index.php">clicando aqui.</a>';
    $negado = -1;
} else {
    $negado = 0;
    // Verifica se tem permissão de escrita nas pasta dos templates
    if (@file_put_contents('../' . COMPILED . '/teste.txt', 'teste') !== false) {
        @exec('rm -rf ../' . COMPILED . '/*');
        $mensagem[] = "Pasta '" . COMPILED . "' com acesso <span style=\"color:green\">liberado</span>.";
    } else {
        $mensagem[] = "Pasta '" . COMPILED . "' com acesso <span style=\"color:red\">negado</span>.";
        $negado++;
    }

    $plugin_c = 'plugin/report/render/templates_c';
    if (@file_put_contents("../{$plugin_c}/teste.txt", 'teste') !== false) {
        @exec("rm -rf ../{$plugin_c}/*");
        $mensagem[] = "Pasta '{$plugin_c}' com acesso <span style=\"color:green\">liberado</span>.";
    } else {
        $mensagem[] = "Pasta '{$plugin_c}' com acesso <span style=\"color:red\">negado</span>.";
        $negado++;
    }

    $config_c = 'config';
    if (@file_put_contents("../{$config_c}/teste.txt", 'teste') !== false) {
        @exec("rm -rf ../{$config_c}/*");
        $mensagem[] = "Pasta '{$config_c}' com acesso <span style=\"color:green\">liberado</span>.";
    } else {
        $mensagem[] = "Pasta '{$config_c}' com acesso <span style=\"color:red\">negado</span>.";
        $negado++;
    }
}
?>
<html>
<head>
    <title>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</title>
</head>
<body>
<h2>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</h2>
<?= implode('<br>', $mensagem) ?>
<? if ($negado == 0): ?>
    <p><a href="install02.php">Proximo</a></p>
<? elseif ($negado != -1): ?>
    <p>De permiss&atilde;o de acesso as pastas acima e tente novamente.</p>
<? endif ?>
</body>
</html>
