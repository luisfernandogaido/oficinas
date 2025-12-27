# oficinas

GiraOs, o app que aumenta o giro.

## Objetivo do app

GiroOs é um SaaS para oficinas mecânicas que tem como objetivo agilizar o fluxo de atendimento de OS, atacando o
principal problema das oficinas mecânicas, segundo meus estudos: lentidão no atendimento e desorganização no fluxo das
OS dentro das oficinas.

Diferente da maioria dos apps disponíveis no mercado brasileiro, que são grandes, complexos e caros, GiroOs visa ser
simples, totalmente funcional em celular e chão de fábrica. Não há relatórios complexos nem a necessidade de cadastro de
clientes, peças e serviços oferecidos, sendo útil para oficina a partir do dia 1.

## Estratégia de negócio

Pretendo visitar pessoalmente as oficinas de minha cidade, me apresentando como um experiente desenvolvedor local, da
mesma cidade que as oficinas. Com dois celulares nas minhas mãos, pedirei por 10 minutos do tempo do responsável pela
oficina para apresentar a plataforma e justificar sua compra. Seguirei a estratégia **Macha de Óleo**, crescendo a
partir de um ponto geográfico (Bauru) e crescendo de forma radial, ganhando terreno de forma lenta, mas consolidada. Há
centenas de oficinas apenas em Bauru, o que me dá margem para crescer bastante com essa estratégia.

Depois de possuir dezenas ou uma centena de oficinas consolidadas, seja em Bauru apenas ou com a ajuda de cidades
vizinhas, partirei para outras estratégias, que não precisam ser mutuamente exclusivas. Posso trabalhar com vendedores
de confiança, pagando generosas comissões. Também posso criar um excelente site, bem explicativo e com vídeos
demonstrativos, e a opção para as pessoas se cadastrarem na plataforma rapidamente. Poderei divulgar estrategicamente na
região, em lojas de peças, com cartões, inserções nas rádios da região ou até mesmo tráfego pago. A princípio estou
relutante em usar tráfego pago por acreditar que estamos na era renascentista do orgânico.

Eu sou inexperiente em negócios. Tenho muito mais experiência em desenvolvimento de software, com 20 anos de
carreira profissional. É por isso que eu estou totalmente aberto a novas estratégias de negócio e até mesmo parcerias,
sem sociedade no momento. Imagino que uma simples visita a um evento com pessoas influentes da área pode me catapultar
de forma definitiva. Mas, por ora, a estratégia Mancha de Óleo é mais segura para a plataforma na fase em que ela se
encontra.

Quando eu tiver algumas centenas de clientes, poderei fazer parcerias com fornecedores de produtos usados em oficinas e
ganhar comissões por entregar OS em tempo real dentro de um rápido raio de ação. Afinal poderei chegar a eles e dizer:

> Bem, eu tenho X oficinas em minha plataforma pedindo Y do seu produtos todos os dias. Eu tenho exatamente os
> estabelecimentos e o momento em que pedem. É um lead extremamente fácil e seguro de se fornecer. Eu quero Z% de
> desconto nas peças, e repassarei por fator desse desconto e ganharei pessoalmente o fator complementar.

Creio que essa estratégia de negócio me daria praticamente uma árvore de ganhar dinheiro.

À medida que mais e mais oficinas se cadastram, poderei ampliar a plataforma para interesse do público geral. Afinal, eu
poderei oferecer às pessoas uma ampla gama de oficinas prontamente disponíveis a atender às suas necessidades na região.
Note que eu tenderei a criar isso apenasm após alguns anos, quando eu tive uma massa de oficinas trabalhando em minha
plataforma. Eu tenho a teoria de que quanto mais pessoas usam minha plataforma, é praticamente inevitável que eu
encontre mais e mais formas de ganhos financeiros.

Mas tudo começará com o simples boca-a-boca. Mancha de Óleo.

Eu pretendo exibir aos meus sócios da empresa Profinanc, Fabiano Torres e Tiago Ferreira, da qual eu sou sócio fundador,
quando a plataforma estiver madura o suficiente. Provavelmente dentro de alguns dias. Caso eles aceitem, terei enorme
prazer em dividir os ganhos com eles, ao passo que terei ao meu lado pessoas que entendem de vendas e negócios melhor
que eu. Caso contrário, pretenderei seguir sozinho, sem formar sociedade com alguém. Poderei, no máximo, estabeler
parcerias com empresas em uma estratégia ganha-ganha, mas mantendo total autonomia sobre a plataforma.

