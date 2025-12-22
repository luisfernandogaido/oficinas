<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <span class="banner">Tempo gasto</span>
    </header>

    <div id="controles">
        <button class="flat" id="b-hoje">Hoje</button>
        <button class="flat" id="b-modo">Dia</button>
        <button class="navigate-before"></button>
        <button class="navigate-next"></button>
        <input type="hidden" id="modo" value="<?= $modo ?>">
        <input type="hidden" id="dia" value="<?= $dia ?>">
        <span id="periodo"></span>
    </div>

    <div id="projetos"></div>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>