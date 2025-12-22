<?php

use app\os\OsViewModel;
use modelo\Os;
use modelo\OsStatus;

/**
 * @var Os $os
 * @var OsViewModel $osVM
 */
?>

<?php if ($os->status == OsStatus::SOLICITADA): ?>
    <p class="alerta">
        ⚠️ PRÉ-ORÇAMENTO (SEM VISITA)
    </p>
<?php elseif ($os->status == OsStatus::ANALISE): ?>
    <p class="alerta">
        ☑️️ DIAGNÓSTICO TÉCNICO
    </p>
<?php endif; ?>

    <table>
        <tbody>
        <tr>
            <th>Produtos</th>
            <td>R$<?= $totalProdutosH ?></td>
        </tr>
        <tr>
            <th>Serviços</th>
            <td>R$<?= $totalServicosH ?></td>
        </tr>
        <tr>
            <th>Desconto</th>
            <td class="desconto">
                <div>
                    R$<input type="number" id="desconto" value="<?= $os->descontoH ?>">
                </div>
            </td>
        </tr>
        <tr class="total">
            <th>
                <br>
                Total
            </th>
            <td class="total">
                <br>
                R$<span class="valor-h"><?= $os->valorH ?></span>
            </td>
        </tr>
        </tbody>
    </table>
    <br>
<?php if ($os->status == OsStatus::SOLICITADA || $os->status == OsStatus::AGENDADA): ?>
    <div class="botoes">
        <button id="enviar-orcamento" class="outline">Enviar estimativa</button>
    </div>
<?php elseif ($os->status == OsStatus::AGUARDANDO_APROVACAO): ?>
    <div class="botoes">
        <button id="enviar-orcamento" class="outline">Reenviar orçamento</button>
    </div>
<?php elseif ($osVM->podeReabrirFinalizada): ?>
    <div class="botoes">
        <button id="reabrir" class="outline">Reabrir OS</button>
    </div>
<?php endif; ?>