## Desenvolvimento da plataforma

Eu sou o único criador e proprietário da Plataforma. Tenho utilizando LLM para atuar como Co-fundador, Tech Lead e
Estrategista de Vendas ("One Man Army Partner"). Atualmente tenho um Gem no Gemini e uma conta PLUS para ter acesso a
mais prompts de Raciocínio e Pro. Esse texto servirá como "The Ultimate Description" para que a LLM consiga manter
contexto de longo prazo e me ajudar melhor nos próximos passos. Tenho notado que a LLM perde o contexto se eu não
atualizar a descrição do Gem, e deixar que a própria LLM reescreva a descrição da Gem tem sido insuficiente para que os
próximos prompts mantenham a qualidade. Vem daí umas das principais motivações da escrita deste longo documento:
qualificar meu único conselheiro nesta empreitada até o presente momento.

**Mindset:** "Chão de Fábrica & Guerra". Pragmático ao extremo. O código serve à operação, não o contrário.

**Stack:** PHP 8.4 (Moderno: Enums, Property Hooks), MySQL (JSON Snapshots + Generated Columns para FTS), Vanilla JS (
State via URL/History API, Zero Frameworks), CSS Semântico (Zero Tailwind). APIs Go + MongoDB criadas por mim mesmo para
tarefas complexas, como por exemplo a que recebe binários.

## Funcionalidades presentes no sistema até o momento (26/12/2025)

### Criação de oficinas

As oficinas acessam um link com um código de acesso gerado por mim, que me identifica como o criador.
Futuramente esse recurso poderá ser entregue a outros usuários, com possível pagamento de comissões.

Uma vez acessado o link e não havendo uma sessão autenticada, o usuário automaticamente tem uma conta de usuário criado
como oficina e o potencial para configurar sua oficina. Não há a menor necessidade de o usuário criar emails, senhas
ou qualquer dado pessoal para começar a configurar sua oficina. O objetivo é reduzir a fricção de entrada, fomentando o
pronto uso da plataforma e sua rápida adoção. O código de acesso, contudo, expira em um número determinado de dias.
Se uma pessoa acessar o link após o número determinado de dias do convite, ele é obrigado a criar uma conta do modo
convencional, isto é, criando email e senha, com validação imediata para acesso. Esse comportamento foi calcado em outro
software, visando a criação indiscriminada de contas facilmente por usuários com potencial abuso de gratuidade. Como
esse softgware é fortemente likgado a histórico de clientes, essa prática é naturalmente desencorajada, e então eu posso
fazer com que o tempo de expiração de cada convite seja virtualmente ilimitado. Ao invés de colocar 3 dias para criação,
posso colocar mais tempo, como por exemplo 30 dias ou 365 dias.

Uma vez dentro da plataforma após esse rápido onboarding, um único item é exibido na home do app:

- Informações básicas: Informe nome, logo e endereço da sua oficina.

Não resta dúvidas do que o usuário deve fazer, que é clicar no item e cadastrar dados de sua oficina. Ao clicar no item,
um minimalista formulário é exibido, com autosave ao parar de digitar, com os campos:

- nome da oficina
- descrição
- 1 foto / logo
- cep
- endereço
- número
- complemento
- bairro
- uf
- cidade

Ao informar o CEP, são preenchidos automaticamente endereço, bairro, UF e cidade.

Como se pode perceber, o processo de onboarding e configuração da oficina é simples e pode levar pouquíssimos minutos.
Nenhum cadastro adicional é necessário.

Após informar esses dados, resta ao usuário voltar para a home, a qual terá agora 4 itens, em contraste com o único que
havia:

- Painel: Analise novas OS e acompanhe tudo que está acontecendo na bancada.
- Divulgue seu estabelecimento: Copie seu link ou gere seu QR Code para alguém próximo de você.
- Informações básicas: Informe nome, logo e endereço da sua oficina.
- Criar a atalho: Colocaremos um ícone na sua área de trabalho.

A oficina está pronta para começar a operar!

### Divulgação de seu próprio estabelecimento

Ao clicar no item "Divulgue seu estabelecimento", a oficina é levada a uma tela que contém o link que leva à home de sua
oficina para os clientes fazerem pedidos. Esse link pode ser copiado para a área de transferência, ideal para que ele
mesmo informe em alguma plataforma, com o seu instagram ou resposta automática de seu WhatsApp Business. A tela também
exibe o QR Code do link, ideal para que a oficina imprima e disponha tal QR Code fisicamente, impresso, em cartões ou
folhas na oficina, ou exibir diretamente a um cliente próximo do aparelho fisicamente, para que acesse imediatamente.

