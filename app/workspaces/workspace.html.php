<?php
use modelo\Workspace;

/** @var Workspace $ws */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="workspace.css?v=3">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <header>
        <button class="voltar"></button>
        <div class="banner"><?= $titulo ?></div>
        <button class="delete-red <?= !$podeExcluir ? 'hidden' : '' ?>"></button>
    </header>
    <form>
        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="nome">Nome da sua oficina</label>
                </div>
                <div class="controle">
                    <input type="text" id="nome" name="nome" required value="<?= e($ws->nome) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="cep">CEP</label>
                </div>
                <div class="controle">
                    <input type="text" class="cep" id="cep" name="cep" value="<?= e($ws->cep) ?>" inputmode="numeric">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="endereco">Endereço</label>
                </div>
                <div class="controle">
                    <input type="text" id="endereco" name="endereco" value="<?= e($ws->endereco) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="numero">Número</label>
                </div>
                <div class="controle">
                    <input type="text" id="numero" name="numero" value="<?= e($ws->numero) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="complemento">Complemento</label>
                </div>
                <div class="controle">
                    <input type="text" id="complemento" name="complemento" value="<?= e($ws->complemento) ?>">
                </div>
                <div class="mensagem"></div>
            </div>

            <div class="campo">
                <div class="rotulo">
                    <label for="bairro">Bairro</label>
                </div>
                <div class="controle">
                    <input type="text" id="bairro" name="bairro" value="<?= e($ws->bairro) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="uf">UF</label>
                </div>
                <div class="controle">
                    <input type="text" id="uf" name="uf" value="<?= e($ws->uf) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2">
                <div class="rotulo">
                    <label for="complemento">Cidade</label>
                </div>
                <div class="controle">
                    <input type="text" id="cidade" name="cidade" value="<?= e($ws->cidade) ?>">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <br>
                <div class="botoes">
                    <button id="carregar-foto" type="button">Carregar foto</button>
                </div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Logo</label>
                </div>
                <div class="controle">
                    <input type="hidden" id="logotipo" name="logotipo" value="<?= $ws->logo ?>">
                    <input type="file" id="arquivo" name="arquivo" accept="image/*">
                    <img src="<?= $ws->logo ?>">
                </div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="descricao">Descrição do seu negócio</label>
                </div>
                <div class="controle">
                    <textarea name="descricao"
                              id="descricao"
                              placeholder="Destaque seus diferenciais, serviços principais e filosofia de trabalho."
                    ><?= e($ws->descricao) ?></textarea>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="workspace.js?v=3"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>