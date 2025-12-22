<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <input type="search" placeholder="Pesquisar usuário" id="search">
        <button class="filter" title="Ctrl + ,"></button>
        <div class="buttons">
            <button class="mais"></button>
            <div>
                <a href="dominios-emails.php" class="button">Domínios emails</a>
            </div>
        </div>
    </header>

    <div id="resultado" data-n=""></div>

    <div class="tela moda" id="filtro">
        <div>
            <?php if ($master): ?>
                <p>Conta</p>
                <div class="contas checks">
                    <label>
                        <input type="radio" name="conta" value="" checked>
                        TODAS
                    </label>
                    <?php foreach ($contas as $conta): ?>
                        <label>
                            <input type="radio" name="conta" value="<?= $conta['codigo'] ?>">
                            <?= e($conta['nome']) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <p>Perfil</p>
            <div class="perfis checks">
                <label>
                    <input type="radio" name="perfil" value="" checked>
                    TODOS
                </label>
                <?php foreach ($perfis as $k => $v): ?>
                    <label>
                        <input type="radio" name="perfil" value="<?= $k ?>">
                        <?= $v ?>
                    </label>
                <?php endforeach; ?>
            </div>
            <br>
            <div class="botoes">
                <button class="primario">OK</button>
            </div>
        </div>
    </div>

    <div class="tela moda" id="share">
        <div>
            <h1>Compartilhar acesso de <span class="nome"></span></h1>
            <div class="mais hidden">
                <br>
                <p>
                    Um link seguro será gerado e o usuário poderá acessar sua conta diretamente, sem a necessidade de
                    usuário/senha.
                </p>
                <p>
                    Esse recurso é comumente utilizado quando você deseja fazer com que o usuário entre no sistema pela
                    primeira vez, diretamente em sua própria conta de usuário.
                </p>
            </div>
            <br>
            <div class="botoes">
                <button class="copylink">Copiar link</button>
                <button class="whatsapp">WhatsApp</button>
                <button class="email">Email</button>
                <button class="saiba-mais">Saiba mais</button>
            </div>
        </div>
    </div>

    <a href="usuario.php" class="button novo"></a>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const master = <?= $master ? 'true' : 'false' ?>;
      const adminPersonifica = <?= $adminPersonifica ? 'true' : 'false' ?>;
    </script>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>