A plataforma funciona integralmente como um relacionamento entre cliente/motorista e oficina, com o fluxo de status de
atendimento todo na plataforma, mas passando informativos através de WhatsApp, com "clicks to chat" desenhados de
maneira perfeita para uma excelente e assertiva comunicação. A divulgação do estalecimento é um passo crucial e essa
tela passa rapidamente essa mensagem. Na tela está escrito: "Este é o link do seu estabelecimento. Divulgue para que
seus clientes solicitem e acompanhem serviços."

### Solicitação de atendimento

Quando um cliente acesse o link da oficina, uma página simples e direta é exibida com os seguintes dados:

- foto/logo
- nome em destaque
- endereço
- serviços oferecidos

Um stick bottom button é exibido com a ação "Solicitar atendimento".

A plataforma não permite que a página da oficina venda produtos. O sistema, atualmente, é puramente para fluxo de
atendimentos Ordens de Serviço.

Ao clicar em "Solicitar atendimento", o cliente/motorista é levado a um wizard de até 7 passos, com perguntas e
formulários simples. A maioria dos passos exibe uma lista de radio buttos bem estilizados que, ao serem clicados, salvam
a escolha e levam para o próximo passo. Esse método atrai o usuário ao rápido preenchimento com informações ricas para a
oficina, sem complexos preenchimentos. Se o usuário não está autenticado, um usuário novo é criado no banco de dados e
associado à sessão corrente, sem maior esforço. Ou seja, todos os dados vinculados ao usuário já são armazenados
diretamente no banco, simplificando o processo ao custo de inserir no banco de dados eventualmente cedo demais se o
usuário se tornar um desistente.

#### Passo 1: Problema principal

10 radio buttons:

- Revisão / Troca de Óleo
- Freios
- Suspensão / Pneus
- Motor / Barulhos
- Superaquecimento / Vazamentos
- Elétrico / Luzes no Painel
- Embreagem / Câmbio
- Ar Condicionado
- Funilaria / Pintura
- Outros

Os itens são colocados do mais frequente ao menos frequente no cotidiano. Os termos também são claros o suficiente para
que uma pessoa leiga em assuntos de oficina consiga classificar o problema.

#### Passo 2: Quando ocorreu

6 radio buttons:

- Hoje
- Ontem
- Nesta semana
- Neste mês
- Há meses
- Há anos

Note que não solicito datas específicas, mas sim escalas temporais de rápido acesso cognitivo para uma pessoa.

#### Passo 3: Frequência

3 radio buttons:

- Sempre
- Às vezes
- Raramante

Mais do mesmo: fácil de se preencher.

#### Passo 4: Observações

Dois textareas opcionais são exibidos:

- Conte os sintomas que você notou
- O problema aparece em quais condições?

O texto é livre, mas não obrigatório. É a chance que o cliente/motorista tem de detalhar um pouco mais o problema. É a
parte mais crítica do processo de ponto de vista de usabilidade, pois escrever é chato, e até eu não gosto muito, mesmo
sendo uma pessoa que tem uma escrita clara, prática e rápida. É o ponto baixo, mas é opcional.

#### Passo 5: Fotos e vídeos

Aqui o cliente tem a liberdade para enviar fotos e vídeos que descrevem o problema, se ele julgar conveniente. O texto é
claro na tela : "Se fizer sentido, envie fotos ou vídeos curtos. Seja objetivo e vá direto ao ponto." O usuário pode
escolher um ou mais arquivos de foto e vídeo, ou até mesmo usar a câmera diretamente do dispositivo, se o hardware e o
sistema operacional permitirem. Após a escolha, uma barra de progresso é exibida. Após o término do upload, os assets
são exibidos. Cada um deles pode ser excluído ou aberto em uma nova aba do navegador e, com isso, ser passível de
visualização com zoom. Um limite de 100MB é estipulado para os clientes enviarem os assets. Não há compressão
client-side até o momento, mas isso é uma possibilidade não descartada no futurol. Ao passar de 100MB, a aplicação
impede o envio de novos.

Esses arquivos poderão dar uma boa noção à oficina do que ocorre, de maneira centralizada, sem lotar o WhatsApp da
oficina.

