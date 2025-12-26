<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Assinantes</div>
    </header>

    <p class="registros">
        <?= count($vigentes) ?>
    </p>

    <table>
        <tbody>
        <?php foreach ($vigentes as $v): ?>
            <tr>
                <td>
                    <a href="../usuarios/usuario.php?codigo=<?= $v['cod_usuario'] ?>"><?= e($v['usuario']) ?></a>
                </td>
                <td><?= e($v['ini']) ?> <?= e($v['fim']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>