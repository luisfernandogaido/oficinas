<?php
use modelo\Os;
use modelo\Usuario;
use modelo\Veiculo;

/** @var Os $os */
/** @var Veiculo[] $veiculos */
/** @var Usuario $usu */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo7.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Quem é você?</div>
    </header>

    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="nome">Seu nome completo</label>
                </div>
                <div class="controle">
                    <input type="text" id="nome" name="nome" value="<?= e($usu->nomeReal()) ?>" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo validar-whatsapp">
                <div class="controle">
                    <div class="botoes" id="wrapper-enviar">
                        <button>Enviar código pelo WhatsApp</button>
                    </div>
                </div>
                <div class="rotulo" style="text-align: center">
                    <label>Com esse código saberemos quem é você e lhe manteremos informado.</label>
                </div>
            </div>
        </div>
    </form>


<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'

      const token = '<?= $token ?>'

      const destinatario = '<?= $whatsApp ?>'

    </script>
    <script type="module" src="passo7.js?v=4"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>