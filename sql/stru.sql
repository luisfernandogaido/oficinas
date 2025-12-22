create table aut
(
    codigo  int unsigned auto_increment
        primary key,
    token   char(64)     not null,
    criacao datetime     not null,
    uso     datetime     null,
    email   varchar(100) null,
    celular varchar(11)  null
);

create table conta
(
    codigo    smallint unsigned auto_increment
        primary key,
    nome      varchar(50)                  not null,
    ativa     tinyint unsigned default '0' not null,
    criacao   datetime                     not null,
    alteracao datetime                     not null
);

create table fipe
(
    id          char(24)    not null
        primary key,
    tipo        varchar(8)  not null,
    marca       varchar(25) not null,
    modelo      varchar(50) not null,
    ano         year        not null,
    combustivel varchar(15) not null,
    codigo_fipe char(8)     null,
    valor       float       not null,
    indice      text        not null
);

create fulltext index fipe_indice_index
    on fipe (indice);

create table peso
(
    codigo smallint unsigned auto_increment
        primary key,
    data   datetime not null,
    peso   float    not null
);

create table registro
(
    codigo  mediumint unsigned auto_increment
        primary key,
    date    datetime     not null,
    type    varchar(10)  not null,
    args    varchar(10)  null,
    text    varchar(150) null,
    `float` float        null,
    created datetime     not null
);

create table usuario
(
    codigo            mediumint unsigned auto_increment
        primary key,
    cod_conta         smallint unsigned not null,
    nome              varchar(50)       not null,
    email             varchar(200)      not null,
    celular           varchar(11)       null,
    cpf_cnpj          varchar(14)       null,
    whatsapp_validado tinyint default 0 not null,
    forcar_assinatura tinyint default 0 not null,
    senha             char(64)          not null,
    perfil            varchar(25)       not null,
    status            varchar(10)       not null,
    apelido           varchar(50)       not null,
    indice            text              not null,
    criacao           datetime          not null,
    alteracao         datetime          not null,
    constraint cpf_cnpj
        unique (cpf_cnpj),
    constraint email
        unique (email),
    constraint usuario_pk
        unique (celular),
    constraint usuario_ibfk_1
        foreign key (cod_conta) references conta (codigo)
);

create table assinatura
(
    codigo            mediumint unsigned auto_increment
        primary key,
    cod_usuario       mediumint unsigned     null,
    cod_conta         smallint unsigned      null,
    nome              varchar(50)            not null,
    ini               datetime               not null,
    fim               datetime               not null,
    valor             decimal(6, 2) unsigned not null,
    status            varchar(25)            not null,
    asaas_id          char(20)               null,
    asaas_installment char(36)               null,
    asaas_invoice_url varchar(100)           null,
    asaas_status      varchar(25)            null,
    criacao           datetime               not null,
    pagamento         datetime               null,
    constraint assinatura_ibfk_1
        foreign key (cod_usuario) references usuario (codigo),
    constraint assinatura_ibfk_2
        foreign key (cod_conta) references conta (codigo)
);

create index assinatura_pagamento_index
    on assinatura (pagamento);

create index cod_conta
    on assinatura (cod_conta);

create index cod_usuario
    on assinatura (cod_usuario);

create table compra
(
    codigo            int auto_increment
        primary key,
    cod_usuario       mediumint unsigned                     not null,
    referencia        varchar(24) collate utf8mb4_0900_as_cs not null,
    descricao         varchar(75)                            not null,
    preco             decimal(10, 2)                         not null,
    parcelas          int default 1                          not null,
    valor_parcelas    decimal(10, 2)                         not null,
    criacao           datetime                               not null,
    pagamento         datetime                               null,
    cancelamento      datetime                               null,
    asaas_id          char(20)                               not null,
    asaas_installment char(36)                               not null,
    asaas_invoice_url varchar(100)                           not null,
    asaas_status      varchar(25)                            not null,
    asaas_all_ids     text                                   not null,
    detalhes          text                                   null,
    constraint compra_usuario_codigo_fk
        foreign key (cod_usuario) references usuario (codigo)
);

