<?php
namespace modelo;

use bd\Formatos;
use bd\My;
use Exception;
use function cli;
use function php_sapi_name;

class Tarefa
{
    private int $codigo;
    private int $codProjeto;
    private string $nome;
    private string $descricao;
    private bool $arquivada;
    private string $tempoTotal;
    private string $criacao;
    private string $indice;
    private array $cards = [];

    /**
     * Tarefa constructor.
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        if ($codigo) {
            $c = My::con();
            $query = <<< QUERY
                SELECT cod_projeto, nome, descricao, arquivada, tempo_total, criacao, indice
                FROM tarefa
                WHERE codigo = $codigo
            QUERY;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('tarefa não cadastrada');
            }
            $this->codProjeto = $l['cod_projeto'];
            $this->nome = $l['nome'];
            $this->descricao = $l['descricao'];
            $this->arquivada = $l['arquivada'];
            $this->tempoTotal = $l['tempo_total'];
            $this->criacao = $l['criacao'];
            $this->indice = $l['indice'];
            $query = <<< QUERY
                SELECT url
                FROM tarefa_card
                WHERE cod_tarefa = $codigo
                ORDER BY codigo            
            QUERY;
            $r = $c->query($query);
            while ($l = $r->fetch_assoc()) {
                $this->cards[] = $l['url'];
            }
        }
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodigo(): int
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo(int $codigo): void
    {
        $this->codigo = $codigo;
    }

    /**
     * @return int
     */
    public function getCodProjeto(): int
    {
        return $this->codProjeto;
    }

    /**
     * @param int $codProjeto
     */
    public function setCodProjeto(int $codProjeto): void
    {
        $this->codProjeto = $codProjeto;
    }

    /**
     * @return string
     */
    public function getNome(): string
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome(string $nome): void
    {
        $this->nome = $nome;
    }

    /**
     * @return string
     */
    public function getDescricao(): string
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao(string $descricao): void
    {
        $this->descricao = $descricao;
    }

    /**
     * @return bool
     */
    public function isArquivada(): bool
    {
        return $this->arquivada;
    }

    /**
     * @param bool $arquivada
     */
    public function setArquivada(bool $arquivada): void
    {
        $this->arquivada = $arquivada;
    }

    /**
     * @return int
     */
    public function getTempoTotal(): string
    {
        return $this->tempoTotal;
    }

    /**
     * @param int $tempoTotal
     */
    public function setTempoTotal(string $tempoTotal): void
    {
        $this->tempoTotal = $tempoTotal;
    }

    /**
     * @return string
     */
    public function getCriacao(): string
    {
        return $this->criacao;
    }

    /**
     * @param string $criacao
     */
    public function setCriacao(string $criacao): void
    {
        $this->criacao = $criacao;
    }

    /**
     * @return string
     */
    public function getIndice(): string
    {
        return $this->indice;
    }

    /**
     * @param string $indice
     */
    public function setIndice(string $indice): void
    {
        $this->indice = $indice;
    }

    /**
     * @return array
     */
    public function getCards(): array
    {
        return $this->cards;
    }

    /**
     * @param array $cards
     */
    public function setCards(array $cards): void
    {
        $this->cards = $cards;
    }

    /**
     * @throws Exception
     */
    public function salva()
    {
        $c = My::con();
        if ($this->codigo) {
            $query = <<< QUERY
                UPDATE tarefa SET
                cod_projeto = ?,
                nome = ?,
                descricao = ?
                WHERE codigo = ?            
            QUERY;
            $com = $c->prepare($query);
            $com->bind_param(
                'issi',
                $this->codProjeto,
                $this->nome,
                $this->descricao,
                $this->codigo
            );
            $com->execute();
            $c->query("DELETE FROM tarefa_card WHERE cod_tarefa = $this->codigo");
        } else {
            $query = <<< QUERY
                INSERT INTO tarefa
                (cod_projeto, nome, descricao, arquivada, criacao, indice)
                VALUES
                (?, ?, ?, 0, NOW(), ?)
            QUERY;
            $com = $c->prepare($query);
            $com->bind_param('isss', $this->codProjeto, $this->nome, $this->descricao, $this->nome);
            $com->execute();
            $this->codigo = $c->insert_id;
        }
        $com = $c->prepare('INSERT INTO tarefa_card (cod_tarefa, url) VALUES (?,?)');
        foreach ($this->cards as $card) {
            $com->bind_param('is', $this->codigo, $card);
            $com->execute();
        }
        $this->indexa();
    }

    /**
     * @throws Exception
     */
    public function exclui()
    {
        $c = My::con();
        $c->query("DELETE FROM tarefa WHERE codigo = $this->codigo");
        $this->codigo = 0;
    }

