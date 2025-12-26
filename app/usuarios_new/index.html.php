<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css?v=3">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <input type="search" id="search">
        <button class="tune"></button>
        <div class="search-panel">
            <form>
                <div class="campos">
                    <div class="campo d2">
                        <div class="rotulo">
                            <label for="cod-conta">Conta</label>
                        </div>
                        <div class="controle">
                            <select id="cod-conta" name="cod-conta">
                                <option></option>
                                <?php foreach ($contas as $conta): ?>
                                    <option value="<?= $conta['codigo'] ?>"><?= e($conta['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="campo d2">
                        <div class="rotulo">
                            <label for="perfil">Perfil</label>
                        </div>
                        <div class="controle">
                            <select id="perfil" name="perfil">
                                <option></option>
                                <?php foreach ($perfis as $p): ?>
                                    <option value="<?= $p ?>">
                                        <?= $p ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="campo" id="campo-status">
                        <div class="rotulo">
                            <label for="status">Status</label>
                        </div>
                        <div class="controle checks">
                            <label>
                                <input type="radio" name="status" value="" checked>
                                todos
                            </label>
                            <label>
                                <input type="radio" name="status" value="ativo">
                                ativo
                            </label>
                            <label>
                                <input type="radio" name="status" value="provisorio">
                                provisorio
                            </label>
                            <label>
                                <input type="radio" name="status" value="pendente">
                                pendente
                            </label>
                            <label>
                                <input type="radio" name="status" value="inativo">
                                inativo
                            </label>
                        </div>
                    </div>
                    <div class="campo" id="opcoes">
                        <div class="rotulo">
                            <label>Opções</label>
                        </div>
                        <div class="controle checks">
                            <label>
                                <input type="checkbox" id="whatsapp-validado" name="whatsapp-validado">
                                WhatsApp validado
                            </label>
                        </div>
                    </div>
                </div>
                <div class="botoes">
                    <button type="button" class="link" id="redefinir">Redefinir</button>
                    <button type="button" class="primario" id="pesquisar">Pesquisar</button>
                </div>
            </form>
        </div>
    </header>

    <div id="resultado"></div>

    <div id="share" class="popover">
        <div class="botoes">
            <button id="copy-share">Copiar link</button>
            <button id="whatsapp-share">WhatsApp</button>
            <button id="email-share">Email</button>
        </div>
    </div>

    <a href="form-usuario.php" class="button novo"></a>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>