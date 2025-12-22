<?php
use modelo\Usuario;

/**
 * @var Usuario $usuario
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>E-mail de verificação</title>
    <style>
        a {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<p>
    Olá, <?= e($usuario->nome) ?>!
</p>
<p>
    Para concluir a criação da sua conta, confirme que nós enviamos corretamente o e-mail a você.
</p>
<p>
    <a href="<?= SITE ?>app/entrar/verificacao.php?token=<?= $token ?>">Verificar seu e-mail</a>
</p>
</body>
</html>