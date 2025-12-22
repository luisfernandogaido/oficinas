<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="index.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

    <header>
        <button class="voltar"></button>
    </header>

    <form>
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label for="peso">Peso</label>
                </div>
                <div class="controle">
                    <input type="number" id="peso" name="peso" step="0.1" required>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button class="primario">Salvar</button>
        </div>
    </form>

    <div id="resultado">
        <table>
            <thead>
            <tr>
                <th>Data</th>
                <th>Peso</th>
                <th>Delta</th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>