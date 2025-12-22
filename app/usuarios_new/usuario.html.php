<?php
use modelo\Usuario;

/**@var Usuario $usuario */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="usuario.css?v=2">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b><?= $usuario->nome ?? 'Novo usuário' ?></b></div>
        <a href="personifica.php?codigo=<?= $codigo ?>" class="button person"></a>
        <a href="form-usuario.php?codigo=<?= $codigo ?>" class="button edit"></a>
    </header>

    <input type="hidden" id="codigo" value="<?= $codigo ?>">

    <div class="panel" id="resumo">
        <div class="resumo-e-acoes">
            <table>
                <tbody>
                <tr>
                    <td><b>Código</b></td>
                    <td><?= $codigo ?></td>
                </tr>
                <tr>
                    <td><b>Conta</b></td>
                    <td><?= e($conta) ?></td>
                </tr>
                <tr>
                    <td><b>Nome</b></td>
                    <td><?= e($usuario->nome) ?></td>
                </tr>
                <tr>
                    <td><b>Email</b></td>
                    <td><?= e($usuario->email) ?></td>
                </tr>
                <tr>
                    <td><b>Celular</b></td>
                    <td>
                        <?= e($usuario->celular) ?>
                        <?php if ($usuario->celular != null): ?>
                            <b><a target="_blank" href="<?= $clickToChat ?>">WhatsApp</a></b>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><b>CPF/CNPJ</b></td>
                    <td><?= e($usuario->cpfCnpj) ?></td>
                </tr>
                <tr>
                    <td><b>Perfil</b></td>
                    <td><?= e($usuario->perfil) ?></td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td><?= e($usuario->status) ?></td>
                </tr>
                <tr>
                    <td><b>Apelido</b></td>
                    <td><?= e($usuario->apelido) ?></td>
                </tr>
                <tr>
                    <td><b>Criação</b></td>
                    <td><?= e($usuario->criacao . ' ' . $diasCriacao) ?></td>
                </tr>
                <tr>
                    <td><b>Alteração</b></td>
                    <td><?= e($usuario->alteracao . ' ' . $diasAlteracao) ?></td>
                </tr>
                </tbody>
            </table>
            <div class="checks" id="acoes">
                <label>
                    Validação pendente
                    <input type="checkbox" id="validacao_pendente" <?= $validacaoPendente ? 'checked' : '' ?>>
                </label>
                <label>
                    Forçar assinatura
                    <input type="checkbox" id="forcar_assinatura" <?= $forcarAssinatura ? 'checked' : '' ?>>
                </label>
                <label>
                    WhatsApp validado
                    <input type="checkbox" id="whatsapp_validado" <?= $whatsappValidado ? 'checked' : '' ?>>
                </label>
            </div>
        </div>
    </div>

    <div class="panel" id="perigo">
        <button class="delete-red"></button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="usuario.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>