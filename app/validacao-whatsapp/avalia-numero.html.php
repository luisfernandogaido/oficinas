<?php foreach ($usuariosCelular as $u): ?>
    <div class="usuario" data-codigo="<?= $u['codigo'] ?>" data-email="<?= $u['email'] ?>">
        <div class="campo">
            <?= $u['codigo'] ?>
            -
            <?= $u['nome'] ?>
        </div>
        <div class="campo">
            <?= $u['email'] ?>
        </div>
        <div class="campo">
            <?= $u['cpf_cnpj'] ?>
        </div>
        <div class="campo">
            <?= $u['whatsapp_validado'] ? 'WhatsApp VALIDADO' : 'WhatsApp NAO VALIDADO' ?>
        </div>
        <div class="campo">
            <?= $u['status_h'] ?>
        </div>
        <div class="campo">
            <?= $u['since_criacao'] ?>
            -
            <?= $u['criacao'] ?>
        </div>
        <br>
        <br>
        <div class="campo">
            <div class="botoes">
                <button class="gerar-token">Gerar token de acesso</button>
            </div>
        </div>
    </div>
<?php endforeach; ?>
