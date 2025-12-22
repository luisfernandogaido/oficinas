<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="qrcode-sair.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <header>
        <button class="voltar"></button>
        <div class="banner"><h1>QR code sair</h1></div>
    </header>

    <div id="qrcode" class="hidden">
        <img>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const link = '<?= $link ?>';
    </script>

    <script type="module" src="qrcode-sair.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>