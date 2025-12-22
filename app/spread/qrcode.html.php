<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="qrcode.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">QR Code</div>
    </header>

    <div id="qrcode" class="hidden">
        <h1><?= $texto ?></h1>
        <br>
        <img>
        <div class="instrucoes hidden">
            <p>
                Peça para seu cliente apontar a câmera.
            </p>
            <p>
                Você também pode imprimir este QR code e exibir em seu estabelecimento.
            </p>
        </div>
    </div>
    <br>
    <div class="botoes hidden">
        <button id="button-print" class="primario">Imprimir</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const link = '<?= $link ?>';
      const texto = '<?= $texto ?>';
      const instrucoes = <?= $instrucoes ? 'true' : 'false' ?>;
    </script>
    <script type="module" src="qrcode.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>