O back-end é muito bem projetado para esse problema de armazenamento de binários e me tomou entre 30 e 40% de todo o MVP
especificamente. Resumidamente, eu mesmo criei uma API para binários. Essa API é externa ao software e pode ser
aproveitada para outras soluções. Ela, em resumo, recebe arquivos com seus metadados, armazena em um filesystem os
arquivos e os metadados (aplicação, caso de uso, código de referência, metadados do arquivo, taxa de compressão etc) e
devolve urls dos arquivos. A aplicação não precisa armazenar o link, pois a API permite a busca por caso de uso e código
de referência. Isso é prático, pois como os links podem ser enviados com endereços diferentes ao longo do tempo,
perfeito para movimentação de arquivos entre diferentes servidores. A API também conta com a capacidade de criar cópias
e movimentações de arquivos entre diferentes servidores, usando rsync e SSH. Isso permite que arquivos "quentes" sejam
mantidos em servidores de baixa latência, mais caros, e, oportunamente sejam movidos para servidores de maix baixo custo
quando "esfriarem". O serviço também tem mecanismos de compressão de áudio e vídeo com base nos metadados enviados com
os arquivos. Esse processo é transparente, em background e, no caso do vídeo, ocupa apenas um núcleo do CPU do servidor,
evitando gargalos de acesso durante pesadas compressões do ffpmeg. Os arquivos são entregues via nginx em domínios
especialmente configurados para entregar com eficiência binários. Toda essa preocupação que eu tive criando esse serviço
visa ter um mecanismo proprietário flexível e de baixo custo para armazenar dados. Afinal, eu sou um excelente
programador e sou capaz de criar soluções que não lançam mão de APIs de custos caros e imprevisíveis. Vibe coders se
encantariam com as sugestões fáceis de APIs caras. Eu não.

#### Passo 6: Veículo

Essa tela exibe uma lista de buttons de veículos previamente informados pelo usuário, com uma última opção permitindo o
cadastro de um novo. Obviamente, se o usuário é novo, só exibirá a opção para cadastrar um novo veículo. O cadastro é
simples direto: ao clicar no radio button de novo três campos são abertos na mesma tela:

- placa
- km
- busca por modelo

O campo de busca é um suggest que bate aproximadamente 50 mil versões/marcas/modelos FIPE, pois tenho esses dados
através de um outro serviço que eu criei. Com isso, com poucos toques de texto, o usuário pode informar marca, modelo,
versão e ano do veículo. E seguir em frente.

#### Passo 7: Quem é você?

Esse passo é exclusivo para usuários não autenticados, tipicamente pessoas que estão acessando a plataforma pela
primeira vez, ou que costumam limpar o histórico do seu navegador com frequência. Apenas dois controles são exibidos:

- campo para nome completo
- botão "Enviar código pelo WhatsApp"

A tela tem uma pequena explicação também: "Com esse código saberemos quem é você e lhe manteremos informado."

Essa é uma forma inovadora de identificar o usuário: nada de email e senha, mas sim WhatsApp. Quando o usuário clica
neste botão uma mensagem com o um código de acesso é gerada e colocada via click to chat com destinatário a um telefone
meu. Esse telefone usa baileys e é capaz de identificar via substring na mensagem se é uma mensagem de autenticação. Com
isso, meu código acessa um endpoint enviando o número de telefone para que seja associado ao usuário da sessão. Esse
processo vincula de forma automática o número de celular ao usuário provisório e, de certa forma, o identifica
unicamente no banco de dados, sem a necessidade usuário e senha.

Caso o celular já exista na base de clientes daquela oficina, o usuário provisório tem seus dados (veículo e OS com seus
relacionamentos) movidos para o usuário correspondende, e não há duplicidades, nem mesmo se ele criou de forma repetida
o veículos com mesma placa. Uma complexidade chata criada apenas para permitir que o onboarding do cliente seja fluido
conforme o prometido, com a identificação como último passo. Eu confesso que essa complexidade, analisando em
retrospecto, é algo que eu não gostaria de seguir fazendo em minhas aplicações. Ou eu torno esse processo de
preechimento mais formal, ou eu não insiro diretamente na base até a autenticação, ou peço a autenticação imediatamente
no começo, já inserindo os dados diretamente na conta correta.

Devo confessar que procurei por esse design, pois queria muito que o cliente começasse informando os dados do problema,
o que provavelmente é muito mais atraente que ter que se identificar/criar conta. A minha intenção era evitar a fuga dos
clientes em um moroso processo. Gostei, mas não repetirei exatamente dessa forma em casos futuros. O tempo vai dizer se
eu acertei com essa forma tão inovadora de obter dados do cliente, incluindo a identificação via WhatsApp.

