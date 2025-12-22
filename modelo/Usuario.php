<?php

namespace modelo;

use bd\Formatos;
use bd\My;
use datahora\DataHora;
use DateTime;
use Exception;

use function array_fill;
use function array_merge;
use function boolval;
use function explode;
use function implode;
use function now;
use function password_hash;
use function preg_match;
use function str_replace;
use function strlen;
use function trim;
use function usort;

use const PASSWORD_DEFAULT;

class Usuario
{
    const string PERFIL_MASTER = 'master';
    const string PERFIL_ADMIN = 'admin';
    const string PERFIL_PADRAO = 'padrao';
    const string PERFIL_FUNCIONARIO = 'funcionario';
    const string PERFIL_CLIENTE = 'cliente';

    const array PERFIS = [
        'master',
        'admin',
        'padrao',
        'funcionario',
        'cliente',
    ];

    const string STATUS_PENDENTE = 'pendente';
    const string STATUS_ATIVO = 'ativo';
    const string STATUS_INATIVO = 'inativo';
    const string STATUS_PROVISORIO = 'provisorio';

    const STATUS = [
        self::STATUS_PENDENTE,
        self::STATUS_ATIVO,
        self::STATUS_INATIVO,
        self::STATUS_PROVISORIO,
    ];

    const PAGE_SIZE = 50;

