<?php
use templates\email\Deschamps;

?>
<?php $template = new Deschamps() ?>
<?php $template->iniBody(); ?>

    <p style="<?= $template->pStyle ?>">
        Olá, <b><?= $nome ?></b>!
    </p>
    <p style="<?= $template->pStyle ?>">
        Acesse o aplicativo clicando no link abaixo.
    </p>
    <p style="<?= $template->pStyle ?>">
        <a href="<?= SITE ?>app/usuarios/token-use.php?token=<?= $token ?>">Clique aqui</a>
    </p>

<?php $template->fimBody(); ?>
<?php $template->renderiza(); ?>