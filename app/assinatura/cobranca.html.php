<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="cobranca.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
    </header>

    <form>
        <div class="campos">
            <div class="campo">
                <img src="../../tpl/img/chess-128.png">
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Descrição</label>
                </div>
                <div class="controle">
                    <span><?= $nome ?></span>
                </div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Valor</label>
                </div>
                <div class="controle">
                    <span>R$ <?= $valor ?></span>
                </div>
            </div>
            <div class="campo">
                <br>
                <p>
                    Caso você já tenha pago, aguarde nesta tela. A autorização será feita em breve.
                </p>
            </div>
        </div>
        <br>
        <div class="botoes">
            <a id="button-pagar" href="<?= $invoiceUrl ?>" target="_blank" class="button primario">Pagar</a>
        </div>
        <br>
        <div class="botoes">
            <button id="button-cancelar" class="button link">Cancelar cobrança</button>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="cobranca.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>