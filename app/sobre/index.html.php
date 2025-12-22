<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <h1><?= Sistema::$nome ?> <?= Sistema::$versao ?></h1>

    <p>
        Desenvolvido por
        <br>
        <a href="https://gaido.space/app/sobre/gaido.php?from=<?= Sistema::$app ?>">
            Luís Fernando Gaido
        </a>
    </p>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>