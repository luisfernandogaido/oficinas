<?php
use modelo\Frequencia;
use modelo\Os;

/** @var Os $os */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo3.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Frequência</div>
    </header>
    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <div class="campo frequencia">
                <div class="rotulo">
                    <label>Com que frequência o problema acontece?</label>
                </div>
                <div class="controle checks">
                    <?php foreach (Frequencia::cases() as $frequencia): ?>
                        <label>
                            <input type="radio"
                                   name="frequencia"
                                   value="<?= $frequencia->value ?>"
                                   required
                                <?= $frequencia == $os->frequencia ? 'checked' : '' ?> >
                            <?= $frequencia->label() ?>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'
    </script>
    <script type="module" src="passo3.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>