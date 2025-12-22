<?php
use modelo\Os;
use modelo\Veiculo;

/** @var Os $os */
/** @var Veiculo[] $veiculos */
?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="passos.css">
    <link rel="stylesheet" href="passo6.css">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner">Veículo</div>
    </header>

    <form>
        <input type="hidden" id="hash" name="hash" value="<?= $hash ?>">
        <div class="campos">
            <div class="campo veiculos">
                <div class="rotulo">
                    <label>Veículo</label>
                </div>
                <div class="controle checks">
                    <?php foreach ($veiculos as $veiculo): ?>
                        <label>
                            <input type="radio"
                                   name="cod-veiculo"
                                   value="<?= $veiculo->codigo ?>"
                                <?= $veiculo->codigo == $os->codVeiculo ? "checked" : "" ?>>
                            <?= e($veiculo->marca . ' ' . $veiculo->modelo . ' ' . $veiculo->placa) ?>
                        </label>
                    <?php endforeach; ?>
                    <label>
                        <input type="radio" name="cod-veiculo" value="0">
                        Cadastrar novo
                    </label>
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2 campos-novo">
                <div class="rotulo">
                    <label for="placa">Placa</label>
                </div>
                <div class="controle">
                    <input type="text" id="placa" name="placa" class="placa" required value="">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo d2 campos-novo">
                <div class="rotulo">
                    <label for="km">KM</label>
                </div>
                <div class="controle">
                    <input type="number" min="0" max="10000000" id="km" name="km" required value="">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo campos-novo">
                <div class="rotulo">
                    <label for="fipe-search">Modelo</label>
                </div>
                <div class="controle">
                    <input type="text" id="fipe-search" name="fipe-search" value="" placeholder="Ex: Creta 2019">
                    <input type="hidden" id="tipo" name="tipo" value="">
                    <input type="hidden" id="marca" name="marca" value="">
                    <input type="hidden" id="modelo" name="modelo" value="">
                    <input type="hidden" id="ano" name="ano" value="">
                    <input type="hidden" id="combustivel" name="combustivel" value="">
                    <input type="hidden" id="codigo-fipe" name="codigo-fipe" value="">
                    <input type="hidden" id="valor-fipe" name="valor-fipe" value="">
                    <input type="hidden" id="id-fipe" name="id-fipe" value="" required>
                    <div id="fipe-panel">
                        <button class="close"></button>
                        <p class="marca"></p>
                        <p class="modelo"></p>
                        <p class="ano"></p>
                        <p class="combustivel"></p>
                        <p class="codigo-fipe"></p>
                    </div>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
    </form>
    <br>
    <div class="botoes campos-novo">
        <button id="prosseguir">Prosseguir</button>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'

    </script>
    <script type="module" src="passo6.js?v=2"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>