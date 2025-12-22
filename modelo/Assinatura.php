<?php
namespace modelo;

use app\client\Grana;
use bd\Formatos;
use DateTime;
use Exception;
use bd\My;
use Sistema;
use function floatval;
use function in_array;
use function notifyMe;
use function now;
use function print_r;
use function str_contains;

class Assinatura
{
    const STATUS_PENDENTE = 'pendente';
    const STATUS_ATIVA = 'ativa';
    const STATUS_CANCELADA = 'cancelada';

    const VALORES = [
        '0 months 1 days' => 5,
        '0 months 7 days' => 6,
        '1 months 0 days' => 7,
    ];

    const ASAAS_DE_PARA = [
        //Cobrança aguardando pagamento
        'PENDING' => self::STATUS_PENDENTE,

        //Cobrança Confirmada (Somente para cartão de crédito)
        'CONFIRMED' => self::STATUS_ATIVA,

        //Cobrança Recebida
        'RECEIVED' => self::STATUS_ATIVA,

        //Cobrança Recebida em Dinheiro (não gera saldo)
        'RECEIVED_IN_CASH' => self::STATUS_ATIVA,

        //Cobrança Atrasada
        'OVERDUE' => self::STATUS_PENDENTE,

        //Estorno Solicitado
        'REFUND_REQUESTED' => self::STATUS_CANCELADA,

        //Estorno em processamento (liquidação já está agendada, cobrança será estornada após executar a liquidação)
        'REFUND_IN_PROGRESS' => self::STATUS_CANCELADA,

        //Cobrança Estornada
        'REFUNDED' => self::STATUS_CANCELADA,

        //Recebido chargeback
        'CHARGEBACK_REQUESTED' => self::STATUS_CANCELADA,

        //Em disputa de chargeback (caso sejam apresentados documentos para contestação)
        'CHARGEBACK_DISPUTE' => self::STATUS_CANCELADA,

        //Disputa vencida, aguardando repasse da adquirente
        'AWAITING_CHARGEBACK_REVERSAL' => self::STATUS_CANCELADA,

        //Em processo de negativação
        'DUNNING_REQUESTED' => self::STATUS_CANCELADA,

        //Recuperada
        'DUNNING_RECEIVED' => self::STATUS_CANCELADA,

        //Pagamento em análise
        'AWAITING_RISK_ANALYSIS' => self::STATUS_PENDENTE,
    ];

    public int $codigo;
    public ?int $codUsuario = null;
    public ?int $codConta = null;
    public string $nome;
    public string $ini;
    public string $fim;
    public float $valor;
    public string $status;
    public ?string $asaasId = null;
    public ?string $asaasInstallment = null;
    public ?string $asaasInvoiceUrl = null;
    public ?string $asaasStatus = null;
    public string $criacao;
    public ?string $pagamento = null;

