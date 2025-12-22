<?php

use modelo\Usuario;

/**
 * @var  Usuario $u
 */

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="registrar.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Registrar conta</div>
    </header>

    <form id="form-usuario">
        <?php if ($from == 'assine'): ?>
            <p class="warning">
                Complete seu cadastro para prosseguir utilizando sua conta.
            </p>
        <?php else: ?>
            <p class="warning">
                Sua conta é provisória.
                Faça seu registro, não perca seus dados e os acesse de qualquer aparelho.
            </p>
            <p>
                É grátis e não pedimos dados de pagamento.
            </p>
            <p>
                <?= Aut::$codigo ?>
            </p>
        <?php endif; ?>
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
            <div class="campo">
                <div class="rotulo">
                    <label for="email">Email</label>
                </div>
                <div class="controle">
                    <input type="email" id="email" name="email" required value="<?= e($email) ?>">
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
            <div class="campo" id="campo-whatsapp">
                <div class="rotulo">
                    <label for="celular">WhatsApp</label>
                </div>
                <div class="controle">
                    <input type="tel"
                           id="celular"
                           name="celular"
                           value="<?= e($celular) ?>"
                           required
                           placeholder="Ex.: 14991011414">
                </div>
                <div class="mensagem">
                    Sujeito a validação para autenticidade da conta
                </div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="cpf-cnpj">CPF/CNPJ</label>
                </div>
                <div class="controle">
                    <input type="text"
                           class="cpf-cnpj"
                           id="cpf-cnpj"
                           name="cpf-cnpj"
                           inputmode="numeric"
                           value="<?= e($cpfCnpj) ?>"
                           required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <br>
                Ao se registrar, você concorda com a
                <br>
                <a href="../entrar/politica-privacidade.php" target="_blank">Política de Privacidade</a>
                <br>
                <br>
            </div>
        </div>
        <div class="botoes">
            <button class="primario" id="botao-registrar">Registrar conta</button>
        </div>
    </form>

    <div class="tela" id="validacao-email">
        <header>
            <button class="voltar"></button>
            <div class="banner">Valide seu email</div>
        </header>

        <form id="form-validacao">
            <p>
                Enviamos para seu email um código de 6 dígitos.
                Informe-o abaixo para completar seu registro.
            </p>
            <div class="campos">
                <div class="campo">
                    <div class="controle">
                        <input type="text"
                               maxlength="6"
                               id="codigo-validacao"
                               inputmode="numeric"
                               name="codigo-validacao" required>
                    </div>
                    <div class="mensagem"></div>
                </div>
            </div>
            <div class="botoes">
                <button class="primario" id="botao-enviar-codigo-validacao">Enviar</button>
            </div>
        </form>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const from = '<?= $from ?>'
      console.log(from)
      const whatsAppValidado = <?= $whatsAppValidado ? 'true' : 'false' ?>;
    </script>
    <script type="module" src="registrar.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>