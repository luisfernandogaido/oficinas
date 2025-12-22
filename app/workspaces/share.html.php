<?php
use modelo\Workspace;

/** @var Workspace $ws */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="share.css?v=3">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Divulgue seu estabelecimento</b></div>
    </header>

    <div class="wrapper">
        <p class="explicacao">
            Este é o link do seu estabelecimento. Divulgue para que seus clientes solicitem e acompanhem serviços.
        </p>
        <br>
        <div id="qrcode">
            <img src="">
        </div>
        <br>
        <p class="link">
            <a target="_blank" href="<?= $link ?>"><?= $link ?></a>
        </p>
        <br>
        <div class="botoes">
            <button id="copiar-link">Copiar link</button>
        </div>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const link = '<?= $link ?>'
    </script>
    <script type="module" src="share.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>