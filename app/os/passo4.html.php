<?php
use modelo\Os;
use modelo\Problema;

/** @var Os $os */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo4.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Observações</div>
    </header>
    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <?php if ($sintomas): ?>
                <div class="campo">
                    <div class="rotulo">
                        <label for="sintomas"><?= $rotuloSintomas ?></label>
                    </div>
                    <div class="controle">
                    <textarea name="sintomas"
                              id="sintomas"
                              maxlength="200"
                    ><?= e($os->sintomas) ?></textarea>
                    </div>
                    <div class="mensagem"></div>
                </div>
            <?php endif; ?>
            <?php if ($condicoes): ?>
                <div class="campo">
                    <div class="rotulo">
                        <label for="condicoes">O problema aparece em quais condições?</label>
                    </div>
                    <div class="controle">
                    <textarea name="condicoes"
                              id="condicoes"
                              maxlength="200"
                    ><?= e($os->condicoes) ?></textarea>
                    </div>
                    <div class="mensagem"></div>
                </div>
            <?php endif; ?>
            <?php if ($obs): ?>
                <div class="campo">
                    <div class="rotulo">
                        <label for="obs-cliente">Observações</label>
                    </div>
                    <div class="controle">
                    <textarea name="obs-cliente"
                              id="obs-cliente"
                              maxlength="200"
                    ><?= e($os->obsCliente) ?></textarea>
                    </div>
                    <div class="mensagem"></div>
                </div>
            <?php endif; ?>
        </div>
    </form>
    <br>
    <div class="botoes">
        <button id="prosseguir">Prosseguir</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'
    </script>
    <script type="module" src="passo4.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>