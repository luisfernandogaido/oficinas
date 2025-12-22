<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <style>
        .erro {
            padding: 1rem;
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            color: #721c24;
        }

        h1, p, .erro {
            margin-bottom: 1rem;
        }

        pre {
            overflow: auto;
        }

    </style>
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>


    <header>
        <button class="voltar"></button>
    </header>

    <h1>Ops, parece que algo deu errado...</h1>

    <div class="erro">
        <?= $e->getMessage() ?>
    </div>

    <div class="botoes">
        <button class="detalhes">Detalhes</button>
    </div>

    <pre class="oculto"><?php d($e) ?></pre>

<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module">
      document.querySelector('main button.detalhes').addEventListener('click', () => {
        document.querySelector('main pre').classList.remove('oculto');
      });
    </script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>