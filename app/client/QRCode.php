<?php
namespace app\client;

class QRCode
{
    private string $content;
    private string $res;

    /**
     * QRCode constructor.
     * @param string $content
     */
    public function __construct(string $content)
    {
        $this->content = $content;
        header("Content-Type: image/png");
        $qs = http_build_query([
            'content' => $content
        ]);
        $url = 'https://qrcode.gaido.dev/gera?' . $qs;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $this->res = curl_exec($ch);
        curl_close($ch);
    }

    public function echo()
    {
        echo $this->res;
    }

    public function file(string $name)
    {
        file_put_contents($name, $this->res);
    }
}