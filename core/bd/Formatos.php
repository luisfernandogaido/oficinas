<?php

namespace bd;

use function array_fill;
use function array_values;
use function count;
use function implode;
use function mb_strtolower;
use function str_contains;
use function str_replace;
use function ucwords;

class Formatos
{
    public static function nome($nome)
    {
        if ($nome) {
            if (mb_strlen($nome) < 3) {
                throw new FormatosException('Formato de nome deve conter ao menos 3 caracteres.');
            }
            $nome = preg_replace('/\s+/', ' ', $nome);
            return upper($nome);
        }
    }

    public static function email($email)
    {
        if ($email) {
            if (!preg_match('/^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+(\.[a-zA-Z.\-_]+)?$/', $email)) {
                throw new FormatosException('Formato de e-mail inválido');
            }
            return strtolower($email);
        }
    }

    public static function inteiro($numero)
    {
        if ($numero !== null) {
            if (is_numeric($numero)) {
                return intval($numero);
            } else {
                throw new FormatosException('Formato inteiro inválido.');
            }
        }
    }

    /**
     * @param string|null $texto
     * @return string|null
     */
    public static function ft(?string $texto): ?string
    {
        if ($texto === null || $texto === '' || $texto === '#') {
            return null;
        }
        $palavras = explode(' ', trim_all($texto));
        $palavras_modificadas = [];
        foreach ($palavras as $p) {
            if (!is_null($p) && $p != '') {
                if (str_contains($p, '@')) {
                    $palavraModificada = '"+' . $p . '*"';
                } else {
                    $palavraModificada = '+' . $p . '*';
                }
                if (str_contains($palavraModificada, '#')) {
                    $palavraModificada = str_replace('*', '', $palavraModificada);
                    $palavraModificada = str_replace('#', '', $palavraModificada);
                }
                $palavras_modificadas[] = $palavraModificada;
            }
        }
        $ret = implode(' ', $palavras_modificadas);
        if (str_contains($ret, '@')) {
            return $ret;
        }
        return str_replace(['-', '+*'], '', $ret);
    }

    /**
     * @param int|float|null $numero
     * @return string|null
     */
    public static function moeda(int|float|null $numero): ?string
    {
        if ($numero !== null) {
            return number_format(self::real($numero), 2, ',', '.');
        }
        return null;
    }

    /**
     * @param string|null $numero Valor no formato 0.000,00
     * @param bool $formatoAmericano
     * @return float|null
     */
    public static function real(?string $numero, bool $formatoAmericano = false): ?float
    {
        if ($numero === null || $numero === '') {
            return null;
        }
        if (!is_numeric($numero)) {
            if ($formatoAmericano) {
                $numero = str_replace([','], [''], $numero);
            } else {
                $numero = str_replace(['.', ','], ['', '.'], $numero);
            }
        }
        return (float)$numero;
    }

    public static function telefoneApp($telefone)
    {
        if ($telefone) {
            if (preg_match('/^(\(?[0-9?]{2}\)?)?[0-9]{3,5}\-?[0-9]{4}$/', $telefone)) {
                $telefone = str_replace(['(', ')', '-'], '', $telefone);
                switch (strlen($telefone)) {
                    case 11:
                        return '(' . substr($telefone, 0, 2) . ')' .
                            substr($telefone, 2, 5) . '-' .
                            substr($telefone, 7, 4);
                    case 10:
                        //DDD + 8 dígitos
                        return '(' . substr($telefone, 0, 2) . ')' .
                            substr($telefone, 2, 4) . '-' .
                            substr($telefone, 6);
                    case 9:
                        //9 dígitos                        
                        return substr($telefone, 0, 5) . '-' . substr($telefone, 5);
                    case 8:
                        //8 dígitos
                        return substr($telefone, 0, 4) . '-' . substr($telefone, 4);
                    default:
                        return null;
                }
            } else {
                return null;
            }
        }
    }

