<?php
use app\client\gd\Doc;

/** @var Doc[] $docs */
?>
<?php foreach ($docs as $doc): ?>
    <a class="card doc" data-hash="<?= $doc->hash ?>" href="doc.php?h=<?= $doc->hash ?>&edit">
        <div class="name"><?= e($doc->name) ?></div>
        <div class="created_at"><?= e($doc->createdAt()) ?></div>
        <span class="actions">
            <button class="delete-red"></button>
        </span>
    </a>
<?php endforeach; ?>