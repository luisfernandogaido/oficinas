<?php if ($frequentes && $itens): ?>
    <p>Usados frequentemente</p>
<?php endif ?>
<div class="cards" data-frequentes="<?= $frequentes ? '1' : '0' ?>">
    <?php foreach ($itens as $item): ?>
        <div class="card <?= $item['interno'] ? 'interno' : '' ?>"
             data-tipo="<?= $item['tipo'] ?>"
             data-codigo="<?= $item['codigo'] ?>"
             data-tipo-codigo="<?= "{$item['tipo']}-{$item['codigo']}" ?>">
            <div class="icone <?= $item['tipo'] ?>"></div>
            <div class="texto">
                <div class="nome">
                    <?= e($item['nome']) ?>
                </div>
                <div class="categoria">
                    <?= e($item['categoria']) ?>
                </div>
            </div>
            <div class="preco">
                <?= $item['interno'] ? (moeda($item['preco'] ?? 0)) : '' ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php if (!$frequentes): ?>
    <br>
    <div class="botoes">
        <button id="cadastrar-produco-servico">Cadastrar novo</button>
    </div>
<?php endif; ?>
