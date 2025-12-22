<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="alterar-senha.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        Alterar senha
    </header>

    <form>
        <h1>Alterar senha de <?= e(Aut::$usuario->nome) ?></h1>
        <div class="campos">
            <div class="campo">
                <div class="rotulo"><label for="old">Senha atual</label></div>
                <div class="controle">
                    <input type="password" id="old" name="old" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label for="senha">Nova senha</label></div>
                <div class="controle">
                    <input type="password" id="senha" name="senha" required autocomplete="new-password">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label for="senha2">Repetir nova senha</label></div>
                <div class="controle">
                    <input type="password" id="senha2" name="senha2" required autocomplete="new-password">
                </div>
                <div class=" mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="back" type="button">Cancelar</button>
            <button class="primario" id="b-alterar">Alterar</button>
        </div>
    </form>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="alterar-senha.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>