<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="gaido.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <div id="ctn">
        <h2>Quem sou eu</h2>
        <p>
            Luís Fernando Gaido
        </p>

        <h2>O que eu faço</h2>
        <p>
            Software
        </p>

        <h2>Desde quando eu faço</h2>
        <p>2005</p>

        <h2>Como eu faço</h2>
        <p>
            Programo em Go, Javascript, Python, MongoDB, Redis, HTML, CSS, PHP e MySQL
            em servidores Linux configurados por mim.
        </p>
        <p>
            Domino linguagens, não frameworks.
        </p>
        <p>
            Analiso interfaces dos grandes, absorvo ideias e as aplico em meus produtos.
        </p>
        <p>
            Aprendo rapidamente.
        </p>

        <h2>Onde eu me formei</h2>
        <p>Unesp - Bauru</p>
        <p>CTI/Unesp - Bauru</p>

        <h2>Contato</h2>

        <div id="instagram">
            <a href="https://www.instagram.com/luisfernandogaido">@luisfernandogaido</a>
        </div>

        <div id="email" class="hidden">
            luisfernandogaido@gmail.com
        </div>
        <div class="botoes">
            <a class="button" id="b-eu-tenho" href="mailto:luisfernandogaido@gmail.com" target="_blank">
                Me oferte um atraente projeto
            </a>
        </div>
        <img src="gaido-20220302.png" alt="">


    </div>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="gaido.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>