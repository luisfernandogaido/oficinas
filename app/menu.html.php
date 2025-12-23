<?php
use modelo\Usuario;

?>
<?php if (Aut::$perfil == Usuario::PERFIL_MASTER): ?>
    <menu>
        <header class="estatico">
            <button class="voltar" title="Voltar"></button>
            <a class="logo" href="<?= SITE ?>app/index.php"></a>
        </header>
       <section>

            <a href="<?= SITE ?>app/usuarios/index.php">Usuários</a>
            <a href="<?= SITE ?>app/contas/index.php">Contas</a>
            <a href="<?= SITE ?>app/validacao-whatsapp/index.php">Validação de WhatsApps</a>
            <a>Submenu</a>
            <div>
                <a href="<?= SITE ?>app/contas/index.php">Contas</a>
                <a href="<?= SITE ?>app/contas/index.php">Contas</a>
                <a href="<?= SITE ?>app/contas/index.php">Contas</a>
                <a href="<?= SITE ?>app/contas/index.php">Contas</a>
            </div>
        </section>
        <section>
            <a href="#" id="menu-tema"></a>
            <a href="#" id="menu-install" class="oculto">Instalar app</a>
            <a href="<?= SITE ?>app/sobre/index.php">Sobre</a>
            <a href="<?= SITE ?>app/entrar/politica-privacidade.php" target="_blank">Política de Privacidade</a>
            <a href="<?= SITE ?>app/entrar/termos-de-uso.php" target="_blank">Termos de uso</a>
            <?php if (Aut::isGaido()): ?>

            <?php endif; ?>
            <?php if (!Aut::provisorio() || Aut::$codPersonificador): ?>
                <a href="<?= SITE ?>app/entrar/sai.php">Sair</a>
            <?php endif; ?>
        </section>
    </menu>
<?php elseif (Aut::$perfil == Usuario::PERFIL_ADMIN): ?>
    <menu>
        <header class="estatico">
            <button class="voltar" title="Voltar"></button>
            <a class="logo" href="<?= SITE ?>app/index.php"></a>
        </header>
        <section>
            <a href="<?= SITE ?>app/usuarios/index.php">Usuários</a>
        </section>
        <section>
            <a href="#" id="menu-tema"></a>
            <a href="<?= SITE ?>app/blog/index.php">Blog</a>
            <a href="#" id="menu-install" class="oculto">Instalar app</a>
            <a href="<?= SITE ?>app/sobre/index.php">Sobre</a>
            <?php if (!Aut::provisorio() || Aut::$codPersonificador): ?>
                <a href="<?= SITE ?>app/entrar/sai.php">Sair</a>
            <?php endif; ?>
        </section>
    </menu>
<?php elseif (Aut::$perfil == Usuario::PERFIL_PADRAO): ?>
    <menu>
        <header class="estatico">
            <button class="voltar" title="Voltar"></button>
            <a class="logo" href="<?= SITE ?>app/index.php"></a>
        </header>
        <section>

        </section>
        <section>
            <a href="#" id="menu-tema"></a>
            <a href="#" id="menu-install" class="oculto">Instalar app</a>
            <a href="<?= SITE ?>app/sobre/index.php">Sobre</a>
            <?php if (!Aut::provisorio() || Aut::$codPersonificador): ?>
                <a href="<?= SITE ?>app/entrar/sai.php">Sair</a>
            <?php endif; ?>
        </section>
    </menu>
<?php else: ?>
    <menu>
        <header class="estatico">
            <button class="voltar" title="Voltar"></button>
            <a class="logo" href="<?= SITE ?>app/index.php"></a>
        </header>
        <section>
            <a href="<?= SITE ?>app/entrar/index.php">Entrar</a>
        </section>
        <section>
            <a href="<?= SITE ?>app/blog/index.php">Blog</a>
            <a href="#" id="menu-tema"></a>
            <a href="#" id="menu-install" class="oculto">Instalar app</a>
            <a href="<?= SITE ?>app/sobre/index.php">Sobre</a>
        </section>
    </menu>
<?php endif; ?>