<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
<link rel="stylesheet" href="usuario.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

<header>
    <button class="voltar"></button>
    <span class="banner"><?= $titulo ?></span>
</header>

<form>
    <input type="hidden" id="codigo" name="codigo" value="<?= e($codigo) ?>">
    <div class="campos">
        <div class="campo">
            <div class="rotulo"><label for="nome">Nome</label></div>
            <div class="controle">
                <input type="text" id="nome" name="nome" required value="<?= e($nome) ?>">
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="email">E-mail</label></div>
            <div class="controle">
                <input type="email" id="email" name="email" required value="<?= e($email) ?>">
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="celular">Celular</label></div>
            <div class="controle">
                <input type="tel" id="celular" name="celular" value="<?= e($celular) ?>">
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label>Validado</label></div>
            <div class="controle checks">
                <label>
                    <input type="radio"
                           name="whatsapp-validado"
                           value="1"
                        <?= $whatsappValidado ? 'checked' : '' ?>
                    >
                    Sim
                </label>
                <label>
                    <input type="radio"
                           name="whatsapp-validado"
                           value="0"
                        <?= !$whatsappValidado ? 'checked' : '' ?>
                    >
                    Não
                </label>
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="cpf-cnpj">CPF/CNPJ</label></div>
            <div class="controle">
                <input type="text" class="cpf-cnpj" id="cpf-cnpj" name="cpf-cnpj" value="<?= e($cpfCnpj) ?>">
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="perfil">Perfil</label></div>
            <div class="controle">
                <select id="perfil" name="perfil" required>
                    <option value=""></option>
                    <?php foreach ($perfis as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $k == $perfil ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="cod-conta">Conta</label></div>
            <div class="controle">
                <select id="cod-conta" name="cod-conta" required>
                    <option value=""></option>
                    <?php foreach ($contas as $c): ?>
                        <option
                            value="<?= $c['codigo'] ?>"
                            <?= $c['codigo'] == $codConta ? 'selected' : '' ?>>
                            <?= e($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="status">Status</label></div>
            <div class="controle">
                <select id="status" name="status" required>
                    <option value=""></option>
                    <?php foreach ($stati as $k): ?>
                        <option value="<?= $k ?>" <?= $k == $status ? 'selected' : '' ?>><?= $k ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="campo">
            <div class="rotulo"><label for="apelido">Apelido</label></div>
            <div class="controle">
                <input type="text" id="apelido" name="apelido" value="<?= e($apelido) ?>">
            </div>
            <div class="mensagem"></div>
        </div>
        <div class="botoes senha">
            <button id="b-senha">Trocar senha</button>
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
            <div class="rotulo"><label for="senha2">Repetir senha</label></div>
            <div class="controle">
                <input type="password" id="senha2" name="senha2" required autocomplete="new-password">
            </div>
            <div class="mensagem"></div>
        </div>
    </div>
    <div class="botoes">
        <button class="back" id="b-cancelar">Cancelar</button>
        <button class="primario" id="b-salvar">Salvar</button>
    </div>
</form>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
<script type="module" src="usuario.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>

