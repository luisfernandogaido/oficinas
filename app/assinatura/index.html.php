<?php
use modelo\Assinatura;

/**
 * @var Assinatura $vigente
 */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Assinatura</div>
    </header>

    <div id="ctn">
        <?php if ($vigente): ?>
            <div id="ativa" class="shadow">
                <h1>Vigente</h1>
                <p><?= $vigente->nome ?></p>
                <p><?= $iniVigente ?> - <?= $fimVigente ?></p>
                <br>
                <?php if(!$futuras): ?>
                    <p>
                        <a href="assine.php">Estender a partir de <?= $ultimoDiaH ?></a>
                    </p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div id="ativa" class="shadow">
                <p>
                    Você não tem uma assinatura em vigência.
                </p>
                <br>
                <div class="botoes">
                    <a href="assine.php" class="button primario">Assine</a>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($futuras): ?>
            <div id="historico" class="shadow">
                <h1><?= $tituloFuturas ?></h1>
                <table class="cards mob">
                    <tbody>
                    <?php foreach ($futuras as $assinatura): ?>
                        <tr>
                            <td><?= $assinatura['ini_h'] ?> - <?= $assinatura['fim_h'] ?></td>
                            <td><?= $assinatura['nome'] ?></td>
                            <td class="num"><?= $assinatura['valor_h'] ?></td>
                            <td class="acoes">
                                <?php if ($assinatura['asaas_invoice_url']): ?>
                                    <a class="button receipt" href="<?= $assinatura['asaas_invoice_url'] ?>"
                                       target="_blank"></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p>
                    <a href="assine.php">Estender a partir de <?= $ultimoDiaH ?></a>
                </p>
            </div>
        <?php endif; ?>
        <?php if ($historico): ?>
            <div id="historico" class="shadow">
                <h1>Histórico</h1>
                <table class="cards mob">
                    <tbody>
                    <?php foreach ($historico as $assinatura): ?>
                        <tr>
                            <td><?= $assinatura['ini_h'] ?> - <?= $assinatura['fim_h'] ?></td>
                            <td><?= $assinatura['nome'] ?></td>
                            <td class="num"><?= $assinatura['valor_h'] ?></td>
                            <td class="acoes">
                                <?php if ($assinatura['asaas_invoice_url']): ?>
                                    <a class="button receipt" href="<?= $assinatura['asaas_invoice_url'] ?>"
                                       target="_blank"></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>