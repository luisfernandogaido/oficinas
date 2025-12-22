<?php

use modelo\OsItem;

/**
 * @var OsItem[] $produtos
 * @var OsItem[] $servicos
 */
?>

<?php if ($produtos): ?>
    <h2>Produtos</h2>
    <div class="itens produtos">
        <?php foreach ($produtos as $produto): ?>
            <div class="item" data-codigo="<?= $produto->codigo ?>"
                 data-tipo-codigo="produto-<?= $produto->codProduto ?>">
                <div class="categoria-nome">
                    <div class="categoria">
                        <?= e($produto->categoria); ?>
                    </div>
                    <div class="nome">
                        <?= e($produto->nome); ?>
                    </div>
                </div>
                <div class="controles">
                    <div class="field">
                        <label>Qtd.</label>
                        <input type="number" min="0" name="quantidade" value="<?= $produto->quantidade ?>">
                    </div>
                    <div class="field">
                        <label>Vl. Unitário</label>
                        <input type="number" min="0" name="preco" value="<?= $produto->precoInput() ?>" step="any">
                    </div>
                    <div class="field">
                        <label>Subtotal</label>
                        <div class="subtotal"></div>
                    </div>
                    <button class="delete"></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php if ($servicos): ?>
    <h2>Serviços</h2>
    <div class="itens servicos">
        <?php foreach ($servicos as $servico): ?>
            <div class="item" data-codigo="<?= $servico->codigo ?>"
                 data-tipo-codigo="servico-<?= $servico->codServico ?>">
                <div class="categoria-nome">
                    <div class="categoria">
                        <?= e($servico->categoria); ?>
                    </div>
                    <div class="nome">
                        <?= e($servico->nome); ?>
                    </div>
                </div>
                <div class="controles">
                    <div class="field">
                        <label>Qtd.</label>
                        <input type="number" min="0" name="quantidade" value="<?= $servico->quantidade ?>">
                    </div>
                    <div class="field">
                        <label>Vl. Unitário</label>
                        <input type="number" min="0" name="preco" value="<?= $servico->precoInput() ?>" step="any">
                    </div>
                    <div class="field">
                        <label>Subtotal</label>
                        <div class="subtotal"></div>
                    </div>
                    <button class="delete"></button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($produtos || $servicos): ?>
    <div class="totais">
        Total R$ <span>0,00</span>
    </div>
<?php else: ?>
    <div id="caixa-do-nada">
        <div class="prancheta"></div>
        <h2>Orçamento em branco</h2>
        <p>Toque no botão "+" para adicionar peças e serviços</p>
    </div>
<?php endif; ?>