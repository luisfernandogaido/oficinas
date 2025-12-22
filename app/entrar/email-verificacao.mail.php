<?php
use templates\email\Deschamps;

?>
<?php $template = new Deschamps() ?>
<?php $template->iniBody(); ?>

    <p style="<?= $template->pStyle ?>">
        Olá, <b><?= e($usuario->nome) ?></b>!
    </p>
    <p style="<?= $template->pStyle ?>">
        Para concluir a criação da sua conta, clique no link abaixo.
    </p>
    <p style="<?= $template->pStyle ?>">
        <a href="<?= SITE ?>app/entrar/verificacao.php?token=<?= $token ?>">Verificar seu e-mail</a>
    </p>

<?php $template->fimBody(); ?>
<?php $template->renderiza(); ?>