<?php
use app\client\gd\MinDoc;

/** @var MinDoc $md */
?>
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
                <button type="button" class="primario salvar">Salvar</button>
                <button type="button" class="cancelar">Cancelar</button>
            </div>
        </div>
    </form>
</div>