#### Pós solicitação

O cliente é levado a uma página contendo uma OS com um hash único e de difícil adivinhação. Esse link é o mesmo que a
oficina enxerga, mas dependendo de quem acessa, esse link se apresenta de maneiras diferentes:"

- cliente solicitante
- oficina atendente
- usuário externo, "conviado" a observar

Esse endpoint é o mais complexo da aplicação até o presente momento, pois deve apresentar diferentes informações e
controles para os diferentes atores interessados na OS e do próprio momento/status em que a OS de encontra. O que é
apresentado nessa tela é um verdadeiro ping pong entre cliente e oficina. Esse ping pong permeia todo o atendimento em
seus diferentes estados.

A view do cliente possui uma série de tiles simples e poucas ações dependendo do status. Um
tile principal, no topo, sempre exibe as informações mais importantes ao cliente dependendo do status atual, como
informações sobre agendamento ou retirada do veículo.

A view da oficina, muito mais complexa, é composta por um head com detalhes imediatos da OS, abas com diferentes
assuntos da OS, como detalhes do pedido, orçamento, histórico de status etc. e, por fim, duas ações principais numa
barra de ações flutuante na parte de baixo da tela. Essas ações mudam dependendo do status. Botões com ações menos
importantes podem ser encontrados dentro de algumas das abas. Há, por fim, um kebab menu com ações pouco frequentes de
caráter global, como por exemplo visualizar o que o cliente está vendo em sua tela.

### Apresentação da OS nas visões da oficina e do cliente

Uma OS possui os seguintes status:

- RASCUNHO: durante a edição/criação da OS por parte do cliente.
- PENDENTE_MODERACAO: quando o cliente termina a OS e ela precisa passar pelo moderador MASTER (eu).
- BLOQUEADA: caso o moderador descubra que os dados são impróprios. A oficina sequer fica sabendo.
- SOLICITADA: caso o moderador aprove a OS.
- ANALISE: caso a oficina acate a solicitação.
- AGENDADA: caso a oficina agende uma data para receber o veículo.
- AGUARDANDO_APROVACAO: caso a oficina crie feche um diagnóstico, que precisa ser aprovado pelo cliente.
- EM_ANDAMENTO: caso o cliente aprove o orçamento.
- FINALIZADA: caso a oficina conclua com sucesso o trabalho.
- CONCLUIDA: caso o cliente busque o veículo e efetue o pagamento.
- CANCELADA: caso cliente decida cancelar o processo.
- REJEITADA: caso a oficina decida por não acatar a solicitação .

Os únicos status que nunca são observados na tela de OS são RASCUNHO, PENDENTE_MODERACAO e BLOQUEADA, pois dependem de
uma interface disponível apenas para o MASTER, que nestes momentos iniciais do software (nem foi lançado ainda),
precisarão de uma pessoa para validar se há material inapropriado enviado por parte de vândalos trasvetidos de clientes.
Essa tela, a propósito, não existe até o presente momento, e deve em um futuro próximo ser implementada.

A partir de agora cada um dos status disponíveis na tela de OS serão descritos sob o ponto de vista da oficina e do
cliente, com os dados e ações que cada um dos atores pode tomar. A visão do usuário externo ainda não foi implementada,
e é um cenário em que eventualmente a OS é compartilhada para que outros possam ver. Em essência, será read-only e um
convite, um chamariz para que novos clientes e oficinas se cadastrem na minha plataforma.

#### SOLICITADA

O cliente preencheu a solicitação, teve seu pedido aceito pelo moderador e agora aguarda o atendimento. O cliente pode
ainda nesta fase editar o wizard pelo qual ele passou registrando o pedido. Também pode cancelar a solicitação. Ele pode
ver detalhes do problema, do veículo e as mídias que enviou.

A oficina observa todos os detalhes do pedido na aba "Solicitação". Pode entregar uma estimativa, adicionando produtos e
serviços, mesmo sem ter o veículo em mãos. Um resumo da estimativa é exibido em tempo real na visão do cliente, sem
detalhes de peças. Essa estimativa é importante para reduzir a ansiedade por parte do cliente, que espera um valor,
mesmo que aproximado, do que seria cobrado pela oficina caso o cliente decida ir adiante. Também fica claro que é um
valor estimado, não pode ser confundido com um diagnóstico totalmente fechado. A visão do cliente deixa isso claro nessa
fase.

