<?php
session_start();
$negado = 0;
// Verificar dados do banco para conexao
try {
    $dsn = "{$_SESSION['DB_DRIVER']}:host={$_SESSION['DB_HOST']};dbname={$_SESSION['DB_NAME']}";
    $dbh = new PDO($dsn, $_SESSION['DB_USER'], $_SESSION['DB_PASSWD']);
} catch (PDOException $e) {
    $mensagem[] = 'N&atilde;o foi poss&iacute;vel estabelecer uma conex&atilde;o com banco de dados.';
    $negado++;
}

// criar as tabelas
if ($negado == 0) {
    $ddl = file('../database/ddl.sql');
    $create = '';
    foreach ($ddl as $linha) {
        if (preg_match('/CREATE\ TABLE/', $linha)) {
            $create = $linha;
        } elseif(!preg_match('/ENGINE=InnoDB/', $linha)) {
            $create .= " $linha ";
        } else {
            $create .= str_replace(';', '', " $linha ");
            if ($dbh->exec($create) === false) {
                $negado++;
            }
        }
    }

    if ($negado == 0) {
        $dadosIniciais = file('../database/dados_iniciais.sql');
        foreach ($dadosIniciais as $linha) {
            if ($dbh->exec(str_replace(';', '', $linha)) === false) {
                $negado++;
            }
        }
    } else {
        $mensagem[] = 'N&atilde;o foi poss&iacute;vel criar as tabelas.';
    }

    if ($negado > 0) {
        $mensagem[] = 'N&atilde;o foi poss&iacute;vel inserir os dados iniciais.';
    } else {
        header('Location: install04.php');
    }
}
?>
<html>
<head>
    <title>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</title>
</head>
<body>
<h2>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</h2>
<? if ($negado != 0): ?>
    <p style="color:red"><?= implode('<br/>', $mensagem) ?></p>
    <p><a href="install02.php">Reconfigurar dados de conex&atilde;o</a></p>
<? endif ?>
</body>
</html>
