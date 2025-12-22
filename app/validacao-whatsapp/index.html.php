<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Validação de WhatsApps</b></div>
    </header>

    <div id="validacoes">
        <?php foreach ($validacoes as $v): ?>
            <div class="validacao">
                <div class="campo token">
                    <label>Token</label>
                    <div>
                        <a href="validar.php?codigo=<?= $v['codigo'] ?>">
                            <?= $v['token'] ?>
                        </a>
                    </div>
                </div>
                <div class="campo criacao">
                    <label>Criação</label>
                    <div>
                        <?= $v['token_since'] ?>
                        -
                        <?= $v['criacao_token'] ?>
                    </div>
                </div>
                <div class="campo">
                    <label>Usuário</label>
                    <div>
                        <a href="../usuarios/usuario.php?codigo=<?= $v['cod_usuario'] ?>" target="_blank">
                            <?= $v['cod_usuario'] ?>
                            -
                            <?= $v['nome'] ?>
                        </a>
                    </div>
                </div>
                <div class="campo">
                    <label>Email</label>
                    <div>
                        <?= $v['email'] ?>
                    </div>
                </div>
                <div class="campo c2">
                    <label>Celular</label>
                    <div>
                        <?= $v['celular'] ?>
                    </div>
                </div>
                <div class="campo c2">
                    <label>CPF/CNPJ</label>
                    <div>
                        <?= $v['cpf_cnpj'] ?>
                    </div>
                </div>
                <div class="campo c2">
                    <label>WhatsApp validado</label>
                    <div>
                        <?= $v['whatsapp_validado'] ? 'SIM' : 'NÃO' ?>
                    </div>
                </div>
                <div class="campo c2">
                    <label>Status</label>
                    <div>
                        <?= $v['status_h'] ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>