<?php
use templates\Gaido;

/* @var $this templates\Gaido */
?>

<link rel="stylesheet" href="<?= SITE ?>tpl/css/css.css">
<?php if ($this->favicon): ?>
    <link rel="icon" type="image/png" href="<?= SITE . $this->favicon ?>">
<?php else: ?>
    <link rel="icon" type="image/png" href="<?= SITE ?>tpl/img/giroos-v1-256-white.png">
<?php endif; ?>

<link rel="apple-touch-icon" sizes="180x180" href="<?= SITE ?>tpl/img/giroos-v1-180-iphone.png">
<link rel="manifest" href="<?= SITE ?>app/manifest.json?v=3">