<?php
use modelo\Usuario;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="../core/templates/gaido/css/home.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="install.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

<?php if (Aut::$perfil == Usuario::PERFIL_MASTER): ?>
    <?php include 'master.html.php' ?>
<?php elseif (Aut::$perfil == Usuario::PERFIL_PADRAO): ?>
    <?php include 'padrao.html.php' ?>
<?php endif; ?>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js?v=3"></script>
    <script type="module" src="install.js?v=4"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>