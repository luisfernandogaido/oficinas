<?php
use modelo\Problema;
use modelo\Os;

/** @var Os $os */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo1.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Novo atendimento</div>
    </header>
    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <div class="campo problema">
                <div class="rotulo">
                    <label>Problema principal</label>
                </div>
                <div class="controle checks">
                    <?php foreach (Problema::cases() as $problema): ?>
                        <label>
                            <input type="radio"
                                   name="problema"
                                   value="<?= $problema->value ?>"
                                   required
                                <?= $problema == $os->problema ? 'checked' : '' ?> >
                            <?= $problema->label() ?>
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
    <script type="module" src="passo1.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>