<?php
use app\client\gd\Doc;

/** @var Doc $doc */
?>

<?php $template = new templates\Gaido() ?>
<?php $template->titulo = e($doc->name) . " - Minidocs" ?>
<?php $template->favicon = 'core/templates/gaido/img/home/minidocs.png' ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="doc.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>
    <h1><?= e($doc->name) ?></h1>
    <div id="minidocs">
        <?php foreach ($doc->minidocs as $md): ?>
            <?php if ($md->type == 'text'): ?>
                <div class="mindoc text"><?= e($md->text) ?></div>
            <?php elseif ($md->type == 'file'): ?>
                <div class="mindoc file">
                    <a href="<?= $md->file->url ?>" target="_blank">
                        <?php if (str_contains($md->file->type, 'image/')): ?>
                            <img src="<?= $md->file->url ?>"
                                 alt="<?= e($md->file->name . ' ' . $md->file->description) ?>">
                            <div><?= e($md->file->name) ?></div>
                        <?php elseif(str_contains($md->file->type, 'video/')): ?>
                            <video src="<?= $md->file->url ?>" controls></video>
                            <div><?= e($md->file->name) ?></div>
                        <?php else: ?>
                            <?= e($md->file->name) ?>
                        <?php endif; ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="mindoc undefined">
                    mindoc não definido
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="doc.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>