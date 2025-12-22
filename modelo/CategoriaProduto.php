<?php
namespace modelo;

use Exception;
use bd\My;
use const MYSQLI_ASSOC;

class CategoriaProduto
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
                from categorias_produtos
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
            insert into categorias_produtos
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
            $cs = new CategoriaProduto(0);
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
            from categorias_produtos
            where cod_workspace = $codWorkspace
            order by nome
        LISTA;
        $c = My::con();
        return $c->query($query)->fetch_all(MYSQLI_ASSOC);
    }

    const array TEMPLATE = [
        [
            "categoria" => "Admissão de Ar e Filtros",
            "descricao" => "Filtros de ar, caixas de filtro, dutos de ar e coletores de admissão."
        ],
        [
            "categoria" => "Alternador e Geração",
            "descricao" => "Alternadores completos, reguladores de voltagem, estatores e rotores."
        ],
        [
            "categoria" => "Amortecedores",
            "descricao" => "Amortecedores, kits de batente, coifas e limitadores de curso."
        ],
        [
            "categoria" => "Ar Condicionado - Componentes",
            "descricao" => "Compressores, condensadores, evaporadores, válvulas de expansão e filtros de cabine."
        ],
        [
            "categoria" => "Arrefecimento - Bombas e Válvulas",
            "descricao" => "Bombas d'água, carcaças e válvulas termostáticas."
        ],
        [
            "categoria" => "Arrefecimento - Radiadores e Mangueiras",
            "descricao" => "Radiadores, reservatórios de expansão e todas as mangueiras do sistema."
        ],
        [
            "categoria" => "Baterias e Acumuladores",
            "descricao" => "Baterias automotivas e cabos de bateria."
        ],
        [
            "categoria" => "Carroceria - Acabamentos Externos",
            "descricao" => "Grades, frisos, emblemas, spoilers e acabamentos plásticos externos."
        ],
        [
            "categoria" => "Carroceria - Lataria",
            "descricao" => "Capôs, paralamas, portas e tampas de porta-malas."
        ],
        [
            "categoria" => "Correias e Polias",
            "descricao" => "Correias dentadas, correias de acessórios, tensionadores e polias guias."
        ],
        [
            "categoria" => "Coxins e Suportes",
            "descricao" => "Coxins de motor, câmbio e suportes de fixação de componentes."
        ],
        [
            "categoria" => "Cubos e Rolamentos de Roda",
            "descricao" => "Cubos de roda, rolamentos de roda e retentores."
        ],
        [
            "categoria" => "Discos e Tambores de Freio",
            "descricao" => "Discos de freio (ventilados/sólidos) e tambores de freio."
        ],
        [
            "categoria" => "Embreagem",
            "descricao" => "Kits de embreagem (platô, disco), rolamentos e atuadores hidráulicos."
        ],
        [
            "categoria" => "Escapamento e Catalisador",
            "descricao" => "Catalisadores, silenciosos, tubos de exaustão e coletores de escapamento."
        ],
        [
            "categoria" => "Faróis e Iluminação Externa",
            "descricao" => "Faróis, lanternas, luzes de neblina e lâmpadas de uso externo."
        ],
        [
            "categoria" => "Filtros de Óleo e Combustível",
            "descricao" => "Filtros de óleo de motor e filtros de combustível (linha e bomba)."
        ],
        [
            "categoria" => "Injeção - Bicos e Componentes",
            "descricao" => "Bicos injetores e válvulas de injeção (eletrônica e mecânica)."
        ],
        [
            "categoria" => "Injeção - Bombas de Combustível",
            "descricao" => "Bombas de combustível (elétricas e mecânicas) e flutuadores (sensores de nível)."
        ],
        [
            "categoria" => "Juntas e Vedadores",
            "descricao" => "Jogos de juntas de motor, juntas de cabeçote e retentores diversos."
        ],
        [
            "categoria" => "Limpadores e Palhetas",
            "descricao" => "Palhetas do limpador de para-brisa, motores e braços do limpador."
        ],
        [
            "categoria" => "Motor - Componentes Internos",
            "descricao" => "Pistões, anéis, bielas, virabrequim, tuchos e comandos de válvulas."
        ],
        [
            "categoria" => "Motor de Partida",
            "descricao" => "Motores de partida (arranque), solenoides e automáticos."
        ],
        [
            "categoria" => "Óleos e Fluidos",
            "descricao" => "Óleos de motor, fluidos de freio, fluidos de transmissão e aditivos."
        ],
        [
            "categoria" => "Pastilhas e Lonas de Freio",
            "descricao" => "Pastilhas de freio (dianteiras/traseiras) e lonas de freio."
        ],
        [
            "categoria" => "Pneus e Câmaras de Ar",
            "descricao" => "Pneus para veículos de passeio e utilitários, e câmaras de ar."
        ],
        [
            "categoria" => "Sensores Eletrônicos",
            "descricao" => "Sondas lambda, sensores de ABS, MAP/MAF, de rotação e temperatura."
        ],
        [
            "categoria" => "Sistema de Freios - Hidráulico",
            "descricao" => "Cilindros mestre, cilindros de roda, pinças de freio e servo-freios."
        ],
        [
            "categoria" => "Sistema de Direção",
            "descricao" => "Caixas de direção, bombas hidráulicas, terminais, braços axiais e pivôs."
        ],
        [
            "categoria" => "Transmissão - Componentes de Eixo",
            "descricao" => "Semi-eixos, juntas homocinéticas, trizetas e coifas."
        ]
    ];
}