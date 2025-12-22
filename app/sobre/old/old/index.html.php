<?php $template = new templates\Elite() ?>

<?php $template->inicioCss() ?>
    <link rel="stylesheet" href="index.css?<?= CSSJSV ?>">
<?php $template->fimCss() ?>

<?php $template->inicioCorpo() ?>

    <h1><?= Sistema::$nome ?> - <?= Sistema::$versao ?></h1>
    <div class="card">

        <h2>Desenvolvido por</h2>

        <p>
            <a target="_blank" href="http://profinanc.com/">Profinanc</a>
        </p>
    </div>

<?php $template->fimCorpo() ?>

<?php $template->inicioJs() ?>
    <script src="index.js?<?= CSSJSV ?>"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>