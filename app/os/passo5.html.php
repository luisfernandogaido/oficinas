<?php
use modelo\Os;
use modelo\Problema;

/** @var Os $os */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo5.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Fotos e vídeos</div>
    </header>
    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <input type="file" id="arquivo" name="arquivo[]" accept="image/*,video/*" multiple>
        <?php if ($os->problema == Problema::FUNILARIA_PINTURA): ?>
            <p>
                Para funilaria, fotos são essenciais.
            </p>
            <p>
                Envie fotos de ângulos diferentes.
            </p>
        <?php else: ?>
            <p>
                Se fizer sentido, envie fotos ou vídeos curtos.
            </p>
            <p>
                Seja objetivo e vá direto ao ponto.
            </p>
        <?php endif; ?>
        <div>
            <button class="attach" id="anexar"></button>
        </div>
        <br>
        <div class="progresso">
            <div style="width: 60%">
                <span>50%</span>
            </div>
        </div>
    </form>
    <br>
    <div id="resultado"></div>
    <div class="botoes">
        <button id="prosseguir">Prosseguir</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'

      const maxSizeTotal = <?= $maxSizeTotal ?>

      const maxSizeTotalH = '<?= $maxSizeTotalH ?>'

    </script>
    <script type="module" src="passo5.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>