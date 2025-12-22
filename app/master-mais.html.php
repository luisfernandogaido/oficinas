<?php
use modelo\Usuario;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="../core/templates/gaido/css/home.css">
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Mais</b></div>
    </header>

    <div id="home">
        <a class="item" href="entrar/qrcode-sair.php">
            <span class="icon qrcode"></span>
            <span class="desc">
                <h2>QR code sair</h2>
                <p>
                    Use-o para forçar usuários já logados a fazer logout.
                    Útil em demonstrações com contas provisórias, que não podem fazer logout.
                </p>
            </span>
        </a>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
    <script type="module" src="install.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>