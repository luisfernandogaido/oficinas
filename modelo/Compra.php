<?php
namespace modelo;

use app\client\Grana;
use Exception;
use bd\My;
use function implode;
use function in_array;
use function notifyMe;
use function now;
use function print_r;

class Compra
{
    public int $codigo;
    public int $codUsuario;
    public string $referencia;
    public string $descricao;
    public float $preco;
    public int $parcelas;
    public float $valorParcelas;
    public string $criacao;
    public ?string $pagamento;
    public ?string $cancelamento;
    public ?string $detalhes;
    public string $asaasId;
    public string $asaasInstallment;
    public string $asaasInvoiceUrl;
    public string $asaasStatus;
    public string $asaasAllIds;

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
                select cod_usuario,
                       referencia,
                       descricao,
                       preco,
                       criacao,
                       pagamento,
                       cancelamento,
                       detalhes,
                       asaas_id,
                       asaas_installment,
                       asaas_invoice_url,
                       asaas_status,
                       asaas_all_ids
                from compra
                WHERE codigo = $codigo                
            CONSTROI;
            $r = $c->query($query);
            $l = $r->fetch_assoc();
            if (!$l) {
                throw new Exception('Compra não encontrada');
            }
            $this->codUsuario = $l['cod_usuario'];
            $this->referencia = $l['referencia'];
            $this->descricao = $l['descricao'];
            $this->preco = $l['preco'];
            $this->criacao = $l['criacao'];
            $this->pagamento = $l['pagamento'];
            $this->cancelamento = $l['cancelamento'];
            $this->detalhes = $l['detalhes'];
            $this->asaasId = $l['asaas_id'];
            $this->asaasInstallment = $l['asaas_installment'];
            $this->asaasInvoiceUrl = $l['asaas_invoice_url'];
            $this->asaasStatus = $l['asaas_status'];
            $this->asaasAllIds = $l['asaas_all_ids'];
        }
    }

    /**
     * @return void
     */
    public function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
            insert into compra
            (cod_usuario, referencia, descricao, preco, parcelas, valor_parcelas, criacao,
             detalhes, asaas_id, asaas_installment, asaas_invoice_url, asaas_status, asaas_all_ids)
            values (?, ?, ?, ?, ?, ?, now(), ?, ?, ?, ?, ?, ?)
        INSERE;
        $com = $c->prepare($query);
        $com->execute([
            $this->codUsuario,
            $this->referencia,
            $this->descricao,
            $this->preco,
            $this->parcelas,
            $this->valorParcelas,
            $this->detalhes,
            $this->asaasId,
            $this->asaasInstallment,
            $this->asaasInvoiceUrl,
            $this->asaasStatus,
            $this->asaasAllIds,
        ]);
        $this->codigo = $com->insert_id;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function atualizaStatus(): void
    {
        $now = now()->format('Y-m-d H:i:s');
        $c = My::con();
        $grana = new Grana();
        $cobranca = $grana->asaasCobranca($this->asaasId);
        $status = $cobranca['status'];
        if ($cobranca['deleted']) {
            $status = 'DELETED';
        }
        if ($this->asaasStatus != $status) {
            $this->asaasStatus = $status;
            if ($status == 'CONFIRMED' || $status == 'RECEIVED') {
                $this->pagamento = $now;
            } elseif ($status == 'DELETED') {
                $this->cancelamento = $now;
            }
            $query = <<< ATUALIZA_STATUS
                update compra set
                pagamento = ?,
                cancelamento = ?,
                asaas_status = ?
                where codigo = ?                
            ATUALIZA_STATUS;
            $com = $c->prepare($query);
            $com->execute([
                $this->pagamento,
                $this->cancelamento,
                $this->asaasStatus,
                $this->codigo
            ]);
            $data = [
                'cobranca' => $cobranca,
                'usuario' => new Usuario($this->codUsuario),
            ];
            $body = '<pre>' . print_r($data, true) . '</pre>';
            notifyMe('Compra concluída com sucesso', $body);
        }
    }

    /**
     * @return void
     * @throws Exception
     */
    public function cancela(): void
    {
        $c = My::con();
        $grana = new Grana();
        $allIds = explode(',', $this->asaasAllIds);
        foreach ($allIds as $id) {
            $grana->asaasCobrancaExclui($id);
        }
        $this->asaasStatus = 'DELETED';
        $c->query("update compra set asaas_status = 'DELETED' where codigo = $this->codigo");
    }


    /**
     * @param int $codUsuario
     * @param string $referencia
     * @param string $descricao
     * @param int $parcelas
     * @param float $valorParcelas
     * @param string $tipo
     * @param string|null $detalhes
     * @return Compra
     * @throws Exception
     */
    public static function cria(
        int $codUsuario,
        string $referencia,
        string $descricao,
        int $parcelas,
        float $valorParcelas,
        string $tipo,
        ?string $detalhes
    ): Compra {
        $tiposAutorizados = [Grana::TIPO_CREDIT_CARD, Grana::TIPO_PIX];
        if (!in_array($tipo, $tiposAutorizados)) {
            throw new Exception("Tipo $tipo não autorizado.");
        }
        $usuario = new Usuario($codUsuario);
        if ($usuario->status == Usuario::STATUS_INATIVO) {
            throw new Exception('Compra não pode ser feita com usuário inativado.');
        }
        if ($usuario->status == Usuario::STATUS_PENDENTE) {
            throw new Exception('Usuário com cadastro pendente de validação.');
        }
        if ($usuario->status == Usuario::STATUS_PROVISORIO) {
            throw new Exception('Usuário precisa criar e validar cadastro.');
        }
        if ($usuario->isIncompleto()) {
            throw new Exception('Usuário completar cadastro.');
        }
        $grana = new Grana();
        $cobrancas = $grana->asaasCriaCobranca(
            $usuario->nome,
            $usuario->cpfCnpj,
            $usuario->email,
            $usuario->celular,
            $tipo,
            $parcelas,
            $valorParcelas,
            now()->format('Y-m-d'),
            $descricao,
        );
        $cobranca = $cobrancas[0] ?? null;
        if (!$cobranca) {
            throw new Exception('Erro ao gerar cobrança de compra.');
        }
        $allIds = [];
        foreach ($cobrancas as $cobranca) {
            $allIds[] = $cobranca['id'];
        }
        $compra = new Compra(0);
        $compra->codUsuario = $codUsuario;
        $compra->referencia = $referencia;
        $compra->descricao = $descricao;
        $compra->preco = $parcelas * $valorParcelas;
        $compra->parcelas = $parcelas;
        $compra->valorParcelas = $valorParcelas;
        $compra->criacao = now()->format('Y-m-d H:i:s');
        $compra->detalhes = $detalhes;
        $compra->asaasId = $cobranca['id'];
        $compra->asaasInstallment = $cobranca['installment'];
        $compra->asaasInvoiceUrl = $cobranca['invoiceUrl'];
        $compra->asaasStatus = $cobranca['status'];
        $compra->asaasAllIds = implode(',', $allIds);
        $compra->insere();
        return $compra;
    }

    /**
     * @param string $referencia
     * @return Compra|null
     * @throws Exception
     */
    public static function byReferencia(string $referencia): ?Compra
    {
        $c = My::con();
        $com = $c->prepare("select codigo from compra where referencia = ? AND asaas_status <> 'DELETED'");
        $com->execute([$referencia]);
        $r = $com->get_result();
        $l = $r->fetch_assoc();
        if (!$l) {
            return null;
        }
        return new Compra($l['codigo']);
    }
}