create index compra_pagamento_index
    on compra (pagamento);

create index compra_referencia_index
    on compra (referencia);

create table convite
(
    codigo      int unsigned auto_increment
        primary key,
    cod_usuario mediumint unsigned         not null,
    cupom       varchar(6) charset utf8mb4 not null,
    dias        tinyint unsigned           not null,
    criacao     datetime                   not null,
    constraint convite_cupom_uindex
        unique (cupom),
    constraint convite_usuario_codigo_fk
        foreign key (cod_usuario) references usuario (codigo)
            on delete cascade
)
    collate = utf8mb4_0900_as_cs;

create table convidado
(
    codigo      mediumint unsigned not null
        primary key,
    cod_convite int unsigned       not null,
    entrada     datetime           not null,
    constraint convidado_convite_codigo_fk
        foreign key (cod_convite) references convite (codigo),
    constraint convidado_usuario_codigo_fk
        foreign key (codigo) references usuario (codigo)
            on delete cascade
);

create table projeto
(
    codigo      smallint auto_increment
        primary key,
    cod_usuario mediumint unsigned not null,
    cod_conta   smallint unsigned  not null,
    nome        varchar(25)        not null,
    constraint projeto_ibfk_1
        foreign key (cod_conta) references conta (codigo),
    constraint projeto_ibfk_2
        foreign key (cod_usuario) references usuario (codigo)
);

create index cod_conta
    on projeto (cod_conta);

create index cod_usuario
    on projeto (cod_usuario);

create table tarefa
(
    codigo      mediumint auto_increment
        primary key,
    cod_projeto smallint                   not null,
    nome        varchar(150)               not null,
    descricao   text                       null,
    arquivada   tinyint default 0          not null,
    tempo_total time    default '00:00:00' not null,
    criacao     datetime                   not null,
    trello_card text                       null,
    indice      text                       not null,
    constraint tarefa_ibfk_1
        foreign key (cod_projeto) references projeto (codigo)
            on delete cascade
);

create fulltext index indice
    on tarefa (indice);

create index projeto_tarefa_ibfk_1
    on tarefa (cod_projeto);

create table tarefa_card
(
    codigo     mediumint auto_increment
        primary key,
    cod_tarefa mediumint                               not null,
    url        varchar(200) collate utf8mb4_0900_as_cs not null,
    constraint tarefa_card_ibfk_1
        foreign key (cod_tarefa) references tarefa (codigo)
            on delete cascade
);

create index cod_tarefa
    on tarefa_card (cod_tarefa);

create table tempo
(
    codigo     mediumint auto_increment
        primary key,
    cod_tarefa mediumint not null,
    ini        datetime  null,
    fim        datetime  null,
    tempo      time      null,
    criacao    datetime  not null,
    constraint tempo_ibfk_1
        foreign key (cod_tarefa) references tarefa (codigo)
            on delete cascade
);

create index cod_tarefa
    on tempo (cod_tarefa);

create index celular
    on usuario (celular);

create index cod_conta
    on usuario (cod_conta);

create fulltext index indice
    on usuario (indice);

create table veiculo
(
    codigo           int unsigned auto_increment
        primary key,
    cod_proprietario mediumint unsigned not null,
    tipo             varchar(8)         not null,
    marca            varchar(25)        not null,
    modelo           varchar(50)        not null,
    ano              year               not null,
    combustivel      varchar(15)        not null,
    codigo_fipe      char(8)            not null,
    valor_fipe       float unsigned     not null,
    id_fipe          char(24)           not null,
    placa            char(7)            not null,
    km               int unsigned       not null,
    criacao          datetime           not null,
    alteracao        datetime           not null,
    indice           text               not null,
    constraint veiculo_pk
        unique (cod_proprietario, placa),
    constraint veiculo_usuario_codigo_fk
        foreign key (cod_proprietario) references usuario (codigo)
            on delete cascade
);

