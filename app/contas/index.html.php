<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Contas</div>
    </header>

    <a href="conta.php" class="button novo"></a>

    <table>
        <tbody>
        <?php foreach ($contas as $c): ?>
            <tr data-codigo="<?= $c['codigo'] ?>" data-nome="<?= $c['nome'] ?>">
                <td><?= e($c['nome']) ?></td>
                <td class="acoes">
                    <button class="<?= $c['classe_ativa'] ?>"></button>
                    <a class="button edit" href="conta.php?codigo=<?= $c['codigo'] ?>"></a>
                    <button class="delete"></button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>