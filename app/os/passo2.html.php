<?php
use modelo\Quando;
use modelo\Os;

/** @var Os $os */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo2.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Quando</div>
    </header>
    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <div class="campo quando">
                <div class="rotulo">
                    <label>Quando ocorreu?</label>
                </div>
                <div class="controle checks">
                    <?php foreach (Quando::cases() as $quando): ?>
                        <label>
                            <input type="radio"
                                   name="quando"
                                   value="<?= $quando->value ?>"
                                   required
                                <?= $quando == $os->quando ? 'checked' : '' ?> >
                            <?= $quando->label() ?>
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
    <script type="module" src="passo2.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>