<?php
use modelo\Usuario;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <div id="preto">

    </div>

    <div id="container">
        <section>
            <div class="workspace-logo">
                <img src="<?= $ws->logo ?>">
            </div>
            <br>
            <h1><?= e($ws->nome) ?></h1>
        </section>
        <?php if ($endereco): ?>
            <section class="endereco">
                <?= nl2br(e($endereco)) ?>
            </section>
        <?php endif; ?>
        <?php if ($ws->descricao): ?>
            <section>
                <?= nl2br(e($ws->descricao)) ?>
            </section>
        <?php endif; ?>
    </div>
    <div class="botoes" id="flutuante">
        <button class="primario" id="solicitar-atendimento">Solicitar atendimento</button>
    </div>
<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const workspaceHash = '<?= $ws->hash ?>'
    </script>
    <script type="module" src="index.js?v=4"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>