    public int $codigo = 0;
    public int $codConta;
    public string $conta;
    public bool $contaAtiva;
    public string $nome;
    public string $email;
    public ?string $celular = null;
    public ?string $celularRaw {
        get => Formatos::telefoneBd($this->celular);
    }
    public bool $whatsAppValidado = false;
    public bool $forcarAssinatura = false;
    public ?string $cpfCnpj = null;
    public ?string $senha = null;
    public string $perfil = Usuario::PERFIL_PADRAO;
    public string $status = Usuario::STATUS_ATIVO;
    public string $apelido;
    public string $criacao;
    public string $alteracao;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        if (!$codigo) {
            return;
        }
        $c = My::con();
        $query = <<< CONSTROI
                SELECT u.cod_conta, c.nome conta, c.ativa conta_ativa, u.nome, u.email, u.celular,
                       u.whatsapp_validado, u.forcar_assinatura, u.cpf_cnpj, u.perfil, u.`status`,
                       u.apelido, u.criacao, u.alteracao
                FROM usuario u
                INNER JOIN conta c ON u.cod_conta = c.codigo
                WHERE u.codigo = $codigo;
            CONSTROI;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Usuário não cadastrado.');
        }
        $this->codigo = $codigo;
        $this->codConta = $l['cod_conta'];
        $this->conta = $l['conta'];
        $this->contaAtiva = boolval($l['conta_ativa']);
        $this->nome = $l['nome'];
        $this->email = $l['email'];
        $this->celular = Formatos::telefoneApp($l['celular']);
        $this->whatsAppValidado = boolval($l['whatsapp_validado']);
        $this->forcarAssinatura = boolval($l['forcar_assinatura']);
        if (strlen($l['cpf_cnpj'] ?? '') == 11) {
            $this->cpfCnpj = Formatos::cpfApp($l['cpf_cnpj']);
        } elseif (strlen($l['cpf_cnpj'] ?? '') == 14) {
            $this->cpfCnpj = Formatos::cnpjApp($l['cpf_cnpj']);
        }
        $this->perfil = $l['perfil'];
        $this->status = $l['status'];
        $this->apelido = $l['apelido'];
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function salva(): void
    {
        if (!$this->codConta) {
            throw new Exception('Código da conta obrigatório.');
        }
        if (!$this->nome) {
            throw new Exception('Nome obrigatório.');
        }
        if (!$this->email) {
            throw new Exception('E-mail obrigatório.');
        }
        if (!$this->senha) {
            if (!$this->codigo) {
                throw new Exception('Senha obrigatória na criação do usuário');
            }
            $this->senha = null;
        }
        if ($this->senha) {
            $this->senha = password_hash($this->senha, PASSWORD_DEFAULT);
        }
        if (!$this->perfil) {
            throw new Exception('Perfil obrigatório.');
        }
        if (!$this->status) {
            throw new Exception('Status obrigatório.');
        }
        if (!$this->apelido) {
            $this->apelido = $this->nome;
        }
        if (strlen($this->cpfCnpj ?? '') == 14) {
            $this->cpfCnpj = Formatos::cpfBd($this->cpfCnpj);
        } elseif (strlen($this->cpfCnpj ?? '') == 18) {
            $this->cpfCnpj = Formatos::cnpjBd($this->cpfCnpj);
        } else {
            $this->cpfCnpj = null;
        }
        $this->alteracao = now()->format('Y-m-d H:i:s');
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere();
        }
        $this->indexa();
    }

    private function altera(): void
    {
        $c = My::con();
        $query = <<< ALTERA
                UPDATE usuario SET
                cod_conta = ?,
                nome = ?,
                email = ?,
                celular = ?,
                whatsapp_validado = ?,
                forcar_assinatura = ?,
                cpf_cnpj = ?,
                senha = COALESCE(?, senha),
                perfil = ?,
                status = ?,
                apelido = ?,
                alteracao = NOW()
                WHERE codigo = ?                
            ALTERA;
        $com = $c->prepare($query);
        $com->execute([
            $this->codConta,
            $this->nome,
            $this->email,
            Formatos::telefoneBd($this->celular),
            $this->whatsAppValidado ? 1 : 0,
            $this->forcarAssinatura ? 1 : 0,
            $this->cpfCnpj,
            $this->senha,
            $this->perfil,
            $this->status,
            $this->apelido,
            $this->codigo,
        ]);
    }

    private function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
                INSERT INTO usuario
                (
                 cod_conta, nome, email, celular, whatsapp_validado, forcar_assinatura,
                 cpf_cnpj, senha, perfil, status, apelido, indice, criacao, alteracao
                )
                VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, '', NOW(), NOW())                
            INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->codConta,
            $this->nome,
            $this->email,
            Formatos::telefoneBd($this->celular),
            $this->whatsAppValidado ? 1 : 0,
            $this->forcarAssinatura ? 1 : 0,
            $this->cpfCnpj,
            $this->senha,
            $this->perfil,
            $this->status,
            $this->apelido,
        ]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     */
    public function indexa(): void
    {
        $c = My::con();
        $indice = implode(' ', [
            $this->nome,
            $this->email,
            $this->apelido,
            Formatos::telefoneBd($this->celular),
            $this->cpfCnpj,
        ]);
        $com = $c->prepare("UPDATE usuario SET indice = ? WHERE codigo = ?");
        $com->execute([$indice, $this->codigo]);
    }

    public function nomeReal(): ?string
    {
        if ($this->email == "$this->nome@$this->nome") {
            return null;
        }
        return $this->nome;
    }

    /**
     * @return string|null
     */
    public function emailReal(): ?string
    {
        [$nome, $dominio] = explode('@', $this->email);
        return $nome != $dominio;
    }

    /**
     * @throws Exception
     */
    public function exclui(): void
    {
        $c = My::con();
        $c->query("DELETE FROM usuario WHERE codigo = $this->codigo");
        $this->codigo = 0;
    }

    /**
     * @return bool
     */
    public function isAtivo(): bool
    {
        return $this->status == self::STATUS_ATIVO;
    }

    /**
     * @return bool
     */
    public function isProvisorio(): bool
    {
        return $this->status == self::STATUS_PROVISORIO;
    }

    /**
     * @return bool
     */
    public function isIncompleto(): bool
    {
        return !$this->cpfCnpj || !$this->celular;
    }

    /**
     * @param string $celular
     * @param string $cpfCnpj
     * @return void
     * @throws Exception
     */
    public function completa(string $celular, string $cpfCnpj): void
    {
        $c = My::con();
        $this->celular = Formatos::telefoneBd($celular);
        $this->cpfCnpj = Formatos::cpfBd($cpfCnpj);
        $query = <<< COMPLETA
        update usuario set
        celular = ?,
        cpf_cnpj = ?
        where codigo = ?
        COMPLETA;
        $com = $c->prepare($query);
        $com->execute([$this->celular, $this->cpfCnpj, $this->codigo]);
    }

    /**
     * @param string $nome
     * @param string $email
     * @return bool
     */
    public static function semEmail(string $nome, string $email): bool
    {
        return $email == "$nome@$nome";
    }

    /**
     * @param bool $forca
     * @return void
     */
    public function forcaAssinatura(bool $forca): void
    {
        $this->forcarAssinatura = $forca;
        $f = $forca ? 1 : 0;
        $c = My::con();
        $c->query("UPDATE usuario SET forcar_assinatura = $f WHERE codigo = $this->codigo");
    }

    /**
     * @param bool $validado
     * @return void
     */
    public function marcaWhatsappValidado(bool $validado): void
    {
        $this->whatsAppValidado = $validado;
        $v = $validado ? 1 : 0;
        $c = My::con();
        $c->query("UPDATE usuario SET whatsapp_validado = $v WHERE codigo = $this->codigo");
    }

    /**
     * @param string|null $search
     * @param int|null $codConta
     * @param string|null $perfil
     * @param string|null $status
     * @param bool|null $whatAppValidado
     * @param int $pagina
     * @return array
     * @throws Exception
     */
    public static function find(
        ?string $search = null,
        ?int $codConta = null,
        ?string $perfil = null,
        ?string $status = null,
        ?bool $whatAppValidado = null,
        int $pagina = 0,
    ): array {
        $c = My::con();
        $conditions = [];
        $params = [];
        $where = '';
        if ($codConta) {
            $conditions[] = "u.cod_conta = ?";
            $params[] = $codConta;
        }
        if ($perfil) {
            $conditions[] = "u.perfil = ?";
            $params[] = $perfil;
        }
        if ($search) {
            $search = trim($search);
            if (preg_match("/^[0-9.\-\/]+$/", $search)) {
                $search = str_replace('.', '', $search);
            }
            $search = str_replace(['(', ')', '-', '/'], '', $search);
            $search = str_replace(['@'], ' ', $search);
            $search = Formatos::ft($search);
            $conditions[] = "MATCH(u.indice) AGAINST(? IN BOOLEAN MODE)";
            $params[] = $search;
        }
        if ($status) {
            $conditions[] = "u.status = ?";
            $params[] = $status;
        }
        if ($whatAppValidado) {
            $conditions[] = "u.whatsapp_validado = 1";
        }
        if ($conditions) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }
        $pageSize = self::PAGE_SIZE;
        $offset = $pagina * $pageSize;
        $query = <<< LOAD
            SELECT u.codigo, u.cod_conta, c.nome conta, u.nome, u.email, u.celular, u.cpf_cnpj, u.whatsapp_validado,
                   u.forcar_assinatura, u.perfil, u.status, u.apelido, u.criacao, u.alteracao
            FROM usuario u
            INNER JOIN conta c ON u.cod_conta = c.codigo
            $where
            ORDER BY criacao DESC
            LIMIT $pageSize OFFSET $offset
        LOAD;
        $com = $c->prepare($query);
        $com->execute($params);
        $r = $com->get_result();
        $usuarios = [];
        while ($l = $r->fetch_assoc()) {
            $l['since'] = DataHora::sinceDays(new DateTime($l['criacao']));
            $l['sem_email'] = self::semEmail($l['nome'], $l['email']);
            $usuarios[] = $l;
        }
        $queryCount = <<< COUNT
            SELECT COUNT(*) n
            FROM usuario u
            INNER JOIN conta c ON u.cod_conta = c.codigo
            $where
        COUNT;
        $comCount = $c->prepare($queryCount);
        $comCount->execute($params);
        $count = $comCount->get_result()->fetch_assoc()['n'];
        return [
            'count' => $count,
            'pages' => ceil($count / $pageSize),
            'first' => $offset + 1,
            'last' => $offset + count($usuarios),
            'data' => $usuarios,
        ];
    }

    /**
     * @param int|null $codConta
     * @param string|null $perfil
     * @param string|null $search
     * @param array $status
     * @return array
     */
    public static function load(?int $codConta, ?string $perfil, ?string $search, array $status): array
    {
        $c = My::con();
        $conditions = [];
        $params = [];
        $where = '';
        if ($codConta != null) {
            $conditions[] = "u.cod_conta = ?";
            $params[] = $codConta;
        }
        if ($perfil != null) {
            $conditions[] = "u.perfil = ?";
            $params[] = $perfil;
        }
        if ($search !== null) {
            $search = trim($search);
            if (preg_match("/^[0-9.\-\/]+$/", $search)) {
                $search = str_replace('.', '', $search);
            }
            $search = str_replace(['(', ')', '-', '/'], '', $search);
            $search = str_replace(['@'], ' ', $search);
            $search = Formatos::ft($search);
            $conditions[] = "MATCH(u.indice) AGAINST(? IN BOOLEAN MODE)";
            $params[] = $search;
        }
        if ($status) {
            $questions = implode(',', array_fill(0, count($status), '?'));
            $conditions[] = "u.status IN ($questions)";
            $params = array_merge($params, $status);
        }
        if ($params) {
            $where = "WHERE " . implode(" AND ", $conditions);
        }
        $query = <<< LOAD
            SELECT c.nome conta, u.codigo, u.nome, u.email, u.cpf_cnpj, 
                   u.celular, u.whatsapp_validado, u.perfil, u.status, u.criacao
            FROM usuario u
            INNER JOIN conta c ON u.cod_conta = c.codigo
            $where
            ORDER BY codigo DESC
        LOAD;
        $com = $c->prepare($query);
        $com->execute($params);
        $r = $com->get_result();
        $usuarios = [];
        while ($l = $r->fetch_assoc()) {
            $l['nome_status'] = $l['status'];
            $usuarios[] = $l;
        }
        return $usuarios;
    }

    /**
     * @param $email
     * @param $senha
     * @return void
     * @throws Exception
     */
    public static function alteraSenha($email, $senha): void
    {
        $usuario = self::porEmail($email);
        $usuario->senha = $senha;
        $usuario->salva();
    }

    /**
     * @param string|null $email
     * @return Usuario
     * @throws Exception
     */
    public static function porEmail(?string $email): Usuario
    {
        if (!$email) {
            return new Usuario(0);
        }
        $c = My::con();
        $com = $c->prepare("SELECT codigo FROM usuario WHERE email = ?");
        $com->execute([$email]);
        $l = $com->get_result()->fetch_assoc();
        return new Usuario($l['codigo'] ?? 0);
    }

    /**
     * @param string $celular
     * @return Usuario
     * @throws Exception
     */
    public static function porCelular(string $celular): Usuario
    {
        $c = My::con();
        $celular = Formatos::telefoneBd($celular);
        $com = $c->prepare("SELECT codigo from usuario where celular = ? order by codigo desc limit 1");
        $com->execute([$celular]);
        $l = $com->get_result()->fetch_assoc();
        return new Usuario($l['codigo'] ?? 0);
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function dominiosEmails(): array
    {
        $c = My::con();
        $query = <<< DOMINIOS_EMAILS
            select email
            from usuario
            where status = 'ativo'
            order by codigo desc        
        DOMINIOS_EMAILS;
        $r = $c->query($query);
        $m = [];
        while ($l = $r->fetch_assoc()) {
            [$nome, $dominio] = explode('@', $l['email']);
            if (!isset($m[$dominio])) {
                $m[$dominio] = [
                    'nomes' => [],
                    'count' => 0,
                ];
            }
            $m[$dominio]['nomes'][] = $nome;
            $m[$dominio]['count']++;
        }
        $dominios = [];
        foreach ($m as $k => $v) {
            $dominios[] = [
                'dominio' => $k,
                'count' => $v['count'],
                'nomes' => $v['nomes'],
            ];
        }
        usort($dominios, function ($a, $b) {
            return $b['count'] <=> $a['count'];
        });
        return $dominios;
    }
}