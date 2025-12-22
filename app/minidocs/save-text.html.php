<?php
use app\client\gd\MinDoc;

/** @var MinDoc $md */
?>
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