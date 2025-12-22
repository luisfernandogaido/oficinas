<?php
use templates\email\Deschamps;

?>
<?php $template = new Deschamps() ?>
<?php $template->iniBody(); ?>

    <p style="<?= $template->pStyle ?>">
        Clique no link abaixo para criar uma nova senha.
    </p>

    <p style="<?= $template->pStyle ?>">
        <a href="<?= $site ?>app/entrar/redefinir.php?token=<?= $token ?>">Redefinir senha</a>
    </p>

<?php $template->fimBody(); ?>
<?php $template->renderiza(); ?>