<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="assine.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
    </header>

    <form>
        <h1><?= $titulo ?></h1>
        <div class="campos">
            <div class="campo">
                <img src="../../core/templates/gaido/img/gaido.png">
            </div>
            <p class="argumento">
                Um argumento bem convincente.
            </p>
            <br>
            <br>
            <div class="campo" id="campo-periodo">
                <div class="controle checks">
                    <label>
                        <input type="radio" name="periodo" value="0 months 1 days" checked>
                        24 horas
                    </label>
                    <label>
                        <input type="radio" name="periodo" value="0 months 7 days">
                        7 dias
                    </label>
                    <label>
                        <input type="radio" name="periodo" value="1 months 0 days">
                        1 mês
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo" id="campo-valor">
                <div class="controle">
                    <span>R$ 20,00</span>
                </div>
            </div>
            <div class="campo" id="campo-dica">
                <p class="dica">
                    <!--                    Quanto mais longa sua assinatura, mais você economiza-->
                </p>
            </div>
            <div class="campo" id="campo-tipo">
                <div class="controle checks">
                    <label>
                        <input type="radio" name="tipo" value="PIX" checked>
                        Pix
                    </label>
                    <label>
                        <input type="radio" name="tipo" value="CREDIT_CARD">
                        Cartão de crédito
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <br>
        <div class="botoes">
            <button class="primario" id="button-gerar-cobranca">Gerar cobrança</button>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const valores = JSON.parse('<?= $valores ?>')
    </script>
    <script type="module" src="assine.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>