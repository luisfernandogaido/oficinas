<?php

use bd\Formatos;
use JetBrains\PhpStorm\NoReturn;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

function autoload($classe): void
{
    if (str_contains($classe, 'modelo') ||
        str_contains($classe, 'app') ||
        str_contains($classe, 'tpl') ||
        str_contains($classe, 'cli') && !str_contains($classe, 'client')
    ) {
        $arquivo = RAIZ . str_replace(['\\'], DIRECTORY_SEPARATOR, $classe) . '.php';
    } else {
        $arquivo = RAIZ . str_replace(['\\'], DIRECTORY_SEPARATOR, 'core/' . $classe) . '.php';
    }
    if (file_exists($arquivo)) {
        include $arquivo;
        return;
    }
    $arquivo = RAIZ . "/../lib/" . str_replace('\\', DIRECTORY_SEPARATOR, $classe) . ".php";
    if (file_exists($arquivo)) {
        include $arquivo;
    }
}

/**
 * @param $configuracao string
 * @return array
 * @throws Exception
 */
function conf(string $configuracao): array
{
    $arquivo = RAIZ . 'conf/' . $configuracao . '.php';
    if (!file_exists($arquivo)) {
        throw new Exception('Configuração ' . $configuracao . ' inexistente.');
    }
    return include($arquivo);
}

function deltaT()
{
    return microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'];
}

/**
 * Converte um número real em formato de moeda brasileira.<br>
 * Sensacional, não é mesmo?
 *
 * @param float $numero
 * @return string
 */
function moeda(float $numero): string
{
    return number_format($numero, 2, ',', '.');
}

/* * Converte uma string no formato #.###,## em número real.
 *
 * @param string $str_valor
 * @return double
 */

function real($str_valor)
{
    $float = str_replace(['.', ','], ['', '.'], $str_valor);
    return floatval($float);
}

function hasha256($dado)
{
    return hash('sha256', $dado);
}

/**
 * @param mixed $v
 * @return string
 */
function hash_serialize(mixed $v): string
{
    return hash('sha256', serialize($v));
}

/**
 * @param int $caracteres
 * @return string
 * @throws Exception
 */
function token(int $caracteres = 64): string
{
    return bin2hex(random_bytes($caracteres / 2));
}

/**
 * @param int $n
 * @return string
 * @throws Exception
 */
function myHash(int $n = 11): string
{
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_';
    $s = '';
    for ($i = 0; $i < $n; $i++) {
        $s .= $chars[random_int(0, 63)];
    }
    return $s;
}

/**
 * @param int $n
 * @return string
 * @throws Exception
 */
function myToken(int $n = 6): string
{
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $s = '';
    for ($i = 0; $i < $n; $i++) {
        $s .= $chars[random_int(0, 35)];
    }
    return $s;
}

function e(?string $txt): string
{
    if ($txt === null) {
        return '';
    }
    return htmlentities($txt, ENT_QUOTES, 'UTF-8');
}

/**
 * Cria uma javascript string json que pode ser seguramente convertida para um objeto javascript.
 * Elimina a necessidade de enviar dados complexos do PHP para Javascript via async requests.
 *
 * @param array $value
 * @return string
 */
function asje(array $value): string
{
    return addslashes(json_encode($value));
}

function hs(string $txt): string
{
    return htmlspecialchars($txt, ENT_COMPAT);
}

/* * Permite a escrita de strings Javascript diretamente em trechos de código Javascript.<br>
 * Exemplo: var qry = JSON.parse("<?= ejs(json_encode($qry)) ?>");
 *
 * @param string $str_js String a ser escapada.
 * @return string String escapada
 */

function ejs($str_js)
{
    return str_replace(["\n", '"'], ['\n', '\"'], $str_js);
}

function d($array)
{
    if (php_sapi_name() === 'cli') {
        print_r($array);
        echo PHP_EOL;
    } else {
        echo '<br><pre class="printr">';
        print_r($array);
        echo '</pre>';
    }
}

/**
 * @param $dados
 * @return void
 */
#[NoReturn]
function dd($dados): void
{
    d($dados);
    exit;
}

/**
 * @param array $dados
 * @return void
 */
#[NoReturn]
function jj(array $dados): void
{
    echo json_encode($dados, JSON_PRETTY_PRINT);
    exit;
}

function vd($array)
{
    if (php_sapi_name() === 'cli') {
        var_dump($array);
        echo PHP_EOL;
    } else {
        echo '<br><pre class="printr">';
        var_dump($array);
        echo '</pre>';
    }
}

