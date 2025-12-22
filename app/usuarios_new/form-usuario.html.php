<?php
use modelo\Usuario;

/**@var Usuario $usuario */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="form-usuario.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><?= $titulo ?></div>
    </header>

    <form>
        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
        <div class="campos">
            <div class="campo">
                <div class="controle">
                    <span><?= $codigo ?></span>
                </div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="nome">Nome</label>
                </div>
                <div class="controle">
                    <input type="text" id="nome" name="nome" value="<?= e($usuario->nome ?? '') ?>" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="email">Email</label>
                </div>
                <div class="controle">
                    <input type="email" id="email" name="email" value="<?= e($usuario->email ?? '') ?>" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="cod_conta">Conta</label>
                </div>
                <div class="controle">
                    <select id="cod_conta" name="cod_conta" required>
                        <option value=""></option>
                        <?php foreach ($contas as $conta): ?>
                            <option value="<?= $conta['codigo'] ?>"
                                <?= $conta['codigo'] == ($usuario->codConta ?? 0) ? 'selected' : '' ?>>
                                <?= e($conta['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="celular">Celular</label>
                </div>
                <div class="controle">
                    <input type="tel" id="celular" name="celular" value="<?= e($usuario->celular) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="cpf_cnpj">CPF/CNPJ</label>
                </div>
                <div class="controle">
                    <input type="text"
                           class="cpf-cnpj"
                           id="cpf_cnpj"
                           name="cpf_cnpj"
                           value="<?= e($usuario->cpfCnpj) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="perfil">Perfil</label>
                </div>
                <div class="controle">
                    <select id="perfil" name="perfil" required>
                        <option value=""></option>
                        <?php foreach ($perfis as $perfil): ?>
                            <option value="<?= $perfil ?>" <?= $perfil == $usuario->perfil ? 'selected' : '' ?>>
                                <?= $perfil ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="status">Status</label>
                </div>
                <div class="controle">
                    <select id="status" name="status">
                        <option value=""></option>
                        <?php foreach ($stati as $status): ?>
                            <option value="<?= $status ?>"
                                <?= $usuario->status == $status ? 'selected' : '' ?>>
                                <?= e($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="apelido">Apelido</label>
                </div>
                <div class="controle">
                    <input type="text"
                           id="apelido"
                           name="apelido"
                           value="<?= e($usuario->apelido ?? '') ?>" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo" id="wrapper-trocar-senha">
                <div class="controle">
                    <div class="botoes">
                        <button id="trocar-senha">Trocar senha</button>
                    </div>
                </div>
            </div>
            <div class="campo senha">
                <div class="rotulo">
                    <label for="senha">Senha</label>
                </div>
                <div class="controle">
                    <input type="password" id="senha" name="senha" autocomplete="new-password" required>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo senha">
                <div class="rotulo">
                    <label for="senha">Repetir senha</label>
                </div>
                <div class="controle">
                    <input type="password" id="senha2" name="senha2" autocomplete="new-password" required>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="back">Cancelar</button>
            <button class="primario" id="salvar">Salvar</button>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const codigo = <?= $codigo ?>;
      let informarSenha = <?= $informarSenha ? 'true' : 'false' ?>;
    </script>
    <script type="module" src="form-usuario.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>