<p class="registros">
    <?= $registros ?>
</p>
<table class="cards mob">
    <tbody>
    <?php foreach ($usuarios as $u): ?>
        <tr data-codigo="<?= e($u['codigo']) ?>"
            data-nome="<?= e($u['nome']) ?>"
            data-celular="55<?= e($u['celular']) ?>"
            data-status="<?= e($u['status']) ?>"
            data-sem-email="<?= e($u['sem_email']) ?>"
        >
            <td>
                <?= e($u['codigo']) ?>
                <?= e($u['nome']) ?>
            </td>
            <td class="d3"><?= e($u['conta']) ?></td>
            <td class="d3"><?= e($u['perfil']) ?></td>
            <td class="d3"><?= e($u['since']) ?></td>
            <td>

            </td>
            <td class="acoes">
                <a href="usuario.php?codigo=<?= $u['codigo'] ?>" class="button view"></a>
                <a href="personifica.php?codigo=<?= $u['codigo'] ?>" class="button person"></a>
                <?php if ($u['celular']): ?>
                    <button class="whatsapp"></button>
                <?php endif; ?>
                <button class="share" data-popover="share"></button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php if (!$cabeTudo): ?>
    <div class="paginacao" data-pages="<?= $data['pages'] ?>">
        <button class=" arrow-left"></button>
        <button class="arrow-right"></button>
    </div>
<?php endif; ?>
