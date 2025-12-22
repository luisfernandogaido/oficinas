<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="esqueci.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <form>

        <h1>Esqueceu sua senha?</h1>

        <p>
            Enviaremos um link de recuperação para
        </p>

        <p class="email oculto">
            luisfernandogaido@gmail.com
        </p>

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
        </div>
        <div class="botoes">
            <button class="primario" id="b-enviar">Enviar</button>
        </div>
    </form>

    <div class="botoes">
        <button id="b-voltar-entrar" class="link">Voltar para entrar</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="esqueci.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>