<?php
use templates\Gaido;

/* @var $this templates\Gaido */
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="<?= Gaido::VIEWPORT ?>, interactive-widget=<?= $this->interactiveWidget ?>">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title><?= e($this->getTitulo()) ?></title>
    <link rel="stylesheet" href="<?= SITE ?>core/templates/gaido/css/gaido.css?v=20230302-1641">
    <?php include RAIZ . 'tpl/head.html.php' ?>
    <link rel="stylesheet" href="<?= SITE ?>app/app.css">
    <?= $this->css ?>
    <?php include RAIZ . 'core/templates/gaido/pixel.html.php' ?>
    <?= $this->head ?>
</head>
<body class="oculto">
<header>
    <button class="menu"></button>
    <a id="logo" href="<?= SITE ?>app/index.php"></a>
    <?php if (Aut::logado()): ?>
        <span class="nome"><?= e(Aut::nomeReal() ?? '') ?></span>
        <div class="buttons">
            <button class="person-pin"></button>
            <div>
                <a class="button nome"
                   href="<?= SITE ?>app/usuarios/conta-usuario.php">
                    <?= Aut::nomeReal() ?? 'Conta' ?>
                </a>
                <a class="button gerenciar"
                   href="<?= SITE ?>app/usuarios/conta-usuario.php">
                    Conta
                </a>
                <?php if (Aut::$assinatura): ?>
                    <a class="button"
                       href="<?= SITE ?>app/assinatura/index.php">
                        Assinatura
                    </a>
                <?php endif; ?>
                <?php if (Aut::$codPersonificador): ?>
                    <a class="button" href="<?= SITE ?>app/usuarios/despersonifica.php">Despersonificar</a>
                <?php endif; ?>
                <a class="button" href="<?= SITE ?>app/entrar/index.php?trocar=1">Trocar de conta</a>
                <?php if (!Aut::provisorio() || Aut::$codPersonificador): ?>
                    <a class="button" href="<?= SITE ?>app/entrar/sai.php">Sair</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <a class="button input" href="<?= SITE ?>app/entrar/index.php"></a>
    <?php endif; ?>
    <?php include RAIZ . 'app/menu.html.php' ?>
</header>
<main>
    <?= $this->main ?>
    <div id="regiao-botoes" class="oculta"></div>
</main>
<footer>
    <?= $this->footer ?>
</footer>
<div id="alerta">
    <div class="msg"></div>
    <div class="act"></div>
</div>
<div id="loading"></div>
<script>
  var SITE = '<?= SITE ?>'
  var PERFIL = '<?= Aut::$perfil ?>'
  var APP = '<?= APP ?>'
  var TEMA_PROFINANC = Boolean(<?= Sistema::$temaProfinanc ? 'true' : 'false'?>)
</script>
<script type="module" src="<?= SITE ?>core/templates/gaido/js/gaido.js"></script>
<?= $this->js ?>
<!--Executado em <?= number_format(deltaT(), 6, ',', '') ?> segundos-->
</body>
</html>