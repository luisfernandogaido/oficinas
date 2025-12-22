<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <span class="banner">Projetos</span>
    </header>

    <table>
        <thead>
        <tr>
            <th>Nome</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($projetos as $p): ?>
            <tr data-codigo="<?= $p['codigo'] ?>">
                <td><?= e($p['nome']) ?></td>
                <td class="acoes">
                    <a href="projeto.php?codigo=<?= $p['codigo'] ?>" class="button edit"></a>
                    <button class="delete"></button>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <a href="projeto.php" class="button novo"></a>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>