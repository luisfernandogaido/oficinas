# oficinas

Gira Oficinas, o app que aumenta o giro.

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