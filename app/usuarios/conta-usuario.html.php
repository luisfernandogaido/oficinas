<?php
use modelo\Usuario;

/** @var Usuario $usuario */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="conta-usuario.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Conta</div>
    </header>

    <form>
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Código</label>
                </div>
                <div class="controle">
                    <span><?= Aut::$codigo ?></span>
                </div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>Nome</label></div>
                <div class="controle">
                    <input type="text"
                           id="nome"
                           name="nome"
                           value="<?= e($nome) ?>"
                           spellcheck="false"
                           readonly
                           style="border: none; font-weight: bold; margin: 0; padding: 0;">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>E-mail</label></div>
                <div class="controle">
                    <?= e($email) ?>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>Celular</label></div>
                <div class="controle">
                    <?= e($celular) ?>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>CPF/CNPJ</label></div>
                <div class="controle">
                    <?= e($cpfCnpj) ?>
                </div>
                <div class="mensagem"></div>
            </div>
            <?php if (Aut::$perfil == Usuario::PERFIL_MASTER || Aut::$codPersonificador != null): ?>
                <div class="campo">
                    <div class="rotulo"><label>Código</label></div>
                    <div class="controle">
                        <?= e($usuario->codigo) ?>
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="rotulo"><label>Conta</label></div>
                    <div class="controle">
                        <?= e($usuario->conta) ?>
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="rotulo"><label>Perfil</label></div>
                    <div class="controle">
                        <?= $usuario->perfil ?>
                    </div>
                    <div class="mensagem"></div>
                </div>
                <div class="campo">
                    <div class="rotulo"><label>Status</label></div>
                    <div class="controle">
                        <?= $usuario->status ?>
                    </div>
                    <div class="mensagem"></div>
                </div>
            <?php endif; ?>
            <div class="campo">
                <div class="rotulo"><label>Assinatura</label></div>
                <div class="controle">
                    <?php if (Aut::$assinatura): ?>
                        <a href="../assinatura/index.php"><?= Aut::$assinatura->nome ?></a>
                    <?php else: ?>
                        <a href="../assinatura/index.php">Sem assinatura vigente</a>
                    <?php endif; ?>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>Senha</label></div>
                <div class="controle">
                    <a href="alterar-senha.php">Alterar senha</a>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="conta-usuario.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>