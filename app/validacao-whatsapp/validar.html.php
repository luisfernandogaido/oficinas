<?php
use modelo\Usuario;
use modelo\WhatsappValidacao;

/**
 * @var WhatsappValidacao $wv ;
 * @var Usuario $u ;
 */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="validar.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Validação de WhatsApp</b></div>
    </header>

    <form>
        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Token</label>
                </div>
                <div class="controle">
                    <span><b><?= $wv->token ?></b></span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Criação</label>
                </div>
                <div class="controle">
                    <span><?= $wv->criacao ?></span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Usuário</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->codigo ?>
                        -
                        <?= e($u->nome) ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Email</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->email ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Celular</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->celular ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>WhatsApp validado</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->whatsAppValidado ? 'SIM' : 'NAO' ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>CPF/CNPJ</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->cpfCnpj ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Status</label>
                </div>
                <div class="controle">
                    <span>
                        <?= $u->status ?>
                    </span>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Número do WhatsApp</label>
                </div>
                <div class="controle">
                    <input type="text" id="numero" name="numero">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Validado</label>
                </div>
                <div class="controle checks">
                    <label>
                        <input type="radio" name="validado" value="1" checked>
                        SIM
                    </label>
                    <label>
                        <input type="radio" name="validado" value="0">
                        NAO
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Resposta</label>
                </div>
                <div class="controle">
                    <input type="text" id="resposta" name="resposta" required value="Validado.">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="primario" id="b-salva">Salvar</button>
        </div>
    </form>
    <br>
    <h2>Usuários com o celular <?= $u->celular ?></h2>
    <div class="usuarios-celular">
        <?php foreach ($usuariosCelular as $u): ?>
            <div class="usuario">
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
            </div>
        <?php endforeach; ?>
    </div>
    <br>
    <h2>Usuários com o WhatsApp informado</h2>
    <div class="usuarios-celular" id="usuarios-celular-informado"></div>
<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="validar.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>