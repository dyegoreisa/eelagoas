<?php
if (is_file('../config/instaled')) {
    header('Location: ../index.php');
}
file_put_contents('../config/instaled', '1', FILE_APPEND);
?>
<html>
<head>
    <title>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</title>
</head>
<body>
<h2>Instala&ccedil;&atilde;o do sistema Limnologia UFRJ</h2>
<p>Sistema instalado!</p>
<p>Use o Login: admin e Senha: admin</p>
<p><a href="../index.php">clique aqui</a> para acessar o sistema</p>
</body>
</html>
