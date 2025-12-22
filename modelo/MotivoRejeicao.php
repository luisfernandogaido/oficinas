<?php

namespace modelo;

enum MotivoRejeicao: string
{
    case AGENDA = 'agenda';
    case PATIO = 'patio';
    case MODELO = 'modelo';
    case SERVICO = 'servico';
    case PECAS = 'pecas';
    case AREA = 'area';
    case CLIENTE_DESISTIU = 'cliente_desistiu';
    case OUTROS = 'outros';

    public function label(): string
    {
        return match ($this) {
            self::AGENDA => 'Agenda cheia',
            self::PATIO => 'Pátio lotado',
            self::MODELO => 'Modelo não atendido',
            self::SERVICO => 'Serviço não oferecido',
            self::PECAS => 'Peças indisponíveis',
            self::AREA => 'Fora da área',
            self::CLIENTE_DESISTIU => 'Cliente desistiu',
            self::OUTROS => 'Outros motivos',
        };
    }

    public function mensagemCliente(): string
    {
        return match ($this) {
            self::AGENDA => 'No momento, estamos com a agenda 100% preenchida. ' .
                'Para manter nosso padrão de qualidade e prazo, ' .
                'infelizmente não conseguiremos encaixar seu veículo nesta semana.',
            self::PATIO => 'Nossa capacidade física atingiu o limite seguro. ' .
                'Para não deixar seu carro na rua ou exposto, precisamos pausar novas entradas momentaneamente.',
            self::MODELO => 'Nossa oficina é especializada em marcas específicas e, no momento, ' .
                'não possuímos o ferramental ideal para atender este modelo com a excelência que você merece.',
            self::SERVICO => 'Analisamos seu pedido, mas este tipo específico de serviço ' .
                'não faz parte do nosso menu de especialidades.',
            self::PECAS => 'Identificamos uma dificuldade severa no fornecimento de peças de qualidade ' .
                'para este reparo. Para não deixar seu carro parado aguardando peças sem previsão, ' .
                'preferimos não iniciar a desmontagem.',
            self::AREA => 'Verificamos que seu endereço está fora do nosso raio de atuação logística ' .
                'para busca e entrega.',
            self::CLIENTE_DESISTIU => 'Registramos o cancelamento do serviço conforme sua solicitação. ' .
                'Seu veículo já foi liberado e está pronto para retirada em nossa oficina. ' .
                'Agradecemos a oportunidade e as portas continuam abertas para atendê-lo no futuro.',
            self::OUTROS => 'Após análise técnica inicial da solicitação, identificamos que não conseguiremos ' .
                'atender esta demanda específica no momento.',
        };
    }
}