Nesta fase oficina pode, também, rejeitar a solicitação, alegando um dos seguintes motivos:

- Agenda cheia
- Pátio lotado
- Modelo não atendido
- Serviço não oferecido
- Peças indisponíveis
- Fora da área
- Cliente desistiu
- Outros motivos

Ao escolher um dos motivos, via modal, a plataforma dá ao usuário a opção de enviar a mensagem ao WhatsApp do cliente,
via click-to-chat. Uma mensagem muito bem projetada para cada um dos casos é enviada. A parte interessante desse
processo é que até mesmo em momentos de desconforto, como a negativa de uma pedido, é transformado em uma comunicação
assertiva e profissional para com o cliente. Claro, nada supera aquele áudio bacana, mas isso não escala. E tudo que
esse software tenta fazer é otimizar os atendimentos.

A oficina pode também nesse estado, agendar para receber o veículo. Ao selecionar o agendamento, opções de data e hora
extremamente simplificadas são exibidas. Ao invés de tentar criar um complexo sistema de agendamentos (há muitos SaaS
que fazem isso, a propósito), a ideia aqui é permitir de forma relâmpago que a oficina passe a bola ao cliente com uma
data/hora para o recebimento do carro. O modal apresenta uma janela de seis dias: hoje, amanhã, dia-da-semana,
dia-da-semana, dia-da-semana e dia-da-semana, onde "dia-da-semana" é um dia seguinte, pulando domingos. Exemplos:
segunda, terça, quarta, quinta e sexta. Se o dia corrente já passou das 20h00, não há agenda para hoje. Um combo de
horários de meia em meia hora sobre das 08h00 às 20h00. Horários que já passaram no dia de hoje não são exibidos. E FIM!
Nada complexo, com conflito de agenda etc. A ideia neste MVP é ser simples, dar pouco espaço para engessamento por parte
da oficina. Muito melhor que reportar conflitos de agenda é permitir que o usuário da oficina consiga rapidamente marcar
um horário dentro de um futuro próximo. O cliente pode ser notificado via click to chat com uma mensagem adequada sobre
o agendamento.

Por fim, a oficina também pode dar entrada do veículo nesse estágio. Isso deve ocorrer quando o cliente já está presente
na oficina, ou na eventualidade de o carro chegar cumprindo o agendamento. Dar entrada exige um simples check-in com
apenas dois campos:

- km
- Nível do tanque:
    - Reserva
    - 1/4
    - 1/2
    - 3/4
    - Cheio

A esta altura, 26/12/2025, eu visitei pessoalmente algumas oficinas para demonstrar o protótipo desse MVP, e o pedido de
melhoria mais frequente, disparadamente, foi: precisamos de um checklist de entrada. As oficinas alegam que o momento do
check-in deve ser usado para laudar o estado em que o carro atinge as dependências da oficina. É uma comprovação de que
eventuais danos no veículo não ocorreram na oficina. Negligenciado por mim no MVP, notei que é tão essencial que será um
dos próximos features que criarei. Isso não me impedirá de sair vendendo, pois eu preciso validar o produto logo. Mas
certamente farei em breve. Essa nova funcionalidade de check-in provavelmente será feita em uma tela separada, com um
resumo sendo exibido em uma nova aba de check-in. A nova tela terá autosave, mas quero que seja totalmente dedicada a
isso. Ao final do preenchimento, o check-in servirá como uma espécie de documento, de laudo, com data/hora e read-only.

#### AGENDADA

Para todos os fins, AGENDADA pode tudo que SOLICITADA pode, exceto pelo fato de que há uma data de agendamento
informada.

O cliente enxerga no principal tile essa data. Também pode clicar num botão que abre o google maps com a rota do
estabelecimento traçada, e um botão de WhatsApp para entrar em contato.

A oficina pode reagendar, cancelar o agendamento (voltando para SOLICITADA) e dar entrada no veículo.

#### ANALISE

O carro já está com a oficina, que pode começar a detalhar um orçamento mais preciso.

O cliente é incentivado a esperar por um orçamento completo. A partir dessa fase, ele não pode mais editar o problema,
pois tudo já está nas mãos da oficina, incluindo o veículo.

A oficina pode detalhar o orçamento e, quando ele estiver concluído, enviar um click-to-chat ao cliente apertando o
botão "Concluir Orçamento". O acionamento deste botão não envia apenas uma mensagem ao cliente. Ele muda o estado da OS
para AGUARDANDO_APROVACAO. A oficina pode, também, cancelar/devolver o veículo o modal de rejeição é novamente aberto
para a oficina informar.

