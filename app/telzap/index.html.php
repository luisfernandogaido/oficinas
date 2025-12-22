<?php
use modelo\Usuario;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Telefone -> WhatsApp</div>
    </header>

    <form>
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="numero">Número</label>
                </div>
                <div class="controle">
                    <input type="tel" id="numero" name="numero">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>
    <div id="qrcode">
        <img class="hidden">
    </div>
    <br>
    <div class="botoes">
        <button class="primario" id="button-abre">Abrir</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>