<?php if ($files): ?>
    <div class="resumo">
        Espaço usado:
        <?= $totalOriginalSizeH ?>
        <?php if ($souEu): ?>
            <?= $totalSizeH ?>
        <?php endif; ?>
    </div>
<?php endif; ?>
<div id="files" data-total-original-size="<?= $totalOriginalSize ?>">

    <?php foreach ($files as $file): ?>
        <div class="file" data-id="<?= $file['id'] ?>">
            <?php if (str_contains($file['type'], "image")): ?>
                <img src="<?= $file['url'] ?>" alt="<?= $file['name'] ?>">
            <?php elseif (str_contains($file['type'], "video")): ?>
                <video src="<?= $file['url'] ?>" controls></video>
            <?php endif; ?>
            <p>
                <?= e($file['name']) ?>
                <?= e($file['original_size_h']) ?>
                <?php if ($souEu): ?>
                    <?= e($file['size_h']) ?>
                <?php endif; ?>
            </p>
            <div class="controles">
                <button class="delete"></button>
            </div>
        </div>
    <?php endforeach; ?>
</div>