    public static function telefoneBd($telefone)
    {
        if ($telefone) {
            if (preg_match('/^(\(?[0-9?]{2}\)?)?[0-9]{3,5}\-?[0-9]{4}$/', $telefone)) {
                return str_replace(['(', ')', '-'], '', $telefone);
            } else {
                return null;
            }
        }
    }

    public static function cpfApp($cpf)
    {
        if ($cpf) {
            if (preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $cpf)) {
                $cpf = str_replace(['.', '-'], '', $cpf);
                return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
            } else {
                return null;
            }
        }
    }

    public static function cpfBd($cpf)
    {
        if ($cpf) {
            if (preg_match('/^[0-9]{3}\.?[0-9]{3}\.?[0-9]{3}\-?[0-9]{2}$/', $cpf)) {
                return str_replace(['.', '-'], '', $cpf);
            } else {
                return null;
            }
        }
    }

    public static function cepApp($cep)
    {
        if ($cep) {
            if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $cep)) {
                $cep = str_replace('-', '', $cep);
                return substr($cep, 0, 5) . '-' . substr($cep, -3);
            } else {
                return null;
            }
        }
    }

    public static function cepBd($cep)
    {
        if ($cep) {
            if (preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $cep)) {
                return str_replace('-', '', $cep);
            } else {
                return null;
            }
        }
    }

    public static function dataApp($data)
    {
        if ($data) {
            if (gettype($data) == 'string') {
                $data = substr($data, 0, 10);
                if (strpos($data, '/') !== false) {
                    $formato = 'd/m/Y';
                } else {
                    $formato = 'Y-m-d';
                }
                $d = \DateTime::createFromFormat($formato, $data);
                if ($d && $d->format($formato) == $data) {
                    return $d->format('d/m/Y');
                } else {
                    return null;
                }
            } elseif (is_object($data) && get_class($data) == 'DateTime') {
                return $data->format('d/m/Y');
            }
            return null;
        }
    }

    public static function dataBd($data)
    {
        if ($data) {
            if (gettype($data) == 'string') {
                if (strpos($data, '/') !== false) {
                    $formato = 'd/m/Y';
                } else {
                    $formato = 'Y-m-d';
                }
                $d = \DateTime::createFromFormat($formato, $data, new \DateTimeZone('UTC'));
                if ($d && $d->format($formato) == $data) {
                    return $d->format('Y-m-d');
                } else {
                    return null;
                }
            } elseif (is_object($data) && get_class($data) == 'DateTime') {
                return $data->format('Y-m-d');
            }
            return null;
        }
    }

    public static function dataHoraBd($dataHora)
    {
        if (is_string($dataHora)) {
            $dataHora = substr($dataHora, 0, 16);
            if (strpos($dataHora, '/') !== false) {
                $formato = 'd/m/Y H:i';
            } else {
                $formato = 'Y-m-d H:i';
            }
            $d = \DateTime::createFromFormat($formato, $dataHora, new \DateTimeZone('UTC'));
            if ($d && $d->format($formato) == $dataHora) {
                return $d->format('Y-m-d H:i');
            } else {
                return null;
            }
        } elseif (is_object($dataHora) && get_class($dataHora) == 'DateTime') {
            return $dataHora->format('Y-m-d H:i');
        }
        return null;
    }

    public static function dataHoraApp($dataHora)
    {
        if (is_string($dataHora)) {
            $dataHora = substr($dataHora, 0, 16);
            if (strpos($dataHora, '/') !== false) {
                $formato = 'd/m/Y H:i';
            } else {
                $formato = 'Y-m-d H:i';
            }
            $d = \DateTime::createFromFormat($formato, $dataHora);
            if ($d && $d->format($formato) == $dataHora) {
                return $d->format('d/m/Y H:i');
            } else {
                return null;
            }
        } elseif (is_object($dataHora) && get_class($dataHora) == 'DateTime') {
            return $dataHora->format('d/m/Y H:i');
        }
        return null;
    }

    public static function mcuApp($mcu)
    {
        $mcu = trim($mcu);
        if (preg_match('/^[0-9]{8}$/', $mcu)) {
            return $mcu;
        } else {
            return null;
        }
    }

    public static function mcuBd($mcu)
    {
        $mcu = trim($mcu);
        if (preg_match('/^[0-9]{8}$/', $mcu)) {
            return '    ' . $mcu;
        } else {
            return null;
        }
    }

    public static function cnpjApp($cnpj)
    {
        if ($cnpj) {
            $regex = '/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}-?[0-9]{2}$/';
            if (preg_match($regex, $cnpj)) {
                $cnpj = str_replace(['.', '/', '-'], '', $cnpj);
                return substr($cnpj, 0, 2) . '.' .
                    substr($cnpj, 2, 3) . '.' .
                    substr($cnpj, 5, 3) . '/' .
                    substr($cnpj, 8, 4) . '-' .
                    substr($cnpj, 12);
            } else {
                return null;
            }
        }
    }

    public static function cnpjBd($cnpj)
    {
        //74.787.271/0001-75
        if ($cnpj) {
            $regex = '/^[0-9]{2}\.?[0-9]{3}\.?[0-9]{3}\/?[0-9]{4}-?[0-9]{2}$/';
            if (preg_match($regex, $cnpj)) {
                return str_replace(['.', '/', '-'], '', $cnpj);
            } else {
                return null;
            }
        }
    }

    public static function placaApp($placa)
    {
        if ($placa) {
            $placa = trim($placa);
            if (preg_match('/^[a-zA-Z]{3}-?[0-9][a-zA-Z0-9][0-9]{2}$/', $placa)) {
                $placa = str_replace('-', '', $placa);
                $placa = upper($placa);
                return substr($placa, 0, 3) . '-' . substr($placa, 3);
            } else {
                return null;
            }
        }
    }

    public static function placaBd($placa)
    {
        if ($placa) {
            $placa = trim($placa);
            if (preg_match('/^[a-zA-Z]{3}-?[0-9][a-zA-Z0-9][0-9]{2}$/', $placa)) {
                $placa = str_replace('-', '', $placa);
                return upper($placa);
            } else {
                return null;
            }
        }
    }

    public static function urlClean($string)
    {
        $regex = "@(https?://([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?).*$)@";
        return preg_replace($regex, ' ', $string);
    }

    public static function dashToCamel($dashed)
    {
        $partes = explode('-', $dashed);
        $len = count($partes);
        for ($i = 1; $i < $len; $i++) {
            $partes[$i] = ucfirst($partes[$i]);
        }
        return implode('', $partes);
    }

    public static function codigoFipe($codigo)
    {
        $codigo = trim($codigo);
        if (!preg_match('/^\d{6}-?\d$/', $codigo)) {
            return null;
        }
        $codigo = str_replace('-', '', $codigo);
        return substr($codigo, 0, 6) . '-' . substr($codigo, 6);
    }

    public static function number(?float $numero, int $casas = 2): ?string
    {
        if ($numero === null) {
            return null;
        }
        return number_format($numero, $casas, '.', '');
    }

    public static function semAcentos(?string $texto): ?string
    {
        if ($texto === null) {
            return null;
        }
        $texto = mb_strtolower($texto);
        $search = [
            'á',
            'à',
            'ã',
            'â',
            'é',
            'è',
            'ê',
            'í',
            'ì',
            'î',
            'ó',
            'ò',
            'õ',
            'ô',
            'ú',
            'ù',
            'û',
            'ç',
        ];
        $replace = [
            'a',
            'a',
            'a',
            'a',
            'e',
            'e',
            'e',
            'i',
            'i',
            'i',
            'o',
            'o',
            'o',
            'o',
            'u',
            'u',
            'u',
            'c',
        ];
        return str_replace($search, $replace, $texto);
    }

    public static function questions(int $count): string
    {
        return implode(', ', array_fill(0, $count, "?"));
    }

    /**
     * @param string $nome
     * @return string
     */
    public static function primeiroNome(string $nome): string
    {
        $partes = array_values(array_filter(explode(' ', $nome)));
        return ucwords(mb_strtolower($partes[0], 'UTF-8'));
    }
}
