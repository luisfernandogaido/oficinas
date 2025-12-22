<?php

namespace modelo;

use bd\Formatos;
use bd\My;
use datahora\DataHora;
use DateTime;
use DateTimeZone;
use Exception;
use Throwable;

use function myToken;

class WhatsappValidacao
{
    public int $codigo;
    public int $codUsuario;
    public string $token;
    public bool $identificacao = false;
    public string $criacao;
    public ?string $validacao = null;
    public ?string $resposta = null;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if ($codigo) {
            $query = <<< CONSTROI
            select cod_usuario, token, identificacao, criacao, validacao, resposta
            from whatsapp_validacao
            where codigo = $codigo
            CONSTROI;
            $c = My::con();
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('validação de whatsapp não encontrada');
            }
            $this->codUsuario = $l['cod_usuario'];
            $this->token = $l['token'];
            $this->identificacao = $l['identificacao'] == 1;
            $this->criacao = $l['criacao'];
            $this->validacao = $l['validacao'];
        }
    }

    /**
     * @return void
     */
    public function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
        insert into whatsapp_validacao
            (cod_usuario, token, identificacao, criacao)
        VALUES (?, ?, ?, ?)
        INSERE;
        $com = $c->prepare($query);
        $com->execute([$this->codUsuario, $this->token, $this->identificacao ? 1 : 0, $this->criacao]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     */
    public function exclui(): void
    {
        $c = My::con();
        $c->query("delete from whatsapp_validacao WHERE codigo = $this->codigo");
    }

    /**
     * @return Usuario
     * @throws Exception
     */
    public function usuario(): Usuario
    {
        return new Usuario($this->codUsuario);
    }

    /**
     * @param string $numero
     * @param bool $validado
     * @param string $resposta
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function responde(string $numero, bool $validado, string $resposta, int $codUsuario): void
    {
        $usuarioDoTelefone = Usuario::porCelular($numero);
        if ($usuarioDoTelefone->codigo) {
            $codUsuario = $usuarioDoTelefone->codigo;
        }
        if ($validado && !$numero) {
            throw new Exception('para validar, é preciso informar o número.');
        }
        $c = My::con();
        $celular = Formatos::telefoneBd($numero);
        if ($validado) {
            if ($celular == null) {
                throw new Exception("Número de telefone $numero com formato inválido.");
            }
            $com = $c->prepare("update usuario set celular = ?, whatsapp_validado = ? where codigo = ?");
            $com->execute([$celular, 1, $codUsuario]);
        } else {
            $com = $c->prepare("update usuario set whatsapp_validado = ? where codigo = ?");
            $com->execute([0, $codUsuario]);
        }
        new Usuario($this->codUsuario)->indexa();
        $com2 = $c->prepare(
            "update whatsapp_validacao set cod_usuario = ?, validacao = now(), resposta = ? where codigo = ?"
        );
        $com2->execute([$codUsuario, $resposta, $this->codigo]);
        $this->resposta = $resposta;
    }

    /**
     * @param string $celular
     * @return array
     * @throws Exception
     */
    public static function usuariosCelular(string $celular): array
    {
        $celular = Formatos::telefoneBd($celular);
        $c = My::con();
        $query = <<< USUARIOS_CELULAR
            SELECT codigo, nome, email, cpf_cnpj, whatsapp_validado, status, criacao
            from usuario
            where celular = ?
            order by codigo desc
        USUARIOS_CELULAR;
        $com = $c->prepare($query);
        $com->execute([$celular]);
        $r = $com->get_result();
        $usuarios = [];
        while ($l = $r->fetch_assoc()) {
            $l['status_h'] = $l['status'];
            $tz = new DateTimeZone('America/Sao_Paulo');
            $l['since_criacao'] = DataHora::sinceShort(new DateTime($l['criacao'], $tz));
            $usuarios[] = $l;
        }
        return $usuarios;
    }

    /**
     * @param int $codUsuario
     * @param bool $identificacao
     * @return WhatsappValidacao
     * @throws Exception
     */
    public static function cria(int $codUsuario, bool $identificacao): WhatsappValidacao
    {
        $c = My::con();
        $query = <<< CRIA
            select codigo
            from whatsapp_validacao
            where cod_usuario = $codUsuario
              and validacao is null
            order by codigo desc
            limit 1
        CRIA;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if ($l) {
            return new WhatsappValidacao($l['codigo']);
        }
        $wv = new WhatsappValidacao(0);
        $wv->codUsuario = $codUsuario;
        $wv->token = myToken();
        $wv->identificacao = $identificacao;
        $wv->criacao = new DateTime('now', new DateTimeZone('America/Sao_Paulo'))->format('Y-m-d H:i:s');
        $wv->insere();
        return $wv;
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public static function revoga(int $codUsuario): void
    {
        $pendente = self::pendente($codUsuario);
        if (!$pendente) {
            return;
        }
        $pendente->exclui();
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function aValidar(): array
    {
        $c = My::con();
        $query = <<< A_VALIDAR
            select wv.codigo, wv.criacao criacao_token, wv.token, wv.cod_usuario, u.nome, u.email,
                   u.celular, u.whatsapp_validado, u.cpf_cnpj, u.status, u.criacao criacao_usuario
            from whatsapp_validacao wv
            inner join usuario u on wv.cod_usuario = u.codigo
            where wv.resposta is null
            order by wv.codigo desc
        A_VALIDAR;
        $r = $c->query($query);
        $aValidar = [];
        while ($l = $r->fetch_assoc()) {
            $l['token_since'] = DataHora::sinceShort(new DateTime($l['criacao_token']));
            $l['status_h'] = $l['status'];
            $aValidar[] = $l;
        }
        return $aValidar;
    }

    /**
     * @param int $codUsuario
     * @return bool
     * @throws Exception
     */
    public static function isPendente(int $codUsuario): bool
    {
        return self::pendente($codUsuario) !== null;
    }

    /**
     * @param int $codUsuario
     * @return WhatsappValidacao|null
     * @throws Exception
     */
    public static function pendente(int $codUsuario): ?WhatsappValidacao
    {
        $c = My::con();
        $query = <<< PENDENTE
            select codigo
            from whatsapp_validacao
            where cod_usuario = $codUsuario
              and validacao is null
            order by codigo desc
            limit 1
        PENDENTE;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if ($l !== null) {
            return new WhatsappValidacao($l['codigo']);
        }
        return null;
    }

    /**
     * @param string $token
     * @return WhatsappValidacao|null
     * @throws Exception
     */
    public static function byToken(string $token): ?WhatsappValidacao
    {
        $c = My::con();
        $com = $c->prepare("SELECT codigo FROM whatsapp_validacao WHERE token = ?");
        $com->execute([$token]);
        $r = $com->get_result();
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new WhatsappValidacao($l['codigo']);
    }

    /**
     * @param string $token
     * @param string $telefone
     * @return string
     * @throws Exception
     */
    public static function solicitaValidacao(string $token, string $telefone): string
    {
        try {
            $c = My::con();
            $wv = self::byToken($token);
            if (!$wv) {
                return "Código de validação não encontrado.";
            }
            $query = <<< CONSULTA_TELEFONE
            select codigo
            from usuario
            where celular = ?
            order by codigo desc
            limit 1
        CONSULTA_TELEFONE;
            $com = $c->prepare($query);
            $com->execute([$telefone]);
            $r = $com->get_result();
            $l = $r->fetch_assoc();
            $codUsuario = $wv->codUsuario;
            if (!$wv->identificacao) {
                if ($l && ($wv->codUsuario != $l['codigo'])) {
                    return "Este telefone já foi cadastrado e não poderá ser usado para validar sua conta.";
                }
            } elseif ($l) {
                $codUsuario = $l['codigo'];
            }
            $usuario = new Usuario($codUsuario);
            if ($usuario->whatsAppValidado) {
                $resposta = "Telefone validado. Usuário: " . $usuario->codigo;
            } else {
                $resposta = "Validado. Acesso liberado. Usuário: " . $usuario->codigo;
            }
            $wv->responde($telefone, true, $resposta, $codUsuario);
            return $resposta;
        } catch (Throwable $t) {
            return $t->getMessage();
        }
    }
}