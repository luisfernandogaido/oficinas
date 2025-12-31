<?php use modelo\OsStatus;

foreach ($oss as $os): ?>
    <a class="card"
       href="os.php?h=<?= $os['hash'] ?>"
       data-codigo="<?= $os['codigo'] ?>"
       data-cod-criador="<?= $os['cod_criador'] ?>">
        <div class="field modelo-placa">
            <div class="modelo">
                <?= $os['marca_modelo'] ?>
            </div>
            <div class="placa">
                <?= $os['placa'] ?>
            </div>
        </div>
        <div class="field os">
            #<?= $os['codigo'] ?>
        </div>
        <div class="field status">
            <span class="status <?= $os['status']->value ?>">
                <?= $os['status']->label() ?>
            </span>
        </div>
        <div class="field cliente">
            <?= e($os['cliente']) ?>
            <!--                        Rogerio Maia de Queiroz Lessa-->
        </div>
        <div class="field tempo">
            <?= $os['status_since'] ?>
        </div>
        <div class="field valor">
            <?= $os['valor_h'] != '0,00' ? $os['valor_h'] : '' ?>
        </div>
        <?php if ($master): ?>
            <div class="field workspace">
                <?= e($os['workspace']) ?>
            </div>
            <div class="field actions">
                <button class="delete"></button>
                <button class="person"></button>
                <?php if ($os['status'] == OsStatus::PENDENTE_MODERACAO): ?>
                    <button class="thumb-up"></button>
                    <button class="thumb-down"></button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </a>
<?php endforeach; ?>
<script type="application/json" class="data"><?= json_encode($_SERVER) ?></script>