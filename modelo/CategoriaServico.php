<?php
namespace modelo;

use Exception;
use bd\My;

class CategoriaServico
{
    public int $codigo;
    public int $codWorkspace;
    public string $nome;
    public string $descricao;
    public bool $ativa = true;
    public string $criacao;

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
        $query = <<< CONSTROI
                select cod_workspace, nome, descricao, ativa, criacao
                from categorias_servicos
                where codigo = $codigo
            CONSTROI;
        $c = My::con();
        $l = $c->query($query)->fetch_assoc();
        if (!$l) {
            throw new Exception('Categoria de serviço não encontrada');
        }
        $this->codWorkspace = $l['cod_workspace'];
        $this->nome = $l['nome'];
        $this->descricao = $l['descricao'];
        $this->ativa = $l['ativa'] == 1;
        $this->criacao = $l['criacao'];
    }


    public function save(): void
    {
        if ($this->codigo) {
            $this->altera();
        } else {
            $this->insere();
        }
    }

    private function insere(): void
    {
        $c = My::con();
        $query = <<< INSERE
            insert into categorias_servicos
                (cod_workspace, nome, descricao, criacao)
            values (?, ?, ?, now())
        INSERE;
        $com = $c->prepare($query);
        $com->execute([$this->codWorkspace, $this->nome, $this->descricao]);
        $this->codigo = $com->insert_id;
    }

    private function altera(): void
    {
        $c = My::con();
    }

    /**
     * @param int $codWorkspace
     * @return void
     * @throws Exception
     */
    public static function generate(int $codWorkspace): void
    {
        foreach (self::TEMPLATE as $categoria) {
            $cs = new CategoriaServico(0);
            $cs->codWorkspace = $codWorkspace;
            $cs->nome = $categoria['categoria'];
            $cs->descricao = $categoria['descricao'];
            $cs->save();
        }
    }

    public static function list(int $codWorkspace): array
    {
        $query = <<< LISTA
            select codigo, nome, descricao, ativa, criacao
            from categorias_servicos
            where cod_workspace = $codWorkspace
            order by nome
        LISTA;
        $c = My::con();
        return $c->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    const array TEMPLATE = [
        [
            "categoria" => "Diagnóstico e Inspeção",
            "descricao" => "Avaliação geral do veículo para identificar falhas, ruídos ou mau funcionamento."
        ],
        [
            "categoria" => "Motor e Componentes Internos",
            "descricao" => "Reparo e manutenção de pistões, válvulas, cabeçote, virabrequim e outros componentes do motor."
        ],
        [
            "categoria" => "Transmissão e Embreagem",
            "descricao" => "Serviços de troca, reparo e ajuste de câmbio manual, automático e embreagem."
        ],
        [
            "categoria" => "Freios e Sistema de Frenagem",
            "descricao" => "Substituição de pastilhas, discos, fluido e reparo de sistemas hidráulicos de freio."
        ],
        [
            "categoria" => "Suspensão e Amortecedores",
            "descricao" => "Troca e manutenção de molas, amortecedores, buchas e componentes da suspensão."
        ],
        [
            "categoria" => "Direção e Alinhamento",
            "descricao" => "Correção de geometria da direção, alinhamento de rodas e ajustes de componentes de direção."
        ],
        [
            "categoria" => "Arrefecimento e Radiador",
            "descricao" => "Reparo e limpeza de radiador, bomba d’água e verificação do sistema de arrefecimento."
        ],
        [
            "categoria" => "Sistema de Escape e Catalisador",
            "descricao" => "Substituição ou reparo de escapamento, catalisador e silenciadores."
        ],
        [
            "categoria" => "Injeção Eletrônica e Alimentação",
            "descricao" => "Limpeza de bicos injetores, regulagem e diagnóstico de falhas na alimentação de combustível."
        ],
        [
            "categoria" => "Elétrica e Eletrônica Embarcada",
            "descricao" => "Reparo de sistemas elétricos, módulos, chicotes e sensores eletrônicos."
        ],
        [
            "categoria" => "Bateria e Sistema de Carga",
            "descricao" => "Troca de bateria, verificação de alternador e sistema de carga do veículo."
        ],
        [
            "categoria" => "Iluminação e Sinalização",
            "descricao" => "Substituição de faróis, lanternas, lâmpadas e verificação do sistema de iluminação."
        ],
        [
            "categoria" => "Ar-condicionado e Climatização",
            "descricao" => "Carga de gás, limpeza de dutos e manutenção do sistema de ar-condicionado automotivo."
        ],
        [
            "categoria" => "Troca de Óleo e Lubrificação",
            "descricao" => "Substituição de óleo do motor, câmbio e diferencial, além de lubrificação de componentes."
        ],
        [
            "categoria" => "Filtros e Manutenção Preventiva",
            "descricao" => "Troca de filtros de ar, combustível, óleo e cabine como parte da revisão preventiva."
        ],
        [
            "categoria" => "Correias, Correntes e Tensionadores",
            "descricao" => "Substituição e ajuste de correias dentadas, correntes de comando e tensionadores."
        ],
        [
            "categoria" => "Sistema de Combustível",
            "descricao" => "Limpeza e reparo de bomba de combustível, tanques e linhas de alimentação."
        ],
        [
            "categoria" => "Sistema de Ignição",
            "descricao" => "Substituição de velas, cabos, bobinas e ajuste de tempo de ignição."
        ],
        [
            "categoria" => "Rodas, Pneus e Balanceamento",
            "descricao" => "Montagem, troca de pneus, balanceamento e reparo de rodas."
        ],
        [
            "categoria" => "Alinhamento e Geometria",
            "descricao" => "Ajuste do alinhamento, cambagem e caster para garantir estabilidade e segurança."
        ],
        [
            "categoria" => "Lataria e Funilaria",
            "descricao" => "Reparo de amassados, corrosões e substituição de partes danificadas da carroceria."
        ],
        [
            "categoria" => "Pintura Automotiva",
            "descricao" => "Retoques, repintura completa e polimento para restauração da aparência do veículo."
        ],
        [
            "categoria" => "Estética e Higienização",
            "descricao" => "Limpeza interna e externa, cristalização, higienização de bancos e polimento técnico."
        ],
        [
            "categoria" => "Vidros e Retrovisores",
            "descricao" => "Substituição e reparo de vidros, retrovisores e mecanismos elétricos."
        ],
        [
            "categoria" => "Travas, Fechaduras e Vidros Elétricos",
            "descricao" => "Correção e manutenção de travas, vidros elétricos e sistemas de abertura."
        ],
        [
            "categoria" => "Airbag e Segurança Veicular",
            "descricao" => "Verificação e substituição de módulos de airbag e sensores de segurança."
        ],
        [
            "categoria" => "Computador de Bordo e Sensores",
            "descricao" => "Diagnóstico e substituição de sensores, módulos e sistemas eletrônicos de controle."
        ],
        [
            "categoria" => "Escape Esportivo e Preparações",
            "descricao" => "Instalação de escapamentos esportivos e modificações para desempenho."
        ],
        [
            "categoria" => "Performance e Reprogramação de ECU",
            "descricao" => "Ajuste eletrônico da central do motor para aumento de potência e eficiência."
        ],
        [
            "categoria" => "Inspeção Veicular e Vistorias",
            "descricao" => "Vistorias para regularização, venda ou transferência de veículos."
        ],
        [
            "categoria" => "Serviços de Guincho e Socorro",
            "descricao" => "Atendimento emergencial e transporte do veículo até a oficina."
        ],
        [
            "categoria" => "Instalação de Acessórios",
            "descricao" => "Instalação de alarmes, travas, sensores, películas e outros acessórios."
        ],
        [
            "categoria" => "Som, Multimídia e Alarmes",
            "descricao" => "Instalação e manutenção de sistemas de som, multimídia e alarmes veiculares."
        ],
        [
            "categoria" => "Revisão Periódica",
            "descricao" => "Manutenção completa conforme quilometragem e recomendações do fabricante."
        ],
        [
            "categoria" => "Serviços de Garantia",
            "descricao" => "Atendimento e manutenção de veículos dentro do período de garantia."
        ],
        [
            "categoria" => "Conversão GNV e Manutenção",
            "descricao" => "Instalação e manutenção de sistemas de gás natural veicular."
        ],
        [
            "categoria" => "Serviços de Retífica",
            "descricao" => "Usinagem e recuperação de motores, cabeçotes, virabrequins e cilindros."
        ],
        [
            "categoria" => "Suspensão a Ar e Preparações",
            "descricao" => "Instalação e manutenção de suspensões a ar e personalizações automotivas."
        ],
        [
            "categoria" => "Serviços de Solda e Estrutura",
            "descricao" => "Soldagem e reparos estruturais em chassi e carroceria."
        ],
        [
            "categoria" => "Chassi e Estrutura",
            "descricao" => "Correção de empenos e danos estruturais em chassis e longarinas."
        ],
        [
            "categoria" => "Serviços para Veículos Elétricos e Híbridos",
            "descricao" => "Manutenção de sistemas de alta tensão, baterias e componentes elétricos."
        ],
        [
            "categoria" => "Serviços Diesel",
            "descricao" => "Diagnóstico e manutenção de motores e sistemas de injeção a diesel."
        ],
        [
            "categoria" => "Serviços de Câmbio Automático",
            "descricao" => "Troca de fluido, limpeza e reparo de sistemas automáticos de transmissão."
        ],
        [
            "categoria" => "Serviços de Câmbio Manual",
            "descricao" => "Reparo e substituição de embreagem, rolamentos e sincronizadores."
        ],
        [
            "categoria" => "Diagnóstico Computadorizado",
            "descricao" => "Leitura de falhas e parâmetros eletrônicos com scanner automotivo."
        ],
        [
            "categoria" => "Manutenção Preventiva",
            "descricao" => "Serviços regulares para evitar falhas futuras e prolongar a vida útil do veículo."
        ],
        [
            "categoria" => "Manutenção Corretiva",
            "descricao" => "Reparo de falhas e substituição de peças já danificadas."
        ],
        [
            "categoria" => "Serviços Express (Rápidos)",
            "descricao" => "Pequenos serviços com tempo reduzido de execução, como troca de óleo e filtros."
        ],
        [
            "categoria" => "Avaliação e Orçamento",
            "descricao" => "Análise do veículo e elaboração de orçamento detalhado dos serviços necessários."
        ],
        [
            "categoria" => "Teste de Rodagem",
            "descricao" => "Condução do veículo para verificar o desempenho após serviços executados."
        ]
    ];
}