<?php

?>

<?php $template = new templates\Gaido() ?>
<?php $template->titulo  = 'Minidocs' ?>
<?php $template->favicon = 'core/templates/gaido/img/home/minidocs.png' ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <input type="search" id="search">
        <button class="theme"></button>
        <button class="mais"></button>
    </header>

    <a href="novo.php" class="button novo"></a>

    <div id="docs" class="cards"></div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>