create fulltext index veiculo_indice_index
    on veiculo (indice);

create table whatsapp_validacao
(
    codigo        int unsigned auto_increment
        primary key,
    cod_usuario   mediumint unsigned not null,
    token         char(6)            not null,
    identificacao tinyint default 0  not null,
    criacao       datetime           not null,
    validacao     datetime           null,
    resposta      varchar(200)       null,
    constraint whatsapp_validacao_usuario_codigo_fk
        foreign key (cod_usuario) references usuario (codigo)
            on delete cascade
);

create index whatsapp_validacao_token_index
    on whatsapp_validacao (token);

create table workspace
(
    codigo      int unsigned auto_increment
        primary key,
    cod_criador mediumint unsigned not null,
    hash        char(16)           not null,
    nome        varchar(25)        not null,
    descricao   text               null,
    logo        varchar(100)       null,
    whats_app   varchar(11)        null,
    email       varchar(100)       null,
    cep         char(8)            null,
    endereco    varchar(100)       null,
    numero      varchar(10)        null,
    complemento varchar(25)        null,
    bairro      varchar(25)        null,
    uf          char(2)            null,
    cidade      varchar(50)        null,
    ativo       tinyint default 1  not null,
    criacao     datetime           not null,
    constraint hash
        unique (hash),
    constraint workspace_usuario_codigo_fk
        foreign key (cod_criador) references usuario (codigo)
);

create table categorias_produtos
(
    codigo        bigint unsigned auto_increment
        primary key,
    cod_workspace int unsigned         not null,
    nome          varchar(75)          not null,
    descricao     text                 null,
    ativa         tinyint(1) default 1 not null,
    criacao       datetime             not null,
    constraint categorias_produtos_pk
        unique (cod_workspace, nome),
    constraint categorias_produtos_workspace_codigo_fk
        foreign key (cod_workspace) references workspace (codigo)
            on delete cascade
);

create fulltext index categorias_produtos_descricao_nome_index
    on categorias_produtos (descricao, nome);

create table categorias_servicos
(
    codigo        bigint unsigned auto_increment
        primary key,
    cod_workspace int unsigned         not null,
    nome          varchar(75)          not null,
    descricao     text                 null,
    ativa         tinyint(1) default 1 not null,
    criacao       datetime             not null,
    constraint categorias_servicos_pk
        unique (cod_workspace, nome),
    constraint categorias_servicos_workspace_codigo_fk
        foreign key (cod_workspace) references workspace (codigo)
            on delete cascade
);

create fulltext index categorias_servicos_descricao_nome_index
    on categorias_servicos (descricao, nome);

create table os
(
    codigo          bigint unsigned auto_increment
        primary key,
    hash            char(32)                    not null,
    cod_workspace   int unsigned                not null,
    cod_cliente     mediumint unsigned          not null,
    cod_veiculo     int unsigned                null,
    criacao         datetime                    not null,
    alteracao       datetime                    not null,
    status          varchar(20)                 not null,
    problema        varchar(30)                 null,
    quando          varchar(15)                 null,
    frequencia      varchar(15)                 null,
    sintomas        varchar(200)                null,
    condicoes       varchar(200)                null,
    obs_cliente     text                        null,
    motivo_rejeicao varchar(20)                 null,
    km              int unsigned                null,
    nivel_tanque    tinyint                     null,
    agendamento     datetime                    null,
    itens           decimal(10, 2) default 0.00 not null,
    desconto        decimal(10, 2) default 0.00 not null,
    valor           decimal(10, 2) default 0.00 not null,
    constraint os_pk
        unique (hash),
    constraint os_usuario_codigo_fk
        foreign key (cod_cliente) references usuario (codigo),
    constraint os_veiculo_codigo_fk
        foreign key (cod_veiculo) references veiculo (codigo),
    constraint os_workspace_codigo_fk
        foreign key (cod_workspace) references workspace (codigo)
);

