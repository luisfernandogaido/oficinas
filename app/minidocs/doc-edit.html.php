<?php

?>

<?php use templates\Gaido;

$template = new templates\Gaido() ?>
<?php $template->titulo = "$doc->name - Minidocs" ?>
<?php $template->favicon = 'core/templates/gaido/img/home/minidocs.png' ?>
<?php $template->interactiveWidget = Gaido::INTERATIVE_WIDGET_RESIZES_CONTENT ?>


<?php $template->iniCss() ?>
    <link rel="stylesheet" href="doc-edit.css?v=4">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <input type="text" id="name" name="name" value="<?= e($doc->name) ?>">
        <a class="button view" href="doc.php?h=<?= $hash ?>" target="_blank"></a>
        <button class="delete-red"></button>
    </header>

    <div id="minidocs" class="cards">
        <?php foreach ($doc->minidocs as $md): ?>
            <?php if ($md->type == 'text'): ?>
                <div class="card text" data-id="<?= $md->id ?>">
                    <div class="actions">
                        <button class="copy"></button>
                        <button class="edit"></button>
                        <button class="delete"></button>
                    </div>
                    <div class="content"><?= e($md->text) ?></div>
                    <div class="form-text">
                        <div class="botoes">
                            <button class="primario salvar">Salvar</button>
                            <button class="link cancelar">Cancelar</button>
                        </div>
                        <textarea placeholder="Escreva um texto"><?= e($md->text) ?></textarea>
                    </div>
                    <div class="copied">Copied!</div>
                </div>
            <?php elseif ($md->type == 'file'): ?>
                <div class="card file" data-id="<?= $md->id ?>" data-hash-file="<?= $md->file->hash ?>">
                    <div class="actions">
                        <a class="button open-in-new" href="<?= $md->file->url ?>" target="_blank"></a>
                        <button class="edit"></button>
                        <button class="delete"></button>
                    </div>
                    <div class="name"><?= e($md->file->name) ?></div>
                    <div class="type"><?= e($md->file->type) ?></div>
                    <div class="size"><?= e($md->file->size()) ?></div>
                    <div class="description"><?= e($md->file->description) ?></div>
                    <div class="tags"><?= e($md->file->tags) ?></div>
                    <form>
                        <div class="campos">
                            <div class="campo">
                                <div class="rotulo">
                                    <label>name</label>
                                </div>
                                <div class="controle">
                                    <input type="text" class="name" name="name" value="<?= e($md->file->name) ?>">
                                </div>
                                <div class="mensagem"></div>
                            </div>
                            <div class="campo">
                                <div class="rotulo">
                                    <label>description</label>
                                </div>
                                <div class="controle">
                                    <textarea
                                        class="description"
                                        name="description"><?= e($md->file->description) ?></textarea>
                                </div>
                                <div class="mensagem"></div>
                            </div>
                            <div class="campo">
                                <div class="rotulo">
                                    <label>tags</label>
                                </div>
                                <div class="controle">
                                    <textarea class="tags" name="tags"><?= e($md->file->tags) ?></textarea>
                                </div>
                                <div class="mensagem"></div>
                            </div>
                            <div class="botoes">
                                <button class="primario salvar">Salvar</button>
                                <button type="button" class="cancelar">Cancelar</button>
                            </div>
                        </div>
                    </form>
                </div>
            <?php else: ?>
                <div class="card">
                    card sem type conhecido
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <div id="actions">
        <button class="article"></button>
        <button class="paste"></button>
        <button class="attach"></button>
        <input type="file" id="arquivos" name="arquivos" multiple>
    </div>
    <div id="upload-progress-wrapper">
        <div id="upload-progress" class="hidden">
            <label></label>
            <div style="width: 0"></div>
        </div>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>';
    </script>
    <script type="module" src="doc-edit.js?v=2"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>