function vdd($dados): never
{
    vd($dados);
    exit;
}

function ve($value): void
{
    var_export($value);
}

function vee($value): void
{
    var_export($value);
    exit;
}

function cacheia($dias)
{
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $dias * 86400));
    header('Cache-Control: max-age=' . $dias * 86400);
    header('Pragma: ');
}

function gzip()
{
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
        ob_start('ob_gzhandler');
    }
}

function ajax($tipo = 'html', $diasCache = 0)
{
    if ($diasCache) {
        cacheia($diasCache);
    } else {
        header('Cache-Control: no-cache, must-revalidate');
    }
    $tipo = strtolower($tipo);
    switch ($tipo) {
        case 'html':
            $GLOBALS['AJAX'] = 'html';
            header('Content-type: text/html; charset=utf-8');
            break;
        case 'json':
            $GLOBALS['AJAX'] = 'json';
            header('Content-type: application/json; charset=utf-8');
            break;
        case 'xml':
            $GLOBALS['AJAX'] = 'xml';
            header('Content-type: text/xml; charset=utf-8');
            echo '<?xml version="1.0" encoding="utf-8"?>';
            break;
        case 'text':
            $GLOBALS['AJAX'] = 'text';
            header('Content-type: text/plain; charset=utf-8');
            $GLOBALS['COMPRESSAO'] = false;
            break;
    }
    gzip();
}

function trim_all($string)
{
    return trim(preg_replace('/\s+/', ' ', $string));
}

/**
 *
 * @return string retorna o dia de hoje no formato dd/mm/yyyy.
 */
function hoje()
{
    return date('d/m/Y');
}

/**
 * Converte uma string em maiúsculas.
 * @param string $string String.
 * @return string String em maiúsculas.
 */
function upper($string)
{
    return mb_strtoupper($string);
}

/**
 * Converte uma string em minúsculas.
 * @param string $string String.
 * @return string String em minúsculas.
 */
function lower($string)
{
    return mb_strtolower($string);
}

/**
 * @param $ret
 * @return void
 */
function printJson($ret): void
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($ret, JSON_PRETTY_PRINT);
}

/**
 * @param string $msg
 * @return void
 */
function printError(string $msg): void
{
    echo '<div class="error">' . e($msg) . '</div>';
}

function cifra($texto, $password)
{
    return openssl_encrypt($texto, 'AES256', $password, 0, "gaidoluisfernand");
}

function decifra($texto, $password)
{
    return openssl_decrypt($texto, 'AES256', $password, 0, 'gaidoluisfernand');
}

function assina($string, $chave)
{
    return base64_encode(hash_hmac("sha512", $string, $chave));
}

function verifica_assinatura($string, $assinatura, $chave)
{
    return hash_equals(hash_hmac("sha512", $string, $chave), base64_decode($assinatura));
}

function envia_email($para, $assunto, $mensagem_html, $mensagem_txt = null, $unsubscribe = null, $de = null)
{
    if (!$de) {
        $emails = conf('email');
        $de = $emails[SERVIDOR];
    }
    if (SERVIDOR == 'localhost') {
        return;
    }
    $to = [];
    $to_headers = [];
    if (is_array($para)) {
        foreach ($para as $valor) {
            $to_headers[] = $valor;
            $partes = explode(' ', $valor);
            $to[] = str_replace(['<', '>'], '', $partes[count($partes) - 1]);
        }
    } else {
        $to_headers[] = $para;
        $partes = explode(' ', $para);
        $to[] = str_replace(['<', '>'], '', $partes[count($partes) - 1]);
    }
    $headers = [];
    $headers[] = "MIME-Version: 1.1";
    $headers[] = "From: " . $de;
    $headers[] = "Return-Path: " . $de;
    $headers[] = "Reply-To: " . $de;
    $headers[] = "Subject: " . $assunto;
    $headers[] = "X-Mailer: PHP/" . phpversion();
    $headers[] = "To: " . implode(', ', $to_headers);
    if ($unsubscribe) {
        $headers[] = "List-Unsubscribe: " . $unsubscribe;
    }
    if ($mensagem_txt) {
        $boundary = $boundary = strtoupper(token(16));
        $headers[] = "Content-type: multipart/alternative;boundary=" . $boundary;
        $mensagem = "This is multipart message using MIME\n" .
            '--' . $boundary . "\n" .
            "Content-type: text/plain;charset=UTF-8\n\n" .
            $mensagem_txt . "\n\n" .
            "--" . $boundary . "\n" .
            "Content-type: text/html;charset=UTF-8\n\n" .
            $mensagem_html . "\n\n" .
            "--" . $boundary . "--";
    } else {
        $headers[] = "Content-type: text/html; charset=utf-8";
        $mensagem = $mensagem_html;
    }
    return mail(implode(', ', $to), $assunto, $mensagem, implode("\r\n", $headers));
}