create table os_historico
(
    codigo       bigint unsigned auto_increment
        primary key,
    cod_os       bigint unsigned                     not null,
    cod_usuario  mediumint unsigned                  null,
    categoria    varchar(20)                         not null,
    visibilidade varchar(10)                         not null,
    status_old   varchar(20)                         null,
    status_new   varchar(20)                         not null,
    descricao    text                                null,
    criacao      timestamp default CURRENT_TIMESTAMP not null,
    constraint os_historico_os_codigo_fk
        foreign key (cod_os) references os (codigo)
            on delete cascade,
    constraint os_historico_usuario_codigo_fk
        foreign key (cod_usuario) references usuario (codigo)
);

create table produtos
(
    codigo         bigint unsigned auto_increment
        primary key,
    cod_categoria  bigint unsigned          not null,
    nome           varchar(100)             not null,
    descricao      text                     null,
    interno        tinyint(1)   default 0   not null,
    contador_uso   int          default 0   not null,
    unidade_medida char(2)                  not null,
    sku            varchar(25)              null,
    marca          varchar(50)              null,
    ref_fabricante int                      null,
    preco          decimal(10, 2)           not null,
    custo          decimal(10, 2)           not null,
    estoque        int unsigned default '0' not null,
    estoque_minimo int unsigned default '0' not null,
    ativo          tinyint(1)   default 1   null,
    criacao        datetime                 not null,
    alteracao      datetime                 not null,
    indice         text                     not null,
    constraint produtos_categorias_produtos_codigo_fk
        foreign key (cod_categoria) references categorias_produtos (codigo)
            on delete cascade
);

create fulltext index produtos_indice_index
    on produtos (indice);

create table servicos
(
    codigo        bigint unsigned auto_increment
        primary key,
    cod_categoria bigint unsigned      not null,
    nome          varchar(100)         not null,
    descricao     text                 null,
    interno       tinyint(1) default 0 not null,
    contador_uso  int        default 0 not null,
    preco         decimal(10, 2)       not null,
    custo         decimal(10, 2)       not null,
    tempo         smallint unsigned    not null,
    ativo         tinyint(1) default 1 not null,
    criacao       datetime             not null,
    alteracao     datetime             not null,
    indice        text                 not null,
    constraint servicos_categorias_servicos_codigo_fk
        foreign key (cod_categoria) references categorias_servicos (codigo)
            on delete cascade
);

create table os_itens
(
    codigo         bigint unsigned auto_increment
        primary key,
    cod_os         bigint unsigned             not null,
    cod_executante mediumint unsigned          not null,
    cod_produto    bigint unsigned             null,
    cod_servico    bigint unsigned             null,
    tipo           varchar(10)                 not null,
    nome           varchar(100)                not null,
    categoria      varchar(75)                 not null,
    unidade_medida char(2)        default 'UN' null,
    quantidade     decimal(10, 3)              null,
    preco          decimal(10, 2)              null,
    custo          decimal(10, 2)              null,
    desconto       decimal(10, 2) default 0.00 null,
    subtotal       decimal(10, 2)              null,
    ordem          int unsigned   default '0'  not null,
    criacao        datetime                    not null,
    constraint os_itens_executante_fk
        foreign key (cod_executante) references usuario (codigo),
    constraint os_itens_os_codigo_fk
        foreign key (cod_os) references os (codigo)
            on delete cascade,
    constraint os_itens_produtos_codigo_fk
        foreign key (cod_produto) references produtos (codigo),
    constraint os_itens_servicos_codigo_fk
        foreign key (cod_servico) references servicos (codigo)
);

create fulltext index servicos_indice_index
    on servicos (indice);

