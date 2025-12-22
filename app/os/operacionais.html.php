<?php foreach ($oss as $os): ?>
    <a class="card" href="os.php?h=<?= $os['hash'] ?>">
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
    </a>
<?php endforeach; ?>
<script type="application/json" class="data"><?= json_encode($_SERVER) ?></script>