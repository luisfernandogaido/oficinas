<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="tarefa.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
        <span class="banner"><?= $titulo ?></span>
    </header>

    <form>
        <input type="hidden" id="codigo" name="codigo" value="<?= $codigo ?>">
        <input type="hidden" id="is-started" name="is-started" value="<?= $isStarted ?>">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="cod-projeto">Projeto</label>
                </div>
                <div class="controle">
                    <select id="cod-projeto" name="cod-projeto" required>
                        <?php foreach ($projetos as $p): ?>
                            <option value="<?= $p['codigo'] ?>" <?= $p['codigo'] == $codProjeto ? 'selected' : '' ?>>
                                <?= e($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="nome">Nome</label>
                </div>
                <div class="controle">
                    <textarea name="nome" id="nome"><?= e($nome) ?></textarea>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label for="descricao">Descrição</label>
                </div>
                <div class="controle">
                    <textarea name="descricao" id="descricao"><?= e($descricao) ?></textarea>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo" id="c-trello">
                <div class="rotulo">
                    <label>Trello</label>
                </div>
                <div class="controle">
                    <div id="trello-cards">
                        <?php foreach ($cards as $card): ?>
                            <div class="trello-card">
                                <input type="hidden" name="trello-card[]" value="<?= e($card) ?>">
                                <a href="<?= e($card) ?>" target="_blank"><?= e($card) ?></a>
                                <button class="close"></button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="paste"></button>

                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <?php if ($codigo): ?>
            <div id="tempo"></div>
            <button class="start"></button>
        <?php endif; ?>
        <div class="botoes">
            <button class="primario">Salvar</button>
            <button id="b-voltar" type="button" class="back">Voltar</button>
            <?php if ($codigo): ?>
                <?php if (!$isArquivada): ?>
                    <button type="button" id="b-arquivar">Arquivar</button>
                <?php endif; ?>
                <button type="button" class="perigo" id="b-excluir">Excluir</button>
            <?php endif; ?>
        </div>
    </form>


<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="tarefa.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>