/**
 * @param $origem
 * @param $lado
 * @param $destino
 * @return int Tamanho do arquivo gerado em bytes.
 * @throws Exception
 */
function miniatura($origem, $lado, $destino): int
{
    $lado = round($lado);
    $type = @exif_imagetype($origem);
    switch ($type) {
        case IMAGETYPE_GIF:
            $extensao = 'gif';
            $im = @imagecreatefromgif($origem);
            break;
        case IMAGETYPE_PNG:
            $extensao = 'png';
            $im = @imagecreatefrompng($origem);
            imagealphablending($im, false);
            imagesavealpha($im, true);
            break;
        case IMAGETYPE_JPEG:
            $extensao = pathinfo($origem, PATHINFO_EXTENSION);
            $im = @imagecreatefromjpeg($origem);
            break;
        case IMAGETYPE_BMP:
            $extensao = 'bmp';
            $im = @imagecreatefrombmp($origem);
            break;
        case IMAGETYPE_WEBP:
            $extensao = 'webp';
            $im = @imagecreatefromwebp($origem);
            break;
        default:
            throw new Exception('miniatura: formato de imagem inválido');
    }
    if (!$im) {
        throw new Exception('erro ao criar miniatura de imagem');
    }
    if (!str_contains($destino, '.')) {
        $destino .= '.' . $extensao;
    }
    $exif = @exif_read_data($origem);
    $orientation = $exif['Orientation'] ?? null;
    $computed = $exif['COMPUTED'] ?? null;
    if ($orientation && $computed) {
        switch ($orientation) {
            case 8:
                $im = imagerotate($im, 90, 0);
                $larguraOriginal = $computed['Height'];
                $alturaOriginal = $computed['Width'];
                break;
            case 3:
                $im = imagerotate($im, 180, 0);
                $larguraOriginal = $computed['Width'];
                $alturaOriginal = $computed['Height'];
                break;
            case 6:
                $im = imagerotate($im, -90, 0);
                $larguraOriginal = $computed['Height'];
                $alturaOriginal = $computed['Width'];
                break;
            default:
                $larguraOriginal = $computed['Width'];
                $alturaOriginal = $computed['Height'];
        }
    } else {
        list($larguraOriginal, $alturaOriginal) = getimagesize($origem);
    }
    $tranparencia = function ($im2, $lado, $altura) {
        imagealphablending($im2, false);
        imagesavealpha($im2, true);
        $transparent = imagecolorallocatealpha($im2, 255, 255, 255, 127);
        imagefilledrectangle($im2, 0, 0, $lado, $altura, $transparent);
    };
    if ($larguraOriginal > $alturaOriginal) {
        $largura = $lado;
        $taxa = $larguraOriginal / $lado;
        $altura = round($alturaOriginal / $taxa);
        $im2 = imagecreatetruecolor($largura, $altura);
        if ($type == IMAGETYPE_PNG) {
            $tranparencia($im2, $lado, $altura);
        }
        imagecopyresampled($im2, $im, 0, 0, 0, 0, $lado, $altura, $larguraOriginal, $alturaOriginal);
    } else {
        $altura = $lado;
        $taxa = $alturaOriginal / $lado;
        $largura = round($larguraOriginal / $taxa);
        $im2 = imagecreatetruecolor(round($largura), round($altura));
        if ($type == IMAGETYPE_PNG) {
            $tranparencia($im2, $lado, $altura);
        }
        imagecopyresampled($im2, $im, 0, 0, 0, 0, $largura, $lado, $larguraOriginal, $alturaOriginal);
    }
    if ($largura > $larguraOriginal || $altura > $alturaOriginal) {
        $im2 = $im;
    }
    switch ($extensao) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($im2, $destino, 82);
            break;
        case 'gif':
            imagegif($im2, $destino);
            break;
        case 'png':
            imagepng($im2, $destino, 5);
            break;
        case 'bmp':
            imagebmp($im2, $destino);
            break;
        case 'webp':
            imagewebp($im2, $destino);
            break;
    }
    return filesize($destino);
}

function cors()
{
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Key, Cache-Control");
}

function inclui_conteudo($arquivo)
{
    ob_start();
    include $arquivo;
    return ob_get_clean();
}