    /**
     * @throws Exception
     */
    public function start()
    {
        $this->desarquiva();
        $this->stop();
        $c = My::con();
        $query = <<< QUERY
            INSERT INTO tempo
            (cod_tarefa, ini, criacao)
            VALUES
            ($this->codigo,NOW(),NOW())
        QUERY;
        $c->query($query);
    }

    /**
     * @throws Exception
     */
    public function stop()
    {
        if (!$this->codigo) {
            return;
        }
        $projeto = new Projeto($this->codProjeto);
        $codUsuario = $projeto->getCodUsuario();
        $c = My::con();
        $query = <<< QUERY
            UPDATE tempo te            
            INNER JOIN tarefa ta ON te.cod_tarefa = ta.codigo
            INNER JOIN projeto p ON ta.cod_projeto = p.codigo
            SET te.fim = NOW()
            WHERE te.fim IS NULL AND
                        p.cod_usuario = $codUsuario        
        QUERY;
        $c->query($query);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function tempos(): array
    {
        $c = My::con();
        $r = $c->query("SELECT codigo, ini, fim, tempo, criacao FROM tempo WHERE cod_tarefa = $this->codigo");
        $tempos = [];
        while ($l = $r->fetch_assoc()) {
            $tempos[] = $l;
        }
        return $tempos;
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function isStarted(): bool
    {
        if (!$this->codigo) {
            return false;
        }
        $c = My::con();
        $query = <<< QUERY
            SELECT EXISTS(
                SELECT *
                FROM tempo
                WHERE   cod_tarefa = $this->codigo AND
                        ini IS NOT NULL AND fim IS NULL
            ) is_started        
        QUERY;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        return $l['is_started'] == 1;
    }

    /**
     * @throws Exception
     */
    public function arquiva()
    {
        if ($this->isStarted()) {
            throw new Exception('pare o cronômetro da tarefa antes de arquivá-la');
        }
        $this->tempoTotal = self::calcTempoTotal($this->codigo);
        $c = My::con();
        $query = <<< QUERY
            UPDATE tarefa SET
            arquivada = 1,
            tempo_total = ?
            WHERE codigo = ?            
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('si', $this->tempoTotal, $this->codigo);
        $com->execute();
    }

    /**
     * @throws Exception
     */
    public function desarquiva()
    {
        $c = My::con();
        $query = <<< QUERY
            UPDATE tarefa SET
            arquivada = 0,
            tempo_total = '00:00:00'
            WHERE codigo = ?            
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('i', $this->codigo);
        $com->execute();
    }

    public function indexa()
    {
        if (!$this->codigo) {
            return;
        }
        $projeto = new Projeto($this->codProjeto);
        $entradas = [
            $this->nome,
            $this->descricao,
            $projeto->getNome(),
        ];
        foreach ($this->cards as $card) {
            $entradas[] = $card;
        }
        $this->indice = implode(' ', $entradas);
        $c = My::con();
        $com = $c->prepare("UPDATE tarefa SET indice = ? WHERE codigo = ?");
        $com->bind_param('si', $this->indice, $this->codigo);
        $com->execute();
    }

    /**
     * @param int $codUsuario
     * @param int $arquivada
     * @param string|null $texto
     * @return array
     * @throws Exception
     */
    public static function lista(int $codUsuario, int $arquivada, ?string $texto): array
    {
        if (strpos($texto, 'https://trello.com/c/') === 0) {
            $texto = substr($texto, 21, 8);
        }
        $texto = Formatos::ft($texto);
        $c = My::con();
        $query = <<< QUERY
            select t.codigo, t.nome, t.descricao, t.arquivada, t.tempo_total, t.criacao, p.nome projeto,
            EXISTS(
                SELECT *
                FROM tempo
                WHERE cod_tarefa = t.codigo AND
                      ini IS NOT NULL AND 
                      fim IS NULL
            ) started
            from tarefa t
            inner join projeto p on t.cod_projeto = p.codigo
            where   p.cod_usuario = $codUsuario and
                    t.arquivada = $arquivada and
                    (
                        MATCH(indice) AGAINST(? IN BOOLEAN MODE) OR
                        ? IS NULL
                    )
            order by t.codigo desc
            limit 100
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('ss', $texto, $texto);
        $com->execute();
        $r = $com->get_result();
        $tarefas = [];
        while ($l = $r->fetch_assoc()) {
            if ($l['tempo_total'] == '00:00:00') {
                $l['tempo_total'] = self::calcTempoTotal($l['codigo']);
            }
            $tarefa = new Tarefa($l['codigo']);
            $l['cards'] = $tarefa->getCards();
            $tarefas[] = $l;
        }
        return $tarefas;
    }

    /**
     * @param int $codigo
     * @return string
     * @throws Exception
     */
    public static function calcTempoTotal(int $codigo): string
    {
        $c = My::con();
        $query = <<< QUERY
            SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(COALESCE(fim, NOW()), ini)))) tempo_total
            FROM tempo
            WHERE   cod_tarefa = $codigo AND
                    ini IS NOT NULL
        QUERY;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        return $l['tempo_total'] ?? '00:00:00';
    }

    /**
     * @param int $codUsuario
     * @param string $ini
     * @param string $fim
     * @return array
     * @throws Exception
     */
    public static function tempoGasto(int $codUsuario, string $ini, string $fim): array
    {
        $c = My::con();
//        $c = My::con('pro');
        $query = <<< QUERY
            SELECT	p.codigo cod_projeto, p.nome projeto,
            SEC_TO_TIME(
                SUM(
                    TIME_TO_SEC(
                        TIMEDIFF(
                            IF(
                                COALESCE(te.fim, NOW()) < CONVERT(?, DATETIME),
                                COALESCE(te.fim, NOW()),
                                CONVERT(?, DATETIME)
                            ),
                            IF(
                                te.ini > CONVERT(?, DATETIME),
                                te.ini,
                                CONVERT(?, DATETIME)
                            )
                        )
                    )
                )
            ) tempo            
            FROM tempo te
            INNER JOIN tarefa ta ON te.cod_tarefa = ta.codigo
            INNER JOIN projeto p ON ta.cod_projeto = p.codigo
            WHERE p.cod_usuario = ? and
                  (
                  te.ini >= CONVERT(?, DATETIME) AND te.ini <= CONVERT(?, DATETIME) OR
                  te.fim >= CONVERT(?, DATETIME) AND te.fim <= CONVERT(?, DATETIME)
                  )
            GROUP BY cod_projeto, projeto
            ORDER BY tempo DESC
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('ssssissss', $fim, $fim, $ini, $ini, $codUsuario, $ini, $fim, $ini, $fim);
        $com->execute();
        $r = $com->get_result();
        $projetos = [];
        while ($l = $r->fetch_assoc()) {
            $projetos[] = $l;
        }
        return $projetos;
    }

    public static function tempoGastoProjeto(int $codProjeto, string $ini, string $fim): array
    {
        if (php_sapi_name() != 'cli') {
            $c = My::con();
        } else {
            $c = My::con('pro');
        }
        $query = <<< QUERY
            SELECT ta.codigo cod_tarefa,
                   ta.nome tarefa,
                   SEC_TO_TIME(
                       SUM(
                           TIME_TO_SEC(
                               TIMEDIFF(
                                   IF(
                                       COALESCE(te.fim, NOW()) < CONVERT(?, DATETIME),
                                       COALESCE(te.fim, NOW()),
                                       CONVERT(?, DATETIME)
                                   ),
                                   IF(
                                       te.ini > CONVERT(?, DATETIME),
                                       te.ini,
                                       CONVERT(?, DATETIME)
                                   )
                                )
                           )
                       )
                   ) tempo,
            GROUP_CONCAT(tc.url) cards
            FROM tempo te
            INNER JOIN tarefa ta ON te.cod_tarefa = ta.codigo
            INNER JOIN projeto p ON ta.cod_projeto = p.codigo
            LEFT JOIN tarefa_card tc ON ta.codigo = tc.cod_tarefa
            WHERE p.codigo = ? AND (
            te.ini >= CONVERT(?, DATETIME) AND te.ini <= CONVERT(?, DATETIME) OR
                        te.fim >= CONVERT(?, DATETIME) AND te.fim <= CONVERT(?, DATETIME))
            GROUP BY cod_tarefa, tarefa
            ORDER BY tempo DESC
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('ssssissss', $fim, $fim, $ini, $ini, $codProjeto, $ini, $fim, $ini, $fim);
        $com->execute();
        $r = $com->get_result();
        $tarefas = [];
        while ($l = $r->fetch_assoc()) {
            $cards = array_unique(array_values(array_filter(explode(',', $l['cards'] ?? ''), function ($card) {
                return boolval($card);
            })));
            $l['cards'] = $cards ?: [];
            $tarefas[] = $l;
        }
        return $tarefas;
    }

    /**
     * @param string $url
     * @return Tarefa|null
     * @throws Exception
     */
    public static function byCard(string $url): ?Tarefa
    {
        $c = My::con();
        $query = <<< QUERY
            SELECT cod_tarefa 
            FROM tarefa_card
            WHERE url = ?
            ORDER BY codigo DESC
            LIMIT 1
        QUERY;
        $com = $c->prepare($query);
        $com->bind_param('s', $url);
        $com->execute();
        $r = $com->get_result();
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Tarefa($l['cod_tarefa']);
    }

}