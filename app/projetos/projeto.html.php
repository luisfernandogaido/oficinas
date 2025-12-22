<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="projeto.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <span class="banner"><?= $titulo ?></span>
    </header>

    <form>
        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="nome">Nome</label>
                </div>
                <div class="controle">
                    <input type="text" id="nome" name="nome" required value="<?= e($nome) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="primario">Salvar</button>
            <button class="back">Voltar</button>
        </div>
    </form>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="projeto.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>