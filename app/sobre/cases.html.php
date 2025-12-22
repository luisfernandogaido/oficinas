<?php $template = new templates\Gaido() ?>

<?php $template->iniCss(); ?>
    <link rel="stylesheet" href="cases.css">
<?php $template->fimCss() ?>

<?php $template->iniMain(); ?>

<?php if ($forbidden): ?>
    <div id="forbidden">
        <p>
            Este link expirou.
        </p>
        <p>
            <a href="https://wa.me/+5514991623401" target="_blank">Solicite um novo ao Gaido</a>
        </p>
    </div>
<?php else: ?>
    <h1>Introdução</h1>
    <div class="video intro">
        <iframe
            src="https://player.vimeo.com/video/926240689?h=a517a573e5&amp;badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
            allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
            title="Sou Gaido, um experiente desenvolvedor de software">
        </iframe>
    </div>

    <h1>Caso de sucesso: Monitor Legislativo</h1>
    <div class="video monitor">
        <iframe
            src="https://player.vimeo.com/video/926248069?badge=0&amp;autopause=0&amp;player_id=0&amp;app_id=58479"
            allow="autoplay; fullscreen; picture-in-picture; clipboard-write"
            title="Sou Gaido, um experiente desenvolvedor de software">
        </iframe>
    </div>

    <script src="https://player.vimeo.com/api/player.js"></script>

<?php endif; ?>


<?php $template->fimMain(); ?>

<?php $template->iniJs(); ?>
    <script type="module" src="cases.js"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>