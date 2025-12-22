<?php
namespace modelo;

use bd\My;
use Exception;
use function boolval;

class Conta
{
    public int $codigo = 0;
    public string $nome = '';
    public bool $ativa = true;
    public string $criacao;
    public string $alteracao;

    /**
     * @param $codigo
     * @throws Exception
     */
    public function __construct($codigo)
    {
        if (!$codigo) {
            return;
        }
        $c = My::con();
        $query = <<< QUERY
              SELECT nome, ativa, criacao, alteracao
              FROM conta
              WHERE codigo = $codigo
            QUERY;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if (!$l) {
            throw new Exception('Conta não cadastrada.');
        }
        $this->codigo = $codigo;
        $this->nome = $l['nome'];
        $this->ativa = boolval($l['ativa']);
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
    }

    /**
     * @return array
     * @throws Exception
     */
    public static function all(): array
    {
        $c = My::con();
        $r = $c->query("SELECT codigo, nome, ativa, criacao, alteracao FROM conta ORDER BY nome");
        $contas = [];
        while ($l = $r->fetch_assoc()) {
            $contas[] = $l;
        }
        return $contas;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function salva(): void
    {
        if (!$this->nome) {
            throw new Exception('Nome obrigatório.');
        }
        $c = My::con();
        $ativa = $this->ativa ? 1 : 0;
        if ($this->codigo) {
            $query = <<< ALTERA
                UPDATE conta SET 
                nome = ?,
                ativa = ?,
                alteracao = NOW()
                WHERE codigo = ?
            ALTERA;
            $com = $c->prepare($query);
            $com->bind_param('sii', $this->nome, $ativa, $this->codigo);
            $com->execute();
        } else {
            $query = <<< INSERE
                INSERT INTO conta
                (nome, ativa, criacao, alteracao)
                VALUES
                (?, ?, NOW(), NOW())
            INSERE;
            $com = $c->prepare($query);
            $com->bind_param('si', $this->nome, $ativa);
            $com->execute();
            $this->codigo = $com->insert_id;
        }
    }

    /**
     * @return void
     */
    public function exclui(): void
    {
        $c = My::con();
        $c->query("DELETE FROM conta WHERE codigo = $this->codigo");
        $this->codigo = 0;
    }
}
