<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="projeto.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <div class="banner">
            Extrato de horas
            <?= e($projeto->getNome()) ?>
            <?= $mes ?>
        </div>
    </header>

    <div id="total">
        234:435
    </div>

    <table>
        <?php foreach ($tarefas as $t): ?>
            <tr data-tempo="<?= $t['tempo'] ?>">
                <td>
                    <?= e($t['tarefa']) ?>
                </td>
                <td class="num"><?= $t['tempo'] ?></td>
                <td class="acoes">
                    <?php if ($t['cards'][0]): ?>
                        <a class="button open-in-new" href="<?= $t['cards'][0] ?>" target="_blank"></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="projeto.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>