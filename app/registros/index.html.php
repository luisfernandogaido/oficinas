<?php

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
<link rel="stylesheet" href="index.css?3">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

<div class="tela" id="main">

    <header>
        <button class="voltar"></button>
        <div class="banner">Registros</div>
    </header>

    <div id="controles" class="checks">
        <label>
            <input type="radio" name="type" value="agua" checked>
            agua
        </label>
        <label>
            <input type="radio" name="type" value="hl">
            hl
        </label>
        <label>
            <input type="radio" name="type" value="barba">
            barba
        </label>
        <label>
            <input type="radio" name="type" value="cabelo">
            cabelo
        </label>
        <label>
            <input type="radio" name="type" value="outros">
            outros
        </label>
    </div>

    <div id="resultado"></div>

    <button class="novo"></button>
</div>

<div class="tela" id="registro">
    <header>
        <button class="voltar"></button>
        <div class="banner">Novo</div>
    </header>
    <form id="f-agua">
        <input type="hidden" name="codigo">
        <input type="hidden" name="type" value="agua">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Data</label>
                </div>
                <div class="controle">
                    <input type="datetime-local" name="date" required step="1">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo"></div>
                <div class="controle checks">
                    <label>
                        <input type="radio" name="ml" value="1200" checked>
                        1200ml
                    </label>
                    <label>
                        <input type="radio" name="ml" value="500">
                        500ml
                    </label>
                    <label>
                        <input type="radio" name="ml" value="outro">
                        outro
                    </label>
                </div>
            </div>
            <div class="campo" id="c-ml">
                <div class="rotulo">
                    <label>ml</label>
                </div>
                <div class="controle">
                    <input type="number" name="ml-custom" min="0" step="1" required>
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button type="button" class="primario">Salvar</button>
        </div>
    </form>
    <form id="f-hl">
        <input type="hidden" name="codigo">
        <input type="hidden" name="type" value="hl">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Data</label>
                </div>
                <div class="controle">
                    <input type="datetime-local" name="date" required step="1">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="controle checks">
                    <label>
                        <input type="checkbox" name="exclama" value="1">
                        exclama!
                    </label>
                </div>
            </div>
        </div>
        <div class="botoes">
            <button type="button" class="primario">Salvar</button>
        </div>
    </form>
    <form id="f-barba">
        <input type="hidden" name="codigo">
        <input type="hidden" name="type" value="barba">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Data</label>
                </div>
                <div class="controle">
                    <input type="datetime-local" name="date" required step="1">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Obs</label>
                </div>
                <div class="controle">
                    <input type="text" name="obs">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button type="button" class="primario">Salvar</button>
        </div>
    </form>
    <form id="f-cabelo">
        <input type="hidden" name="codigo">
        <input type="hidden" name="type" value="cabelo">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Data</label>
                </div>
                <div class="controle">
                    <input type="datetime-local" name="date" required step="1">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Obs</label>
                </div>
                <div class="controle">
                    <input type="text" name="obs">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button type="button" class="primario">Salvar</button>
        </div>
    </form>
    <form id="f-outros">
        <input type="hidden" name="codigo">
        <input type="hidden" name="type" value="outros">
        <div class="campos">
            <div class="campo">
                <div class="rotulo">
                    <label>Data</label>
                </div>
                <div class="controle">
                    <input type="datetime-local" name="date" required step="1">
                </div>
                <div class="mensagem"></div>
            </div>
            <div class="campo">
                <div class="rotulo">
                    <label>Obs</label>
                </div>
                <div class="controle">
                    <input type="text" name="obs">
                </div>
                <div class="mensagem"></div>
            </div>
        </div>
        <div class="botoes">
            <button type="button" class="primario">Salvar</button>
        </div>
    </form>
</div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
<script type="module" src="index.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>

