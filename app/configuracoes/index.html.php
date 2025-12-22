<?php
/** @var Configuracoes $confs */

use modelo\Configuracoes;

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>Configurações</b></div>
    </header>

    <form>
        <div class="campos">
            <div class="campo">
                <div class="controle checks">
                    <label>
                        <input type="checkbox"
                               name="whatsapp-validacao-ao-criar"
                               id="whatsapp-validacao-ao-criar"
                            <?= $confs->whatsAppValidacaoAoCriar ? 'checked' : '' ?>
                        >
                        Validar WhatsApp ao criar
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"><label>Whatsapp</label></div>
                <div class="controle checks">
                    <label>
                        <input type="radio"
                               name="whatsapp"
                               value="14991623401"
                            <?= $confs->whatsApp == '14991623401' ? 'checked' : '' ?>>
                        (14)99162-3401 Business Edge (Default)
                    </label>
                    <label>
                        <input type="radio"
                               name="whatsapp"
                               value="14981199948"
                            <?= $confs->whatsApp == '14981199948' ? 'checked' : '' ?>>
                        (14)98119-9948 Business S24
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>