<?php
namespace math\rand;

use bd\My;

class Embaralhador
{
    private $db;
    private $dbCidades;
    private $nomesFemininos;
    private $nomesMasculinos;
    private $sobrenomes;
    private $tiposLogradouro = [
        'Alameda',
        'Avenida',
        'Beco',
        'Estrada',
        'Praça',
        'Rua',
        'Via',
    ];
    private $dominios = [
        'hotmail.com',
        'yahoo.com.br',
        'gmail.com',
        'uol.com.br',
        'bol.com.br',
    ];
    private $separadores = [
        '',
        '.',
        '_',
        '-',
    ];
    private $bdCidades;
    private $cidades = [];

    /**
     * Embaralhador constructor.
     */
    public function __construct()
    {
        $this->db = My::con();
        $this->dbCidades = My::con('cidades');
        $this->nomesFemininos = explode(PHP_EOL, file_get_contents(__DIR__ . '/dados/nomes-femininos.txt'));
        $this->nomesMasculinos = explode(PHP_EOL, file_get_contents(__DIR__ . '/dados/nomes-masculinos.txt'));
        $this->sobrenomes = explode(PHP_EOL, file_get_contents(__DIR__ . '/dados/sobrenomes.txt'));
        $query = <<< SQL
          SELECT c.nome, e.uf
          FROM cidade c
          INNER JOIN estado e ON c.estado = e.id        
SQL;
        $r = $this->dbCidades->query($query);
        while ($l = $r->fetch_assoc()) {
            $this->cidades[] = $l;
        }
    }

    public function geraEndereco()
    {
        if (mt_rand(0, 1) == 0) {
            return $this->geraEnderecoFeminino();
        } else {
            return $this->geraEnderecoMasculino();
        }
    }

    public function geraEnderecoFeminino()
    {
        $tipoLogradouro = $this->tiposLogradouro[mt_rand(0, count($this->tiposLogradouro) - 1)] . ' ';
        return $tipoLogradouro . $this->geraNomeFeminino();
    }

    public function geraNomeFeminino($maximoNomes = 3, $maximoSobrenomes = 3)
    {
        $nomes = mt_rand(1, $maximoNomes);
        $sobrenomes = mt_rand(1, $maximoSobrenomes);
        $nome = '';
        for ($j = 1; $j <= $nomes; $j++) {
            $nome .= $this->nomesFemininos[mt_rand(0, count($this->nomesFemininos) - 1)] . ' ';
        }
        for ($j = 1; $j <= $sobrenomes; $j++) {
            $nome .= $this->sobrenomes[mt_rand(0, count($this->sobrenomes) - 1)] . ' ';
        }
        $nome = trim($nome);
        return $nome;
    }

    public function geraEnderecoMasculino()
    {
        $tipoLogradouro = $this->tiposLogradouro[mt_rand(0, count($this->tiposLogradouro) - 1)] . ' ';
        return $tipoLogradouro . $this->geraNomeMasculino();
    }

    public function geraNomeMasculino($maximoNomes = 3, $maximoSobrenomes = 3)
    {
        $nomes = mt_rand(1, $maximoNomes);
        $sobrenomes = mt_rand(1, $maximoSobrenomes);
        $nome = '';
        for ($j = 1; $j <= $nomes; $j++) {
            $nome .= $this->nomesMasculinos[mt_rand(0, count($this->nomesMasculinos) - 1)] . ' ';
        }
        for ($j = 1; $j <= $sobrenomes; $j++) {
            $nome .= $this->sobrenomes[mt_rand(0, count($this->sobrenomes) - 1)] . ' ';
        }
        $nome = trim($nome);
        return $nome;
    }

    public function geraBairro()
    {
        $bairro = $this->nomesMasculinos[mt_rand(0, count($this->nomesMasculinos) - 1)] . ' ' .
            $this->nomesFemininos[mt_rand(0, count($this->nomesFemininos) - 1)];
        return $bairro;
    }

    public function geraEmail($nome = null)
    {
        if (!$nome) {
            $nome = $this->geraNome();
        }
        $email = \lower($nome) . '@' . $this->dominios[mt_rand(0, count($this->dominios) - 1)];
        $separador = $this->separadores[mt_rand(0, count($this->separadores) - 1)];
        $search = [
            ' ',
            '/',
            ',',
            'á',
            'é',
            'í',
            'ó',
            'ú',
            'â',
            'ê',
            'î',
            'ô',
            'û',
            'ã',
            'õ',
            'ç',
            'à',
            'è',
            'ì',
            'ò',
            'ù',
            "'",
            '?',
            'ø',
            '(',
            ')',
        ];
        $replace = [
            $separador,
            $separador,
            '',
            'a',
            'e',
            'i',
            'o',
            'u',
            'a',
            'e',
            'i',
            'o',
            'u',
            'a',
            'o',
            'c',
            'a',
            'e',
            'i',
            'o',
            'u',
            '',
            '',
            'o',
            '',
            '',
        ];
        $email = str_replace($search, $replace, $email);
        return $email;
    }

    public function geraNome($maximoNomes = 3, $maximoSobrenomes = 3)
    {
        if (mt_rand(0, 1) == 0) {
            return $this->geraNomeFeminino($maximoNomes, $maximoSobrenomes);
        } else {
            return $this->geraNomeMasculino($maximoNomes, $maximoSobrenomes);
        }
    }

    public function geraCpf()
    {
        $cpf = '';
        for ($j = 0; $j < 11; $j++) {
            $cpf .= mt_rand(0, 9);
        }
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9);
    }

    public function geraNumeroEndereco()
    {
        if (mt_rand(0, 100) > 75) {
            $numero = rand(1, 100) . '-' . rand(1, 200);
        } else {
            $numero = rand(1, 9999);
        }
        return $numero;
    }

    public function geraCep()
    {
        $cep = '';
        for ($j = 0; $j < 8; $j++) {
            $cep .= mt_rand(0, 9);
        }
        return substr($cep, 0, 5) . '-' . substr($cep, 5);
    }

    public function geraCidadeEstado()
    {
        return $this->cidades[mt_rand(0, count($this->cidades) - 1)];
    }

    public function geraNascimento()
    {
        $data = new \DateTime('1900-01-01');
        $dias_somar = mt_rand(1, 35000);
        $data->add(\DateInterval::createFromDateString($dias_somar . ' day'));
        $nascimento = $data->format('d/m/Y');
        return $nascimento;
    }

    public function geraRg()
    {
        $rg = '';
        for ($j = 0; $j < 9; $j++) {
            $rg .= mt_rand(0, 9);
        }
        $rg = substr($rg, 0, 2) . '.' . substr($rg, 2, 3) . '.' . substr($rg, 5, 3) . '-' . substr($rg, 8);
        return $rg;
    }

    public function geraTelefone()
    {
        $tel = '';
        for ($j = 0; $j < 11; $j++) {
            $tel .= mt_rand(0, 9);
        }
        return $tel;
    }

    public function geraCnpj()
    {
        $cnpj = '';
        for ($j = 0; $j < 14; $j++) {
            $cnpj .= mt_rand(0, 9);
        }
        return $cnpj;
    }

    public function geraNumero($numeroBase, $percentualDesvio)
    {
        return $numeroBase * (1 + mt_rand(-$percentualDesvio, $percentualDesvio) / 100);
    }

}