Escrevendo esse tópico agora, me dei conta que, internamente, não há distinção entre uma OS rejeitada no início para uma
OS cancelada/devolvida após a análise. Noto que há uma importante diferença operacional: no primeiro caso o carro não
está ocupando espaço no pátio. No segundo, sim. E do ponto de vista operacional, ocupar espaço é bem diferente de não
ocupar. Precisarei futuramente resolver isso. Entendo que será muito mais raro cancelar uma OS nesse estágio em que o
carro está no pátio, mas ainda é um fluxo possível.

É armazenado no histórico da OS detalhes completos do orçamento, com data, itens e valores. Isso será crucial na
eventualidade de
disputa entre cliente e oficina. A mensagem enviada ao cliente o convida para apreciar o orçamento em seus detalhes,
assim como aprová-lo dentro de sua própria tela.

#### AGUARDANDO_APROVACAO

Idealmente a oficina deve nada fazer durante esse estágio. Mas ela pode:

- voltar para ANALISE: é uma espécie de cancelamento da conclusão do orçamento
- aprovar: apesar de ser possível, deve ser usado apenas em caso de extrema confiança no cliente, pois em caso de
  disputa a plataforma não terá registrado que foi o cliente que aprovou.

O cliente é encorajado a analisar o orçamento em detalhes. Nesta fase, cada item de produto e serviço é exibido, em
contraste com o resumo que era exibido em fases pré ANALISE. Ao clicar no botão para aprovar, um modal de confirmação
com o valor total do orçamento é exibido uma vez mais. Apesar de o valor total estar visível nos totais do orçamento,
essa é uma forma de atestar que a decisão do cliente em aprovar o orçamento foi um ato consciente. O cliente também pode
entrar em contato com a oficina via botão click to chat.

#### EM_ANDAMENTO

Se o orçamento for aprovado, a OS muda para esse status.

Ao cliente cabe apenas esperar passivamente mais informações.

À oficina é exibida uma aba nova, chamada "Execução", que lhe dá apenas a opção de registrar data e hora que se estima
terminar o serviço. É tão simples quanto tocar em dois radio buttons para escolher, e clicar num "X" para remover a
escolha. Essa tela é mais um exemplo de minha obsessão por simplicidade. Uma vez mais, não apresento inputs de date e/ou
time, nem calendários complexos. Exibo radio buttons para uma data próxima (hoje, amanhã, terça, quarta etc) e outro
para um horário típico, com apenas opções:

- 09:00
- 11:00
- 13:30
- 15:30
- 17:00
- 18:00

O usuário da oficina nem precisa pensar muito para escolher. Toca num radio button, depois no outro e pronto. Esses
cuidados se acumulam por toda a aplicação, e minha expectativa é que seja extremamente fácil de se usar a plataforma. E
aplicações fáceis e úteis significam baixo churn.

Ao cliente é exibido no principal data e hora estimadas para a conclusão do serviço sobre o veículo.

A oficina pode voltar para análise o que, para todos os fins, um cancelamento do orçamento. Imagine que um achado
foi encontrado apenas durante a execução. É óbvio que nesse estáio o orçamento não pode mais ser modificado nem pela
oficina. Para que ela tenha a oportunidade de mudá-la, o orçamento aprovado precisará ser cancelado.

A oficina pode, por fim, finalizar o serviço, ao clicar nesse botão uma mensagem é enviada ao cliente via click to chat:
hora de buscar. Na tela do cliente, ele também é convidado a retirar o veículo. Grande momento!

#### FINALIZADA

A oficina aguarda o momento de retirada, para, finalmente concluir o orçamento e tirá-lo de "vista operacional".

O cliente é convidado a retirar o carro.

#### CONCLUIDA

Final feliz: mais dinheiro no bolso da oficina e menos um carro ocupado espaço. Nesse estágio essa tela é read-only
tanto para a oficina quanto para o cliente, guardadas as diferenças de visão desses atores.

#### CANCELADA

Devia ser quando é cancelada pelo cliente, mas não me lembro exatamente como tratei todos os caminhos de cancelamento.

#### REJEITADA

Devia ser quando é cancelada pelo cliente, mas não me lembro exatamente como tratei todos os caminhos rejeição.

### Painel

O painel tem como objetivo exibir rápida e facilmente todos os atendimentos em andamento e que exigem atenção por parte
da oficina. Tecnicamente são todas as OS com os seguintes estados:

