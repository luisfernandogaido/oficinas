<?php

namespace modelo;

use bd\My;
use datahora\DataHora;
use DateMalformedStringException;
use DateTime;
use DomainException;
use Exception;

use function json_encode;

class OsHistorico
{
    public int $codigo;
    public int $codOs;
    public ?int $codUsuario;
    public OsHistoricoCategoria $categoria;
    public Visibilidade $visibilidade;
    public ?OsStatus $statusOld;
    public OsStatus $statusNew;
    public ?string $descricao;
    public ?array $snapshot;
    public string $criacao;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if ($this->codigo) {
            throw new DomainException('construtor não implementado');
        }
    }

    /**
     * @param int $codOs
     * @param int|null $codUsuario
     * @param OsHistoricoCategoria $categoria
     * @param Visibilidade $visibilidade
     * @param OsStatus|null $statusOld
     * @param OsStatus $statusNew
     * @param string|null $descricao
     * @param array|null $snapshot
     * @return OsHistorico
     * @throws Exception
     */
    public static function insere(
        int $codOs,
        ?int $codUsuario,
        OsHistoricoCategoria $categoria,
        Visibilidade $visibilidade,
        ?OsStatus $statusOld,
        OsStatus $statusNew,
        ?string $descricao,
        ?array $snapshot
    ): OsHistorico {
        $h = new OsHistorico(0);
        $h->codOs = $codOs;
        $h->codUsuario = $codUsuario;
        $h->categoria = $categoria;
        $h->visibilidade = $visibilidade;
        $h->statusOld = $statusOld;
        $h->statusNew = $statusNew;
        $h->descricao = $descricao;
        $h->snapshot = $snapshot;
        $h->criacao = new DateTime()->format('Y-m-d H:i:s');
        $c = My::con();
        $query = <<< SQL
            insert into os_historico
            (cod_os, cod_usuario, categoria, visibilidade, status_old, status_new, descricao, snapshot)
            values (?, ?, ?, ?, ?, ?, ?, ?)
        SQL;
        $c->execute_query($query, [
            $h->codOs,
            $h->codUsuario,
            $h->categoria->value,
            $h->visibilidade->value,
            $h->statusOld->value ?? null,
            $h->statusNew->value,
            $h->descricao,
            $h->snapshot ? json_encode($h->snapshot): null,
        ]);
        $h->codigo = $c->insert_id;
        return $h;
    }

    /**
     * @param int $codOs
     * @return array
     * @throws DateMalformedStringException
     */
    public static function load(int $codOs): array
    {
        $query = <<< SQL
            select h.codigo,
                   h.cod_usuario,
                   u.nome usuario,
                   h.categoria,
                   h.visibilidade,
                   h.status_new,
                   h.descricao,
                   h.criacao
            from os_historico h
                 inner join usuario u on h.cod_usuario = u.codigo
            where h.cod_os = $codOs
            order by h.codigo desc
        SQL;
        $c = My::con();
        $r = $c->query($query);
        $historico = [];
        while ($l = $r->fetch_assoc()) {
            $l['status'] = OsStatus::from($l['status_new']);
            $l['status_h'] = $l['status']->label();
            $l['data_h'] = DataHora::human($l['criacao']);
            $historico[] = $l;
        }
        return $historico;
    }

}