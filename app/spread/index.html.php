<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css?v=20230228-2032">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Espalhe</b></div>
    </header>

    <div id="share">
        <div class="meios">
            <a class="meio share">
                <img src="share.png">
                <p>Enviar para</p>
            </a>
            <a class="meio copy">
                <img src="linked.png">
                <p>Copiar link</p>
            </a>
            <a class="meio qrcode" href="qrcode.php?link=<?= urlencode($link) ?>">
                <img src="qr-scan.png">
                <p>QR code</p>
            </a>
        </div>
        <br>
    </div>


<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const link = '<?= $link ?>';
      const texto = '<?= $texto ?>';
    </script>
    <script type="module" src="index.js?v=20230228-2032"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>