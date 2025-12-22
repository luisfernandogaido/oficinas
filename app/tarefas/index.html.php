<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <input type="search" id="txt" placeholder="Pesquisar tarefas">
        <button class="flat arquivadas"></button>
        <a class="button flat" href="tempo/index.php">Tempo gasto</a>
        <input type="hidden" id="arquivadas" value="0">
        <button class="theme"></button>
    </header>

    <div id="tarefas"></div>

    <a href="tarefa.php" class="button novo"></a>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>