/**
 * @return void
 * @throws Exception
 */
function cli(): void
{
    if (php_sapi_name() != 'cli') {
        throw new Exception('Somente CLI.');
    }
}

/**
 *
 * @return RecursiveDirectoryIterator[]
 */
function listaArquivosRecursivamente($diretorio)
{
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($diretorio));
    foreach ($it as $k => $v) {
        if ($v->getFilename()[0] !== '.') {
            yield $v;
        }
    }
}

function lock()
{
    static $x = null;
    $x = fopen("lock.txt", "w");
    if (!flock($x, LOCK_EX | LOCK_NB)) {
        exit;
    }
}

function cache($arquivo, $callback)
{
    if (file_exists($arquivo) && time() - filemtime($arquivo) < 10) {
        include $arquivo;
    } else {
        ob_start();
        $callback();
        $cache = ob_get_clean();
        file_put_contents($arquivo, $cache);
        echo $cache;
    }
}

function zip($pasta, $arquivo)
{
    $base = basename($pasta);
    $zip = new ZipArchive();
    $zip->open($arquivo, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    $zip->addEmptyDir($base);
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($pasta),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($pasta) + 1);
            $zip->addFile($filePath, $base . '/' . $relativePath);
        }
    }
}

/**
 * @param string $usuario
 * @param string $para
 * @param string $assunto
 * @param string $corpo
 * @param bool $lento
 * @param string|null $de
 * @param string|null $senha
 * @param string|null $provedor
 * @return void
 * @throws Exception
 */
function gmail(
    string $usuario,
    string $para,
    string $assunto,
    string $corpo,
    bool $lento,
    ?string $de = null,
    ?string $senha = null,
    ?string $provedor = null
): void {
    $de = $de ?? $_SERVER['EMAIL_USER'] ?? '';
    $senha = $senha ?? $_SERVER['EMAIL_PASS'] ?? '';
    $provedor = $provedor ?? $_SERVER['EMAIL_PROVIDER'] ?? '';
    $body = json_encode([
        'app' => Sistema::$app,
        'usuario' => $usuario,
        'para' => $para,
        'assunto' => $assunto,
        'corpo' => $corpo,
        'de' => $de,
        'senha' => $senha,
        'provedor' => $provedor,
        'lento' => $lento,
    ]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_URL, 'https://gmail.gaido.dev/envia');
    curl_setopt($ch, CURLOPT_USERAGENT, "cURL");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer 3f3f380dc57a',
        'Content-Type: application/json',
        'Content-Length: ' . strlen($body),
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $ret = curl_exec($ch);
    if ($ret != "enviado\n") {
        throw new Exception('Erro ao enviar Gmail.' . $ret);
    }
}

/**
 * Gera links de WhatsApp com base em um telefone com uma mensagem opcional.
 * Formato recomendado, em detrimento do legado web.whatsapp.com/send.
 *
 * @param string $telefone
 * @param string $mensagem
 * @return string
 */
function waMe(string $telefone, string $mensagem = ''): string
{
    $telefone = "55" . Formatos::telefoneBd($telefone);
    $mensagem = urlencode($mensagem);
    return "https://wa.me/$telefone?text=$mensagem";
}

/**
 * @return DateTime
 * @throws Exception
 */
function now(): DateTime
{
    return new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
}

/**
 * Volta uma página no histórico
 */
#[NoReturn]
function back(): never
{
    header("location:javascript://history.go(-1)");
    exit;
}


/**
 * @return string
 * @throws Exception
 */
function dirFiles(): string
{
    $dir = conf('files')[SERVIDOR]['dir'] ?? null;
    if (!$dir) {
        throw new Exception("dirFiles: conf não encontrada para " . SERVIDOR);
    }
    return $dir;
}

/**
 * @return string
 * @throws Exception
 */
function dirFilesApp(): string
{
    return dirFiles() . '/' . Sistema::$app;
}

/**
 * @return string
 * @throws Exception
 */
function domFiles(): string
{
    $dom = conf('files')[SERVIDOR]['dom'] ?? null;
    if (!$dom) {
        throw new Exception("dirFiles: conf não encontrada para " . SERVIDOR);
    }
    return $dom;
}

/**
 * @return string
 * @throws Exception
 */
function domFilesApp(): string
{
    return domFiles() . '/' . Sistema::$app;
}

function headerExcel(string $filename): void
{
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
}

function loadExcel()
{
    include_once RAIZ . '../lib/vendor/autoload.php';
}

