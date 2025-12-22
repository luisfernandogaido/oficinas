<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="criar.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

<?php if ($provisorio): ?>
    <header>
        <button class="voltar"></button>
    </header>
<?php endif; ?>

    <form>
        <div id="passo1">
            <div id="the-logo"></div>
            <br>
            <h1><?= $titulo ?></h1>
            <div class="campos">
                <div class="campo">
                    <div class="rotulo">
                        <label for="email">E-mail</label>
                    </div>
                    <div class="controle">
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="rotulo">
                        <label for="nome">Nome completo</label>
                    </div>
                    <div class="controle">
                        <input type="text" id="nome" name="nome" required>
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="rotulo">
                        <label for="senha">Senha</label>
                    </div>
                    <div class="controle">
                        <input type="password" id="senha" name="senha" required autocomplete="new-password">
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="controle">
                        <br>
                        Ao se registrar, você concorda com a
                        <a href="politica-privacidade.php" target="_blank">Política de Privacidade</a>.
                    </div>
                </div>
            </div>
            <div class="botoes">
                <button class="primario">Registre-se</button>
            </div>
        </div>
        <div id="passo2" class="oculto">
            <h1>Cheque seu e-mail para concluir o registro</h1>
            <p>
                Enviamos um link de registro para você em
            </p>
            <p id="p-email">luisfernandogaido@yahoo.com.br</p>
            <img src="concluir-registro.svg">
        </div>
    </form>

<?php if (!$provisorio): ?>
    <div class="botoes">
        <button class="link" id="b-ja-tem-conta">Já tem conta? Entrar</button>
    </div>
<?php endif; ?>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
        <?php if($provisorio): ?>
        document.body.classList.add('provisorio');
        <?php endif; ?>
        const logado = <?= $logado ? 'true' : 'false' ?>;
    </script>
    <script type="module" src="criar.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>