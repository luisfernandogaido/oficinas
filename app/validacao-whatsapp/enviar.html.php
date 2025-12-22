<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="enviar.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Validar seu número de WhatsApp</b></div>
    </header>

    <form>
        <?php if ($validado): ?>
            <div id="msg-validado">
                <p>
                    O seu número já está validado.
                </p>
            </div>
            <br>
            <div class="botoes">
                <button class="back">Voltar</button>
            </div>
        <?php else: ?>
            <div id="msg-inicial">
                <p>
                    Para validar seu número,
                    clique no botão abaixo e envie a mensagem do seu WhatsApp
                    sem modificar o texto.
                </p>
                <p>
                    A mensagem será enviada com um código de validação.
                </p>
            </div>
            <div id="msg-espera" class="hidden">
                <p>
                    Enviaremos uma resposta automática instantaneamente.
                </p>
            </div>
            <div id="msg-resultado" class="hidden"></div>
            <br>
            <div class="botoes">
                <button type="button" class="primario">Enviar mensagem de validação</button>
            </div>
        <?php endif; ?>
    </form>
    <div class="botoes">
        <button class="back link">Voltar</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="enviar.js"></script>
    <script>
      const token = '<?= $token ?>';
      const destinatario = '<?= $whatsApp ?>';
    </script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>