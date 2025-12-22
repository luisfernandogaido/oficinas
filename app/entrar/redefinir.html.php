<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="redefinir.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <form>

        <input type="hidden" id="token" name="token" value="<?= $token ?>">

        <h1>Escolha uma nova senha</h1>

        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="senha">Nova senha</label>
                </div>
                <div class="controle">
                    <input type="password" id="senha" name="senha" required autocomplete="new-password">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="senha2">Nova senha (outra vez)</label>
                </div>
                <div class="controle">
                    <input type="password" id="senha2" required autocomplete="new-password">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>

        <div class="botoes">
            <button class="primario" id="b-enviar">Enviar</button>
        </div>

    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="redefinir.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>