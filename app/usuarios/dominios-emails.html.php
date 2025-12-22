<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="dominios-emails.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Domínios</div>
    </header>

    <table class="">
        <tbody>
        <?php foreach ($dominios as $d): ?>
            <tr>
                <td><?= $d['dominio'] ?></td>
                <td class="num"><?= $d['count'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="dominios-emails.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>