/**
 * @param array $array
 * @param string $fileName
 * @param bool $onthefly
 * @return void
 * @throws PhpOffice\PhpSpreadsheet\Exception
 * @throws PhpOffice\PhpSpreadsheet\Writer\Exception
 */
function excelFromArray(array $array, string $fileName, bool $onthefly = true): void
{
    loadExcel();
    $spreadsheet = new Spreadsheet();
    $spreadsheet->setActiveSheetIndex(0)->fromArray(
        $array,
        null,
        'A1'
    );
    $sheet = $spreadsheet->getActiveSheet();
    foreach ($sheet->getColumnIterator() as $column) {
        $sheet->getColumnDimension($column->getColumnIndex())->setAutoSize(true);
    }
    $writerType = 'Xls';
    if (str_contains($fileName, 'xlsx')) {
        $writerType = 'Xlsx';
    }
    $writer = IOFactory::createWriter($spreadsheet, $writerType);
    if ($onthefly) {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $writer->save('php://output');
        return;
    }
    $writer->save($fileName);
}

/**
 * @param string $filename
 * @param string|null $aba
 * @return array
 */
function excelToArray(string $filename, ?string $aba = null): array
{
    loadExcel();
    ini_set('memory_limit', '2048M');
    $excel = IOFactory::load($filename);
    if ($aba) {
        $aba = $excel->getSheetByName($aba);
    } else {
        $aba = $excel->getActiveSheet();
    }
    return $aba->toArray();
}

/**
 * @param string|null $text
 * @param string $divider
 * @return string|null
 */
function slug(?string $text, string $divider = '-'): ?string
{
    if ($text === null) {
        return null;
    }
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, $divider);
    $text = preg_replace('~-+~', $divider, $text);
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

/**
 * @param array $data
 * @return void
 */
function loga(array $data): void
{
    global $_redis;
    if (!$_redis) {
        $_redis = new Redis();
        $_redis->pconnect('localhost');
    }
    $meta = [
        'timestamp' => date('Y-m-d\TH:i:s-03:00'),
    ];
    $data = array_merge($meta, $data);
    $_redis->rPush(Sistema::$app, json_encode($data));
}

/**
 * @param string $assunto
 * @param string $mensagem
 * @param bool $lento
 * @return void
 * @throws Exception
 */
function notifyMe(string $assunto, string $mensagem = '', bool $lento = true): void
{
    gmail('gaido', 'luisfernandogaido@gmail.com', $assunto, $mensagem, $lento);
}

function normaliza(string $texto): string
{
    //chat-gpt me ensinou a remover caracteres especiais, mas estou com medo de usar em variados casos.
    //meti o "@" para silenciar notices.

//    $texto = preg_replace('/[^\x20-\x7E]/', '', $texto);

    // Converte caracteres com acento para suas versões sem acento
    $texto = @iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
    // Remove todos os caracteres que não são letras, números ou espaço
    $texto = preg_replace('/[^a-zA-Z0-9\s]/', '', $texto);
    // Opcional: Transformar o texto para letras minúsculas
    $texto = strtolower($texto);
    // Remove espaços extras no início e no fim, e múltiplos espaços internos
    return trim(preg_replace('/\s+/', ' ', $texto));
}

/**
 * @param string $url
 * @param string|null $filename
 * @return void
 */
function streamFile(string $url, ?string $filename = null): void
{
    $head = curl_init($url);
    curl_setopt($head, CURLOPT_NOBODY, true);
    curl_setopt($head, CURLOPT_HEADER, true);
    curl_setopt($head, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($head, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($head, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($head, CURLOPT_SSL_VERIFYHOST, false);
    curl_exec($head);
    $info = curl_getinfo($head);
    curl_close($head);

    if ($info['http_code'] != 200) {
        http_response_code($info['http_code']);
        echo "Erro ao acessar o arquivo";
        return;
    }

    $contentType = $info['content_type'] ?? 'application/octet-stream';
    $contentLength = $info['download_content_length'] ?? -1;
    $fileName = $filename ?? basename($url);
    if (!str_contains($fileName, '.')) {
        $extension = pathinfo(basename($url), PATHINFO_EXTENSION);
        $fileName .= '.' . $extension;
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function ($ch, $data) {
        echo $data;
        return strlen($data);
    });
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: inline; filename="' . $fileName . '"');
    if ($contentLength > 0) {
        header('Content-Length: ' . $contentLength);
    }
    ob_end_flush();
    flush();
    curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Erro no cURL: ' . curl_error($ch);
    }
    curl_close($ch);
}

