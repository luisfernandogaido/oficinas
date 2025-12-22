<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">
            <b>Painel</b>
        </div>
        <input type="search" id="search" placeholder="Pesquisar placa, nome, telefone ou marca/modelo" data-no-esc>
        <button class="theme"></button>
        <button class="history"></button>
    </header>

    <div id="chips" style="display: none">
        <button class="chip novas" data-pseudo-status="novas">
            3
            <br>
            Novas
        </button>
        <button class="chip pendentes" data-pseudo-status="pendentes">
            1
            <br>
            Pendentes
        </button>
        <button class="chip execucao" data-pseudo-status="execucao">
            4
            <br>
            Em execução
        </button>
        <button class="chip prontas" data-pseudo-status="prontas">
            6
            <br>
            Prontas
        </button>
    </div>
    <div class="cards"></div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>