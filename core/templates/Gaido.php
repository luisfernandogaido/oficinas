<?php
namespace templates;

use Throwable;
use Sistema;
use function e;
use function error_log;
use function ob_flush;
use function ob_get_clean;
use function ob_start;
use const RAIZ;

class Gaido
{

    const INTERATIVE_WIDGET_RESIZES_VISUAL = 'resizes-visual';
    const INTERATIVE_WIDGET_RESIZES_CONTENT = 'resizes-content';
    const INTERATIVE_WIDGET_OVERLAYS_CONTENT = 'overlays-content';
    const VIEWPORT = 'width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0';

    public string $css;
    public string $js;
    public ?string $head = null;
    public ?string $favicon = null;
    public string $main;
    public ?string $footer = null;
    public ?string $titulo = null;
    public ?string $pixel = null;
    public string $interactiveWidget = self::INTERATIVE_WIDGET_RESIZES_VISUAL;

    public function iniCss()
    {
        ob_start();
    }

    public function fimCss()
    {
        $this->css = ob_get_clean();
    }

    public function iniJs()
    {
        ob_start();
    }

    public function fimJs()
    {
        $this->js = ob_get_clean();
    }

    public function iniHead()
    {
        ob_start();
    }

    public function fimHead()
    {
        $this->head = ob_get_clean();
    }

    public function iniMain()
    {
        ob_start();
    }

    public function fimMain()
    {
        $this->main = ob_get_clean();
    }

    public function iniFooter()
    {
        ob_start();
    }

    public function fimFooter()
    {
        $this->footer = ob_get_clean();
    }

    public function renderiza()
    {
        ob_clean();
        include RAIZ . 'core/templates/gaido/gaido.html.php';
        ob_flush();
    }

    /**
     * @return string|null
     */
    public function getTitulo(): ?string
    {
        if (!$this->titulo) {
            return Sistema::$nome;
        }
        return $this->titulo;
    }

    /**
     * @param string|null $titulo
     */
    public function setTitulo(?string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function pixelEnable()
    {
        $this->pixel = conf('pixel')[SERVIDOR] ?? null;
    }

    /**
     * @param Throwable $e
     * @return void
     */
    public static function erro(Throwable $e): void
    {
        error_log($e);
        include RAIZ . 'core/templates/gaido/erro.html.php';
    }

    public static function error(Throwable $e): void
    {
        echo '<div class="error">' . e($e->getMessage()) . '</div>';
    }
}