- SOLICITADA
- ANALISE
- AGENDADA
- AGUARDANDO_APROVACAO
- EM_ANDAMENTO
- FINALIZADA

Chamo esse modo de OPERACIONAL, pois reflete o que deve chamar a atenção imediata da oficina. Sem sequer que uma busca
seja necessária, todas as OS nesse estado são exibidas em uma lista de cards horizontais contendo as seguintes
informações:

- modelo
- placa
- número OS
- status
- nome cliente
- tempo em que a OS está no status corrente
- valor do orçamento

Existe um botão no canto superior direito remetendo a um histórico. Quando usuário clica sobre ele, essa mesma tela
alterna para o modo histórico. Ela segue tendo a lista com os mesmos dados, mas duas coisas cruciais mudam:

- uma barra de busca é exibida
- apenas OS com os segiuntes estados são exibidas:
    - CONCLUIDA
    - CANCELADA
    - REJEITADA

É notável que esse modo de busca serve para pesquisar dados passados, antigos. A barra de busca pesquisa por

- placa
- nome do cliente
- telefone
- marca/modelo

Essa lista, com o tempo, tenderá a ser muito grande. Por isso a plataforma sempre lista os 100 últimos resultados. Isso
também vale para o modo "operacional".

Ao clicar num card, o usuário da oficina é levado à tela da OS.
Ao sair do paine, fica preservado via replaceState na URL:

- posição do scroll
- termos de busca

Isso é perfeito para quando o usuário da oficina está entrando e saindo de diferentes OS pesquisadas por ele, melhorando
a usabilidade.

### Controle administrativo dos usuários (MASTER)

Apesar de os usuários de oficina poderem livremente criar suas contas, o perfil MASTER (eu) os enxerga e pode entregar
limitações e validações através da área do MASTER. Usuários podem ser encontrados e ter seu cadastro acessado. Eu posso
visualizar numa única página todos os dados do cadastro do cliente, como por exemplo:

- código
- nome
- email
- celular
- cpf/cnpj
- perfil de acesso
    - master
    - admin
    - padrao
    - funcionario
    - cliente
- status
    - pendente
    - ativo
    - inativo
    - provisorio
- criação
- alteração

Posso forçar que o usuário se identifique via envio de código por WhatsApp. Esse envio chegará ao mesmo número de
WhatsApp que eu tenho o baileys configurado e, para todos os fins, registra automaticamente o celular do usuário.
Marcando essa opção, o usuário será forçado a fazer isso em telas estratégicas, como do painel e da OS.

Também posso forçar o usuário a assinar. Com isso desmarcado, o usuário da oficina pode usar livremente a plataforma.
Com isso marcado, tela do painel e tela da OS deverão levar o usuário a fazer um pagamento via AssaS. Se o pagamento for
bem-sucedido, a liberação do acesso é automática. Isso permite com que eu seja mais flexível com alguns clientes, dando
um período arbitrário de testes, mas com total controle sobre o momento em que ele deverá assinar.

Eu posso entrar em contato via WhatsApp click to chat com o usuário. Também posso personificar em sua conta,
visualizando tudo que ele veria e até mesmo atuando por ele.

### Configurações (MASTER)

Eu, como MASTER, posso decidir se novas contas devem imediatamente validar WhatsApp ao usar minha plataforma via
registro rápido de com link. Também posso decidir qual número de telefone é usada para receber mensagens de validação.
Isso é perfeito para alternar de número quando o meu script node + baileys para de funcionar por falta de rede ou queda
de energia e eu não estou perto para reiniciar. Assim posso desviar mensagens para meu número pessoal.

É muito fácil para eu verificar se meu script node + baileys está up: posso enviar de qualquer número o texto "ping".
Ele está programado para responder "Pong! 🏓". Se isso não ocorrer, caiu e preciso reconfigurar o número se não estiver
presente. Eu propositadamente uso outro número que não meus dois pessoias e business presentes em meu celular: paz.

### Visualização de Workspaces (MASTER)

Posso observar todos os workspaces criados, personificando com o usuário dono e atuando como a própria oficina, por
conveniência, se eu quiser. Essa espiadinha me permite acompanhar de perto como as coisas estão indo.

### Lista de assinantes (MASTER)

Posso observar quantas assinaturas está vigentes e observar a saúde do meu negócio.

### Espalhe (MASTER)

Posso gerar tokens de acesso e enviar para as pessoas poderem criar suas contas de oficina rapidamente, via QR code,
link copiado ou até mesmo usando a Share API do Javascript.