<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <form>
        <div id="the-logo"></div>
        <br>
        <h1>Fazer login</h1>
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="usuario">E-mail</label>
                </div>
                <div class="controle">
                    <input type="text" id="usuario" name="usuario" required value="<?= $localUser ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="senha">Senha</label>
                </div>
                <div class="controle">
                    <input type="password" id="senha" name="senha" required value="<?= $localPass ?>">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="back" type="button">Voltar</button>
            <button class="primario" id="b-entrar">Entrar</button>
        </div>
    </form>

    <div class="botoes b2">
        <button id="b-esqueci" class="link">Esqueci minha senha</button>
        <?php if(!Sistema::$temaProfinanc): ?>
            <button id="b-criar" class="link">Criar conta</button>
        <?php endif; ?>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>