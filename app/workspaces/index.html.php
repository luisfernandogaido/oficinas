<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css?v=3">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <header>
        <button class="voltar"></button>
        <div class="banner">Workspaces</div>
    </header>
    <div class="resultado">
        <table>
            <tbody>
            <?php foreach ($wss as $ws): ?>
                <tr>
                    <td>
                        <div>
                            <img src="<?= $ws['logo'] ?>" alt="">
                        </div>
                    </td>
                    <td><?= e($ws['codigo']) ?></td>
                    <td><?= e($ws['nome']) ?></td>
                    <td class="acoes">
                        <a href="workspace.php?codigo=<?= $ws['codigo'] ?>" class="button edit"></a>
                        <a href="../usuarios_new/usuario.php?codigo=<?= $ws['cod_criador'] ?>"
                           class="button person"></a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <a href="workspace.php" class="button novo"></a>
<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>