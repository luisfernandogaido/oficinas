<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="completar.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Completar cadastro</b></div>
    </header>

    <form>
        <p>
            Informe os seus dados abaixo para prosseguir utilizando sua conta.
        </p>
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="celular">WhatsApp</label>
                </div>
                <div class="controle">
                    <input type="tel" id="celular" name="celular" required placeholder="Ex.: 14991011414">
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
                    <input type="cpf-cnpj" id="cpf-cnpj" name="cpf-cnpj" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="controle">
                    <br>
                    Ao salvar os dados, você concorda com a
                    <a href="../entrar/politica-privacidade.php" target="_blank">Política de Privacidade</a>.
                    <br>
                </div>
            </div>
        </div>
        <br>
        <div class="botoes">
            <button class="primario">Salvar</button>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const from = '<?= $from ?>';
    </script>
    <script type="module" src="completar.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>