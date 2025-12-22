<?php
use modelo\Usuario;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="<?= SITE ?>/app/minidocs/response.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <header>
        <div class="banner">REQUEST COM PATH DINAMICO</div>
    </header>
    <p class="path">
        <?= $path ?>
    </p>



<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="<?= SITE ?>/app/minidocs/response.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>