    /**
     * @param int $codigo
     * @throws Exception
     */
    public function __construct(int $codigo)
    {
        $this->codigo = $codigo;
        if ($codigo) {
            $c = My::con();
            $query = <<< CONSTROI
                select cod_usuario, cod_conta, nome, ini, fim, valor, `status`, asaas_id,
                       asaas_installment, asaas_invoice_url, asaas_status, criacao, pagamento
                from assinatura
                where codigo = $codigo
            CONSTROI;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('assinatura não encontrada');
            }
            $this->codUsuario = $l['cod_usuario'];
            $this->codConta = $l['cod_conta'];
            $this->nome = $l['nome'];
            $this->ini = $l['ini'];
            $this->fim = $l['fim'];
            $this->valor = floatval($l['valor']);
            $this->status = $l['status'];
            $this->asaasId = $l['asaas_id'];
            $this->asaasInstallment = $l['asaas_installment'];
            $this->asaasInvoiceUrl = $l['asaas_invoice_url'];
            $this->asaasStatus = $l['asaas_status'];
            $this->criacao = $l['criacao'];
            $this->pagamento = $l['pagamento'];
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function cria(): void
    {
        $c = My::con();
        $query = <<< CRIA
        INSERT INTO assinatura
        (
         cod_usuario, cod_conta, nome, ini, fim, valor, `status`, asaas_id,
         asaas_installment, asaas_invoice_url, asaas_status, criacao
         )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
        CRIA;
        $com = $c->prepare($query);
        $com->execute([
            $this->codUsuario,
            $this->codConta,
            $this->nome,
            $this->ini,
            $this->fim,
            $this->valor,
            $this->status,
            $this->asaasId,
            $this->asaasInstallment,
            $this->asaasInvoiceUrl,
            $this->asaasStatus,
        ]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function cancela(): void
    {
        if ($this->status != self::STATUS_PENDENTE) {
            throw new Exception('Só é possível cancelar assinaturas pendentes');
        }
        if ($this->asaasId) {
            $grana = new Grana();
            $grana->asaasCobrancaExclui($this->asaasId);
        }
        $c = My::con();
        $c->query("update assinatura set `status` = 'cancelada' where codigo = $this->codigo");
    }

    /**
     * @return void
     * @throws Exception
     */
    public function atualizaStatus(): void
    {
        if ($this->asaasId == null) {
            return;
        }
        $asaasStatusOld = $this->asaasStatus;
        $statusOld = $this->status;
        $grana = new Grana();
        $cobranca = $grana->asaasCobranca($this->asaasId);
        $this->asaasStatus = $cobranca['status'];
        $this->status = self::ASAAS_DE_PARA[$cobranca['status']] ?? null;
        if ($this->status == null) {
            throw new Exception("atualiza status asaas: '{$cobranca['status']}' desconhecido");
        }
        if ($this->asaasStatus == $asaasStatusOld && $this->status == $statusOld) {
            return;
        }
        $pagamento = now()->format('Y-m-d H:i:s');
        if ($this->status != self::STATUS_ATIVA) {
            $pagamento = null;
        }
        $c = My::con();
        $query = <<< ATUALIZA_STATUS
            update assinatura set
            status = ?,
            asaas_status = ?,
            pagamento = ?
            where codigo = ?
        ATUALIZA_STATUS;
        $com = $c->prepare($query);
        $com->execute([$this->status, $this->asaasStatus, $pagamento, $this->codigo]);

        $data = [
            'cobranca' => $cobranca,
            'usuario' => new Usuario($this->codUsuario),
        ];
        $body = '<pre>' . print_r($data, true) . '</pre>';
        notifyMe('Assinatura concluída com sucesso', $body);
    }

    /**
     * @param int $codUsuario
     * @param int $months
     * @param int $days
     * @return Assinatura
     * @throws Exception
     */
    public static function getInstanceTrial(int $codUsuario, int $months, int $days): Assinatura
    {
        $assinatura = new Assinatura(0);
        $assinatura->codUsuario = $codUsuario;
        $assinatura->nome = 'Período de testes';
        $now = now();
        $assinatura->ini = $now->format('Y-m-d H:i:s');
        $assinatura->fim = $now->modify("$months months $days days")->format('Y-m-d H:i:s');
        $assinatura->valor = 0;
        $assinatura->status = Assinatura::STATUS_ATIVA;
        $assinatura->cria();
        return $assinatura;
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @param int $meses
     * @param int $dias
     * @param string $tipo
     * @param bool $now
     * @return Assinatura
     * @throws Exception
     */
    public static function assina(
        int $codUsuario,
        int $codConta,
        int $meses,
        int $dias,
        string $tipo,
        bool $now = false,
    ): Assinatura {
        $sistemaNome = Sistema::$nome;
        $tiposAutorizados = [Grana::TIPO_CREDIT_CARD, Grana::TIPO_PIX];
        if (!in_array($tipo, $tiposAutorizados)) {
            throw new Exception("assina: tipo $tipo não autorizado");
        }
        $assinatura = new Assinatura(0);
        $assinatura->codUsuario = $codUsuario;
        $assinatura->nome = "$sistemaNome {$meses}M{$dias}D";
        $ultimoDia = self::ultimoDia($codUsuario, $codConta);
        if ($ultimoDia && !$now) {
            $assinatura->ini = $ultimoDia;
        } else {
            $assinatura->ini = now()->format('Y-m-d H:i:s');
        }
        $ini = new DateTime($assinatura->ini);
        $assinatura->fim = $ini->modify("$meses months $dias days")->format('Y-m-d H:i:s');
        $assinatura->valor = self::VALORES["$meses months $dias days"] ?? 0;
        if ($assinatura->valor == 0) {
            throw new Exception("assina: valor não tabelado");
        }
        $assinatura->status = Assinatura::STATUS_PENDENTE;
        $grana = new Grana();
        $usuario = new Usuario($codUsuario);
        $cobrancas = $grana->asaasCriaCobranca(
            $usuario->nome,
            $usuario->cpfCnpj,
            $usuario->email,
            $usuario->celular,
            $tipo,
            1,
            $assinatura->valor,
            now()->format('Y-m-d'),
            $assinatura->nome,
        );
        $cobranca = $cobrancas[0] ?? null;
        if (!$cobranca) {
            throw new Exception('Erro ao gerar cobrança');
        }
        $assinatura->asaasId = $cobranca['id'];
        $assinatura->asaasInstallment = $cobranca['installment'];
        $assinatura->asaasInvoiceUrl = $cobranca['invoiceUrl'];
        $assinatura->asaasStatus = $cobranca['status'];
        $assinatura->cria();
        return $assinatura;
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @return Assinatura|null
     * @throws Exception
     */
    public static function vigente(int $codUsuario, int $codConta): ?Assinatura
    {
        $c = My::con();
        $query = <<< VIGENTE
            SELECT codigo
            FROM assinatura
            WHERE (cod_usuario = $codUsuario OR cod_conta = $codConta) AND
                  ini <= NOW() AND fim >= NOW() AND
                  status = 'ativa'
        VIGENTE;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Assinatura($l['codigo']);
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @return Assinatura|null
     * @throws Exception
     */
    public static function pendente(int $codUsuario, int $codConta): ?Assinatura
    {
        $c = My::con();
        $query = <<< VIGENTE
            SELECT codigo
            FROM assinatura
            WHERE (cod_usuario = $codUsuario OR cod_conta = $codConta) AND
                  ini <= NOW() AND fim >= NOW() AND
                  status = 'pendente'
        VIGENTE;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Assinatura($l['codigo']);
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @return Assinatura[]
     * @throws Exception
     */
    public static function historico(int $codUsuario, int $codConta): array
    {
        $c = My::con();
        $query = <<< HISTORICO
            select codigo, nome, ini, fim, valor, asaas_invoice_url, criacao, `status`
            from assinatura
            WHERE (cod_usuario = $codUsuario OR cod_conta = $codConta) AND
                  `status` = 'ativa' and
                  ini < now()
            order by ini desc, fim desc
        HISTORICO;
        $r = $c->query($query);
        $assinaturas = [];
        while ($l = $r->fetch_assoc()) {
            $l['ini_h'] = Formatos::dataApp($l['ini']);
            $l['fim_h'] = Formatos::dataApp($l['fim']);
            $l['valor_h'] = Formatos::moeda($l['valor']);
            $assinaturas[] = $l;
        }
        return $assinaturas;
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @return array
     * @throws Exception
     */
    public static function futuras(int $codUsuario, int $codConta): array
    {
        $c = My::con();
        $query = <<< HISTORICO
            select codigo, nome, ini, fim, valor, asaas_invoice_url, criacao, `status`
            from assinatura
            WHERE (cod_usuario = $codUsuario OR cod_conta = $codConta) AND
                  `status` = 'ativa' and
                  ini > now()
            order by ini, fim
        HISTORICO;
        $r = $c->query($query);
        $assinaturas = [];
        while ($l = $r->fetch_assoc()) {
            $l['ini_h'] = Formatos::dataApp($l['ini']);
            $l['fim_h'] = Formatos::dataApp($l['fim']);
            $l['valor_h'] = Formatos::moeda($l['valor']);
            $assinaturas[] = $l;
        }
        return $assinaturas;
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @return string|null
     * @throws Exception
     */
    public static function ultimoDia(int $codUsuario, int $codConta): ?string
    {
        $c = My::con();
        $query = <<< ULTIMO_DIA
            SELECT MAX(fim) ultimo_dia
            FROM assinatura
            WHERE (cod_usuario = $codUsuario OR cod_conta = $codConta) AND
                  `status` = 'ativa' AND
                  fim >= NOW()                  
        ULTIMO_DIA;
        $r = $c->query($query);
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return $l['ultimo_dia'];
    }

    /**
     * @param int $codUsuario
     * @param int $codConta
     * @param string $substr
     * @return bool
     * @throws Exception
     */
    public static function contem(int $codUsuario, int $codConta, string $substr): bool
    {
        $vigente = Assinatura::vigente($codUsuario, $codConta);
        if (!$vigente) {
            return false;
        }
        return str_contains($vigente->nome, $substr);
    }

    public static function vigentes(): array
    {
        $c = My::con();
        $query = <<< VIGENTE
            select a.codigo,
                   a.cod_usuario,
                   u.nome usuario,
                   u.email,
                   u.celular,
                   a.nome,
                   a.ini,
                   a.fim,
                   a.valor,
                   a.status,
                   a.asaas_id,
                   a.asaas_installment,
                   a.asaas_invoice_url,
                   a.asaas_status,
                   a.criacao,
                   a.pagamento
            from assinatura a
                 inner join usuario u on a.cod_usuario = u.codigo
            where a.status = 'ativa'
              and a.ini <= now()
              and fim >= now()
            order by a.codigo desc;
        VIGENTE;
        $r = $c->query($query);
        $vigentes = [];
        while ($l = $r->fetch_assoc()) {
            $vigentes[] = $l;
        }
        return $vigentes;
    }
}