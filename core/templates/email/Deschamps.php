<?php

namespace templates\email;

use function ob_clean;
use function ob_get_clean;
use function ob_start;

class Deschamps
{
    public string $body;
    public bool $rodape = false;
    public string $pStyle = <<< P_STYLE
        margin:0;padding-bottom:20px;margin-bottom:20px;border-bottom:1px solid #eee
    P_STYLE;

    public function iniBody(): void
    {
        ob_start();
    }

    public function fimBody(): void
    {
        $this->body = ob_get_clean();
    }

    public function renderiza(): void
    {
        ob_clean();
        include RAIZ . 'core/templates/email/deschamps/deschamps.html.php';
    }
}