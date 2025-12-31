<?php

namespace modelo;

use app\client\Storage;
use BadMethodCallException;
use bd\Formatos;
use bd\My;
use datahora\DataHora;
use DateMalformedStringException;
use DateTime;
use Exception;
use InvalidArgumentException;
use LogicException;
use math\Bytes;
use Redis;
use Sistema;
use UnderflowException;

use function array_filter;
use function array_map;
use function array_values;
use function explode;
use function implode;
use function min;
use function moeda;
use function number_format;
use function str_replace;
use function substr;
use function token;

class Os
{
    const int MAX_TOTAL_ORIGINAL_SIZE_PROBLEMA = 100 * 1024 * 1024;

    public int $codigo;
    public string $hash;
    public int $codWorkspace;
    public int $codCliente;
    public ?int $codVeiculo = null;
    public string $criacao;
    public string $alteracao;
    public OsStatus $status;
    public ?Problema $problema = null;
    public ?Quando $quando = null;
    public ?Frequencia $frequencia = null;
    public ?string $sintomas = null;
    public ?string $condicoes = null;
    public ?string $obsCliente = null;
    public ?MotivoRejeicao $motivoRejeicao = null;
    public ?int $km = null;
    public ?NivelTanque $nivelTanque = null;
    public ?string $agendamento = null;
    public ?string $previsaoEntrega = null;
    public float $itens = 0.0;
    public float $desconto = 0.0 {
        get => $this->desconto;
        set {
            if ($value > $this->itens) {
                throw new InvalidArgumentException('O desconto não pode ser maior que a soma de produtos e serviços');
            }
            $this->desconto = $value;
            $this->valor = $this->itens - $this->desconto;
        }
    }
    public string $descontoH {
        get {
            return number_format($this->desconto, 2, '.', '');
        }
    }
    public float $valor = 0.0;
    public string $valorH {
        get {
            return number_format($this->valor, 2, ',', '');
        }
    }
    public bool $orcamentoTravado {
        get {
            return match ($this->status) {
                OsStatus::SOLICITADA, OsStatus::ANALISE, OsStatus::AGENDADA => false,
                default => true,
            };
        }
    }
    public string $indice;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if (!$codigo) {
            return;
        }
        $c = My::con();
        $query = <<< CONSTROI
            SELECT hash,
                   cod_workspace,
                   cod_cliente,
                   cod_veiculo,
                   criacao,
                   alteracao,
                   status,
                   problema,
                   sintomas,
                   quando,
                   frequencia,
                   condicoes,
                   obs_cliente,
                   motivo_rejeicao,
                   km,
                   nivel_tanque,
                   agendamento,
                   previsao_entrega,
                   itens,
                   desconto,
                   valor,
                   indice
            from os
            where codigo = $this->codigo
        CONSTROI;
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception("Código de OS não encontrado");
        }
        $this->hash = $l['hash'];
        $this->codWorkspace = $l['cod_workspace'];
        $this->codCliente = $l['cod_cliente'];
        $this->codVeiculo = $l['cod_veiculo'];
        $this->criacao = $l['criacao'];
        $this->alteracao = $l['alteracao'];
        $this->status = OsStatus::from($l['status']);
        $this->problema = Problema::tryFrom($l['problema'] ?? '');
        $this->quando = Quando::tryFrom($l['quando'] ?? '');
        $this->frequencia = Frequencia::tryFrom($l['frequencia'] ?? '');
        $this->sintomas = $l['sintomas'];
        $this->condicoes = $l['condicoes'];
        $this->obsCliente = $l['obs_cliente'];
        $this->motivoRejeicao = MotivoRejeicao::tryFrom($l['motivo_rejeicao'] ?? '');
        $this->km = $l['km'];
        $this->nivelTanque = NivelTanque::tryFrom($l['nivel_tanque'] ?? 0);
        $this->agendamento = $l['agendamento'];
        $this->previsaoEntrega = $l['previsao_entrega'];
        $this->itens = $l['itens'];
        $this->desconto = $l['desconto'];
        $this->valor = $l['valor'];
        $this->indice = $l['indice'];
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function insere(int $codUsuario): void
    {
        $c = My::con();
        $query = <<< INSERE
            insert into os
                (hash, cod_workspace, cod_cliente, criacao, alteracao, status, indice)
            VALUES (?, ?, ?, ?, ?, ?, '')
        INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->hash,
            $this->codWorkspace,
            $this->codCliente,
            $this->criacao,
            $this->alteracao,
            $this->status->value
        ]);
        $this->codigo = $com->insert_id;
        $this->mudaStatus($this->status, $codUsuario);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function indexa(): void
    {
        $placa = null;
        $marca = null;
        $modelo = null;
        if ($this->codVeiculo) {
            $veiculo = new Veiculo($this->codVeiculo);
            $placa = $veiculo->placa;
            $marca = $veiculo->marca;
            $modelo = $veiculo->modelo;
        }
        $usuario = new Usuario($this->codCliente);
        $ws = new Workspace($this->codWorkspace);
        $telefone = Formatos::telefoneBd($usuario->celular);
        $termos = [
            $this->codigo,
            $ws->nome,
            $placa,
            $marca,
            $modelo,
            $usuario->nome,
            $telefone,
            substr($telefone ?? '', 2),
        ];
        $this->indice = implode(' ', $termos);
        $c = My::con();
        $c->execute_query("UPDATE os SET indice = ? where codigo = ?", [$this->indice, $this->codigo]);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function saveProblem(): void
    {
        $c = My::con();
        $query = <<< SAVE_STEP
            update os
            set cod_cliente = ?,
                cod_veiculo = ?,
                problema    = ?,
                sintomas    = ?,
                quando      = ?,
                frequencia  = ?,
                condicoes   = ?,
                obs_cliente = ?
            where codigo = $this->codigo
        SAVE_STEP;
        $c->execute_query($query, [
            $this->codCliente,
            $this->codVeiculo,
            $this->problema->value,
            $this->sintomas,
            $this->quando->value ?? null,
            $this->frequencia->value ?? null,
            $this->condicoes,
            $this->obsCliente,
        ]);
        $this->indexa();
    }

    /**
     * @return array
     * @throws Exception
     */
    public function filesProblema(): array
    {
        $storage = new Storage();
        return array_map(function ($file) {
            $file['size_h'] = new Bytes($file['size'])->formata();
            $file['original_size_h'] = new Bytes($file['compression']['originalSize'])->formata();
            return $file;
        }, $storage->list(reference: $this->hash));
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function fileProblemaExclui(string $id): void
    {
        $tem = false;
        $files = $this->filesProblema();
        foreach ($files as $file) {
            if ($file['id'] === $id) {
                $tem = true;
                break;
            }
        }
        if (!$tem) {
            throw new Exception('Arquivo de problema não pertence a esta OS');
        }
        new Storage()->fileDelete($id);
    }

    /**
     * @param int $codCliente
     * @return void
     * @throws Exception
     */
    public function moveTo(int $codCliente): void
    {
        $this->codCliente = $codCliente;
        $veiculoDessaOs = new Veiculo($this->codVeiculo);
        $veiculosDoUsuario = Veiculo::doProprietario($codCliente);
        $placaJaExistia = false;
        foreach ($veiculosDoUsuario as $v) {
            if ($v->placa == $veiculoDessaOs->placa) {
                $this->codVeiculo = $v->codigo;
                $placaJaExistia = true;
                break;
            }
        }
        if (!$placaJaExistia) {
            $veiculoDessaOs->codProprietario = $codCliente;
            $veiculoDessaOs->salva();
        }
        $this->saveProblem();
    }

    /**
     * @param OsStatus $new
     * @param int $codUsuario
     * @param string|null $descricao
     * @param array|null $snapshot
     * @return void
     * @throws Exception
     */
    public function mudaStatus(OsStatus $new, int $codUsuario, ?string $descricao = null, ?array $snapshot = null): void
    {
        $old = $this->status;
        $c = My::con();
        $query = "UPDATE os SET status = ? WHERE codigo = ?";
        $c->execute_query($query, [$new->value, $this->codigo]);
        OsHistorico::insere(
            $this->codigo,
            $codUsuario,
            OsHistoricoCategoria::MUDANCA_STATUS,
            Visibilidade::PUBLICO,
            $old,
            $new,
            $descricao,
            $snapshot,
        );
    }

    /**
     * @param bool $master
     * @return array
     * @throws DateMalformedStringException
     */
    public function historico(bool $master): array
    {
        if ($master) {
            return OsHistorico::load($this->codigo);
        }
        return array_values(array_filter(OsHistorico::load($this->codigo), function ($v) {
            return $v['status'] != OsStatus::PENDENTE_MODERACAO;
        }));
    }

    /**
     * @param int $codUsuario
     * @param MotivoRejeicao $motivo
     * @return void
     * @throws Exception
     */
    public function rejeita(int $codUsuario, MotivoRejeicao $motivo): void
    {
        if (
            $this->status != OsStatus::SOLICITADA &&
            $this->status != OsStatus::ANALISE
        ) {
            throw new LogicException("Não é possível rejeitar OS com status {$this->status->value}");
        }
        $c = My::con();
        $c->execute_query("UPDATE os SET motivo_rejeicao = ? WHERE codigo = ?", [$motivo->value, $this->codigo]);
        $this->mudaStatus(OsStatus::REJEITADA, $codUsuario);
        $this->motivoRejeicao = $motivo;
    }

    /**
     * @param int $codUsuario
     * @param int $km
     * @param NivelTanque $nivelTanque
     * @return void
     * @throws Exception
     */
    public function daEntrada(int $codUsuario, int $km, NivelTanque $nivelTanque): void
    {
        if ($this->status != OsStatus::SOLICITADA && $this->status != OsStatus::AGENDADA) {
            throw new LogicException('Só é possível dar entrada em OS solicitadas ou agendadas.');
        }
        $c = My::con();
        $c->execute_query("UPDATE os SET km = ?, nivel_tanque = ? WHERE codigo = ?", [
            $km,
            $this->nivelTanque->value,
            $this->codigo,
        ]);
        $this->mudaStatus(OsStatus::ANALISE, $codUsuario);
        $this->km = $km;
        $this->nivelTanque = $nivelTanque;
        $veiculo = new Veiculo($this->codVeiculo);
        $veiculo->km = $km;
        $veiculo->salva();
    }

    /**
     * @param int $codUsuario
     * @param string $agendamento
     * @return void
     * @throws Exception
     */
    public function agenda(int $codUsuario, string $agendamento): void
    {
        if ($this->status != OsStatus::SOLICITADA && $this->status != OsStatus::AGENDADA) {
            throw new LogicException('Só é possível agendar OS solicitadas.');
        }
        $c = My::con();
        $c->execute_query("UPDATE os SET agendamento = ? WHERE codigo = ?", [
            $agendamento,
            $this->codigo,
        ]);
        $this->mudaStatus(OsStatus::AGENDADA, $codUsuario);
        $this->agendamento = $agendamento;
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function cancelaAgendamento(int $codUsuario): void
    {
        if ($this->status != OsStatus::AGENDADA) {
            throw new LogicException('Só é possível cancelar OS agendadas.');
        }
        $c = My::con();
        $c->execute_query("UPDATE os SET agendamento = null WHERE codigo = ?", [
            $this->codigo,
        ]);
        $this->mudaStatus(OsStatus::SOLICITADA, $codUsuario);
        $this->agendamento = null;
    }

    /**
     * @param string|null $previsao
     * @return void
     */
    public function estimaPrevisaoEntrega(?string $previsao): void
    {
        if ($this->status != OsStatus::EM_ANDAMENTO) {
            throw new BadMethodCallException('não é possível estimar entrega com os que não está em andamento.');
        }
        $c = My::con();
        $c->execute_query("UPDATE os SET previsao_entrega = ? WHERE codigo = ?", [$previsao, $this->codigo]);
        $this->previsaoEntrega = $previsao;
    }

    /**
     * @return OsItem[]
     * @throws DateMalformedStringException
     */
    public function itens(): array
    {
        $c = My::con();
        $query = <<< SQL
            select codigo,
                   cod_os,
                   cod_executante,
                   cod_produto,
                   cod_servico,
                   tipo,
                   nome,
                   categoria,
                   unidade_medida,
                   quantidade,
                   preco,
                   custo,
                   desconto,
                   subtotal,
                   ordem,
                   criacao
            from os_itens
            where cod_os = $this->codigo
            order by ordem, codigo
        SQL;
        $r = $c->query($query);
        $itens = [];
        while ($l = $r->fetch_assoc()) {
            $item = new OsItem(0);
            $item->codigo = $l['codigo'];
            $item->codOs = $l['cod_os'];
            $item->codExecutante = $l['cod_executante'];
            $item->codProduto = $l['cod_produto'];
            $item->codServico = $l['cod_servico'];
            $item->tipo = OsItemTipo::from($l['tipo']);
            $item->nome = $l['nome'];
            $item->categoria = $l['categoria'];
            $item->unidadeMedida = UnidadeMedida::tryFrom($l['unidade_medida'] ?? '');
            $item->quantidade = $l['quantidade'];
            $item->preco = $l['preco'];
            $item->custo = $l['custo'];
            $item->desconto = $l['desconto'];
            $item->subtotal = $l['subtotal'];
            $item->ordem = $l['ordem'];
            $item->criacao = new DateTime($l['criacao']);
            $itens[] = $item;
        }
        return $itens;
    }

    /**
     * @param int $codigo
     * @return void
     * @throws DateMalformedStringException
     */
    public function removeItem(int $codigo): void
    {
        if ($this->orcamentoTravado) {
            throw new BadMethodCallException('não é possível remover os itens de orçamento travado');
        }
        $item = new OsItem($codigo);
        $produtoServico = OsItem::findUnificado(
            $this->codWorkspace,
            $item->tipo,
            $item->codProduto ?? $item->codServico,
        );
        $produtoServico->contadorUso--;
        $produtoServico->save();
        $c = My::con();
        $c->query("delete from os_itens where codigo = $codigo and cod_os = $this->codigo");
        $this->atualizaValor();
    }

    /**
     * @param OsItemTipo $tipo
     * @param int $codigo
     * @return void
     */
    public function removeItemByType(OsItemTipo $tipo, int $codigo): void
    {
        $c = My::con();
        $query = "delete from os_itens where cod_os = $this->codigo and cod_produto = $codigo";
        if ($tipo == OsItemTipo::SERVICO) {
            $query = "delete from os_itens where cod_os = $this->codigo and cod_servico = $codigo";
        }
        $c->query($query);
    }

    /**
     * @param int $codigo
     * @param float|null $quantidade
     * @param float|null $preco
     * @return void
     * @throws DateMalformedStringException
     */
    public function alteraItem(int $codigo, ?float $quantidade, ?float $preco): void
    {
        if ($this->orcamentoTravado) {
            throw new BadMethodCallException('não é permitida a alteração de itens com o orçamento travado');
        }
        $subtotal = $quantidade * $preco;
        $c = My::con();
        $c->execute_query(
            "update os_itens set quantidade = ?, preco = ?, subtotal = ? where codigo = ? and cod_os = ?",
            [$quantidade, $preco, $subtotal, $codigo, $this->codigo]
        );
        $this->atualizaValor();
        if (!$preco) {
            return;
        }
        $item = new OsItem($codigo);
        $produServico = OsItem::findUnificado($this->codWorkspace, $item->tipo, $item->codProduto ?? $item->codServico);
        if ($produServico->preco > $preco) {
            return;
        }
        $produServico->interno = true;
        $produServico->preco = $preco;
        $produServico->save();
    }

    /**
     * @return void
     * @throws DateMalformedStringException
     */
    public function atualizaValor(): void
    {
        $this->itens = 0;
        foreach ($this->itens() as $item) {
            $this->itens += $item->subtotal;
        }
        $this->desconto = min($this->desconto, $this->itens);
        $this->valor = $this->itens - $this->desconto;
        $c = My::con();
        $c->execute_query(
            "UPDATE os SET itens = ?, desconto = ?, valor = ? where codigo = ?",
            [$this->itens, $this->desconto, $this->valor, $this->codigo]
        );
    }

    /**
     * @return void
     */
    public function atualizaDesconto(): void
    {
        if ($this->orcamentoTravado) {
            throw new BadMethodCallException('impossível atualizar desconto com a OS travada');
        }
        $c = My::con();
        $c->execute_query(
            "UPDATE os SET desconto = ?, valor = ? where codigo = ?",
            [$this->desconto, $this->valor, $this->codigo]
        );
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function cancela(int $codUsuario): void
    {
        if ($this->codCliente != $codUsuario) {
            throw new Exception('Sem permissão para cancelar OS na posição do cliente.');
        }
        if ($this->status != OsStatus::SOLICITADA && $this->status != OsStatus::AGENDADA) {
            throw new LogicException('Só é possível cancelar OS solicitadas e agendadas.');
        }
        $this->mudaStatus(OsStatus::CANCELADA, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function aguardaAprovacao(int $codUsuario): void
    {
        if ($this->valor == 0) {
            throw new UnderflowException('valor do orçamento deve ser maior que 0 para aprovação');
        }
        if ($this->status != OsStatus::ANALISE && $this->status != OsStatus::AGUARDANDO_APROVACAO) {
            throw new LogicException('Só é possível aguardar aprovação de OS em análise.');
        }
        if ($this->status == OsStatus::AGUARDANDO_APROVACAO) {
            return;
        }
        $this->mudaStatus(OsStatus::AGUARDANDO_APROVACAO, $codUsuario, null, $this->orcamentoSnapshot());
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function aprova(int $codUsuario): void
    {
        if ($this->status != OsStatus::AGUARDANDO_APROVACAO) {
            throw new LogicException('Só é possível aprovar OS que estão aguardando aprovação.');
        }
        $this->mudaStatus(OsStatus::EM_ANDAMENTO, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function voltaAnalise(int $codUsuario): void
    {
        if ($this->status != OsStatus::AGUARDANDO_APROVACAO && $this->status != OsStatus::EM_ANDAMENTO) {
            throw new LogicException('Só é possível voltar para análise OS que estão aguardando aprovação.');
        }
        $this->mudaStatus(OsStatus::ANALISE, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function finaliza(int $codUsuario): void
    {
        if ($this->status == OsStatus::FINALIZADA) {
            return;
        }
        if ($this->status != OsStatus::EM_ANDAMENTO) {
            throw new LogicException('Só é possível finalizar OS em andamento.');
        }
        $this->mudaStatus(OsStatus::FINALIZADA, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function conclui(int $codUsuario): void
    {
        if ($this->status != OsStatus::FINALIZADA) {
            throw new Exception('só é possível concluir uma OS finalizada');
        }
        $this->mudaStatus(OsStatus::CONCLUIDA, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function reabre(int $codUsuario): void
    {
        if ($this->status != OsStatus::FINALIZADA) {
            throw new BadMethodCallException('não é possível reabrir OS que não esteja finalizada');
        }
        $this->mudaStatus(OsStatus::EM_ANDAMENTO, $codUsuario);
    }

    /**
     * @return array
     * @throws DateMalformedStringException
     */
    public function orcamentoSnapshot(): array
    {
        $produtos = [];
        $servicos = [];
        $totalProdutos = 0;
        $totalServicos = 0;
        foreach ($this->itens() as $item) {
            if (!$item->subtotal) {
                continue;
            }
            if ($item->tipo == OsItemTipo::PRODUTO) {
                $produtos[] = $item;
                $totalProdutos += $item->subtotal;
            } else {
                $servicos[] = $item;
                $totalServicos += $item->subtotal;
            }
        }
        return [
            'totalProdutos' => $totalProdutos,
            'totalServicos' => $totalServicos,
            'desconto' => $this->desconto,
            'valor' => $this->valor,
            'produtos' => $produtos,
            'servicos' => $servicos,
        ];
    }

    /**
     * @return void
     * @throws Exception
     */
    public function exclui(): void
    {
        $filesProblema = $this->filesProblema();
        $storage = new Storage();
        foreach ($filesProblema as $file) {
            $storage->fileDelete($file['id']);
        }
        $c = My::con();
        $c->query("DELETE FROM os WHERE codigo = $this->codigo");
        $this->codigo = 0;
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function aprovaPendencia(int $codUsuario): void
    {
        if (
            $this->status != OsStatus::PENDENTE_MODERACAO
        ) {
            throw new LogicException("Não é possível aprovar pendência OS com status {$this->status->value}");
        }
        $this->mudaStatus(OsStatus::SOLICITADA, $codUsuario);
    }

    /**
     * @param int $codUsuario
     * @return void
     * @throws Exception
     */
    public function bloqueiaPendencia(int $codUsuario): void
    {
        if (
            $this->status != OsStatus::PENDENTE_MODERACAO
        ) {
            throw new LogicException("Não é possível bloquear pendência OS com status {$this->status->value}");
        }
        $this->mudaStatus(OsStatus::BLOQUEADA, $codUsuario);
    }


    /**
     * @param string $ip
     * @return void
     * @throws Exception
     */
    public static function tryLimit(string $ip): void
    {
        $redis = new Redis();
        $key = Sistema::$app . ":os-rate-limit:" . $ip;
        $count = $redis->incr($key);
        if ($count == 1) {
            $redis->expire($key, 3600);
        }
        //2 por hora, só, e o chatão que se foda.
        if ($count > 200) {
            throw new Exception('Limite de solicitações de atendimento atingido. Tente mais tarde.');
        }
    }

    /**
     * @param string $wsHash
     * @param int $codCliente
     * @param int $codUsuario
     * @return Os
     * @throws Exception
     */
    public static function abre(string $wsHash, int $codCliente, int $codUsuario): Os
    {
        $ws = Workspace::porHash($wsHash);
        $os = new Os(0);
        $os->hash = token(32);
        $os->codWorkspace = $ws->codigo;
        $os->codCliente = $codCliente;
        $os->criacao = new DateTime()->format('Y-m-d H:i:s');
        $os->alteracao = $os->criacao;
        $os->status = OsStatus::RASCUNHO;
        $os->insere($codUsuario);
        return $os;
    }

    /**
     * @param string $wsHash
     * @param int $codCliente
     * @return Os
     * @throws Exception
     */
    public static function abreOuUsaAberta(string $wsHash, int $codCliente): Os
    {
        $c = My::con();
        $ws = Workspace::porHash($wsHash);
        $query = <<<ABRE_OU_USA_ABERTA
            select codigo
            from os
            where cod_workspace = $ws->codigo
              and cod_cliente = $codCliente
              and status = 'rascunho'
              ORDER BY codigo
              LIMIT 1
        ABRE_OU_USA_ABERTA;
        $codigo = $c->execute_query($query)->fetch_assoc()['codigo'] ?? null;
        if ($codigo) {
            return new Os($codigo);
        }
        return self::abre($wsHash, $codCliente, $codCliente);
    }

    /**
     * @param string $hash
     * @return Os
     * @throws Exception
     */
    public static function porHash(string $hash): Os
    {
        $c = My::con();
        $codigo = $c->execute_query("select codigo from os where hash = ?", [$hash])->fetch_assoc()['codigo'] ?? null;
        if ($codigo == null) {
            throw new Exception('OS não encontrada');
        }
        return new Os($codigo);
    }

    /**
     * @param int $codWorkspace
     * @param bool $historico
     * @param string $search
     * @param bool $master
     * @return array
     * @throws DateMalformedStringException
     */
    public static function load(int $codWorkspace, bool $historico, string $search, bool $master): array
    {
        $search = Formatos::ft($search);
        $statusOperacionais = [
            OsStatus::SOLICITADA,
            OsStatus::ANALISE,
            OsStatus::AGENDADA,
            OsStatus::AGUARDANDO_APROVACAO,
            OsStatus::EM_ANDAMENTO,
            OsStatus::FINALIZADA,
        ];
        $statusHistorico = [
            OsStatus::CONCLUIDA,
            OsStatus::CANCELADA,
            OsStatus::REJEITADA,
        ];
        $statusOperacionaisMaster = [
            OsStatus::RASCUNHO,
            OsStatus::PENDENTE_MODERACAO
        ];
        $statusHistoricoMaster = [
            OsStatus::BLOQUEADA,
        ];
        if ($master) {
            $statusOperacionais = [...$statusOperacionais, ...$statusOperacionaisMaster];
            $statusHistorico = [...$statusHistorico, ...$statusHistoricoMaster];
        }
        $status = $historico ? $statusHistorico : $statusOperacionais;
        $status = array_map(fn($s) => "'" . $s->value . "'", $status);
        $statusIn = implode(', ', $status);
        $c = My::con();
        if ($codWorkspace) {
            $where = ["o.cod_workspace = $codWorkspace"];
        } else {
            $where = [];
        }
        $where[] = "o.status in ($statusIn)";
        if ($search) {
            $search = $c->real_escape_string($search);
            $where[] = "MATCH(o.indice) AGAINST ('$search' IN BOOLEAN MODE)";
        }
        $where = implode(' AND ', $where);
        $query = <<< OPERACIONAIS
            select o.codigo,
                   v.placa,
                   v.marca,
                   v.modelo,
                   o.status,
                   u.nome    cliente,
                   o.hash,
                   o.valor,
                   (select criacao
                    from os_historico
                    where cod_os = o.codigo
                    order by codigo desc
                    limit 1) criacao_status,
                   w.nome workspace,
                   w.cod_criador
            
            from os o
                 inner join usuario u on o.cod_cliente = u.codigo
                 inner join veiculo v on o.cod_veiculo = v.codigo
                 inner join workspace w on o.cod_workspace = w.codigo                    
            where $where
            ORDER BY codigo desc
            limit 100
        OPERACIONAIS;
        $r = $c->query($query);
        $oss = [];
        while ($l = $r->fetch_assoc()) {
            $marca = str_replace(['VW - VolksWagen'], ['VW'], $l['marca']);
            $modelo = explode(' ', $l['modelo'])[0] ?? '';
            $l['marca_modelo'] = "$marca $modelo";
            $l['placa'] = Formatos::placaBd($l['placa']);
            $l['status'] = OsStatus::from($l['status']);
            $l['valor_h'] = moeda($l['valor']);
            $l['status_since'] = DataHora::sinceShort(new DateTime($l['criacao_status']));
            $oss[] = $l;
        }
        return $oss;
    }
}