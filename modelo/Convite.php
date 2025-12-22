<?php
namespace modelo;

use bd\My;
use DateTime;
use DateTimeZone;
use Exception;
use function myHash;
use function now;

class Convite
{
    public int $codigo;
    public int $codUsuario;
    public string $cupom;
    public int $dias;
    public string $criacao;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if ($this->codigo) {
            $c = My::con();
            $query = <<< CONSTROI
            select cod_usuario, cupom, dias, criacao
            from convite
            where codigo = $this->codigo
            CONSTROI;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('convite não encontrado');
            }
            $this->codUsuario = $l['cod_usuario'];
            $this->cupom = $l['cupom'];
            $this->dias = $l['dias'];
            $this->criacao = $l['criacao'];
        }
    }

    public function codConta(): int
    {
        $c = My::con();
        $query = <<< COD_CONTA
            select u.cod_conta
            from convite c
            inner join usuario u on c.cod_usuario = u.codigo
            where c.codigo = $this->codigo
        COD_CONTA;
        return $c->query($query)->fetch_assoc()['cod_conta'];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function insere(): void
    {
        $c = My::con();
        $query = <<< QUERY
        INSERT INTO convite
            (cod_usuario, cupom, dias, criacao)
        values (?, ?, ?, now())
        QUERY;
        $com = $c->prepare($query);
        $com->execute([$this->codUsuario, $this->cupom, $this->dias]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function expirado(): bool
    {
        $usuario = new Usuario($this->codUsuario);
        if ($usuario->status == Usuario::STATUS_INATIVO) {
            return true;
        }
        $agora = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $criacao = new DateTime($this->criacao, new DateTimeZone('America/Sao_Paulo'));
        $dias = $agora->diff($criacao)->days;
        if ($dias > $this->dias) {
            return true;
        }
        return false;
    }

    /**
     * @param int $codUsuario
     * @param int $dias
     * @return Convite
     * @throws Exception
     */
    public static function generate(int $codUsuario, int $dias): Convite
    {
        $convite = new Convite(0);
        $convite->codUsuario = $codUsuario;
        $convite->cupom = myHash(6);
        $convite->dias = $dias;
        $convite->criacao = now()->format('Y-m-d H:i:s');
        $convite->insere();
        return $convite;
    }

    /**
     * @param string $cupom
     * @return Convite|null
     * @throws Exception
     */
    public static function byCupom(string $cupom): ?Convite
    {
        $c = My::con();
        $com = $c->prepare("select codigo from convite where cupom = ?");
        $com->execute([$cupom]);
        $r = $com->get_result();
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Convite($l['codigo']);
    }
}