<?php

use app\os\OsViewModel;
use modelo\MotivoRejeicao;
use modelo\NivelTanque;
use modelo\Os;
use modelo\OsItemTipo;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Veiculo;
use templates\Gaido;

/** @var Os $os */
/** @var Veiculo $veiculo */
/** @var Usuario $cliente */
/** @var OsViewModel $osVM */
?>

<?php $template = new templates\Gaido() ?>
<?php $template->interactiveWidget = Gaido::INTERATIVE_WIDGET_RESIZES_CONTENT ?>


<?php
$template->iniCss() ?>
    <link rel="stylesheet" href="os-oficina.css?v=4">
<?php
$template->fimCss() ?>

<?php
$template->iniMain() ?>

    <header>
        <button class="voltar"></button>
        <div class="banner"><b>OS #<?= $os->codigo ?></b></div>
        <button class="theme"></button>
        <div class="buttons">
            <button class="mais"></button>
            <div>
                <a class="button" href="os.php?h=<?= $hash ?>&viewas=client">Visão do cliente</a>
                <a class="button" href="zera.php?h=<?= $hash ?>" target="_blank">Zerar (para demonstrações)</a>
                <a class="button" href="os.php?h=<?= $hash ?>&viewas=client">Editar veículo</a>
                <a class="button" href="os.php?h=<?= $hash ?>&viewas=client">Corrigir dados de entrada</a>
                <a class="button" href="os.php?h=<?= $hash ?>&viewas=client">Reverter para Solicitada</a>
            </div>
        </div>
    </header>

    <div id="cabecalho">
        <p>
            <?= $veiculo->placa ?>
            •
            <?= $modeloCurto ?>
        </p>
        <p>
            <?= e($cliente->nome) ?>
            <button class="wa"></button>
        </p>
        <p>
            <span class="status <?= $os->status->value ?>"><?= $os->status->label() ?></span>
            <span class="badge"><?= $os->problema->label() ?></span>
        </p>
    </div>

    <div id="abas">
        <nav>
            <button data-target="solicitacao" class="active">Solicitação</button>
            <button data-target="orcamento">Orçamento</button>
            <button data-target="execucao">Execução</button>
            <button data-target="resumo">Resumo</button>
            <button data-target="historico">Histórico</button>
        </nav>
        <section>
            <div id="solicitacao" class="active">
                <div class="bloco">
                    <?php if ($os->status == OsStatus::REJEITADA): ?>
                        <div id="motivo-rejeicao">
                            Motivo rejeição:
                            <?= $os->motivoRejeicao->label() ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($os->status == OsStatus::AGENDADA): ?>
                        <div id="previsao-entrada">
                            Agendado:
                            <?= $osVM->previsaoEntrada ?>
                        </div>
                    <?php endif; ?>
                    <table>
                        <tbody>
                        <tr>
                            <th>Modelo</th>
                            <td><?= $veiculo->modelo ?></td>
                        </tr>
                        <tr>
                            <th>Ano</th>
                            <td><?= $veiculo->ano ?></td>
                        </tr>
                        <tr>
                            <th>Problema</th>
                            <td><?= $os->problema->label() ?></td>
                        </tr>
                        <?php
                        if ($os->quando): ?>
                            <tr>
                                <th>Quando</th>
                                <td><?= $os->quando->label() ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        <?php
                        if ($os->frequencia): ?>
                            <tr>
                                <th>Frequência</th>
                                <td><?= $os->frequencia->label() ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        <?php
                        if ($os->sintomas): ?>
                            <tr>
                                <th>Sintomas</th>
                                <td><?= e($os->sintomas) ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        <?php
                        if ($os->condicoes): ?>
                            <tr>
                                <th>Condições</th>
                                <td><?= e($os->condicoes) ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        <?php
                        if ($os->obsCliente): ?>
                            <tr>
                                <th>Observações</th>
                                <td><?= e($os->obsCliente) ?></td>
                            </tr>
                        <?php
                        endif; ?>
                        </tbody>
                    </table>
                </div>
                <?php
                if ($filesProblema): ?>
                    <div class="bloco" id="carrossel">
                        <button class="left"></button>
                        <?php
                        foreach ($filesProblema as $file): ?>
                            <?php
                            if (str_contains($file['type'], 'image')): ?>
                                <div>
                                    <img src="<?= $file['url'] ?>" alt="<?= $file['name'] ?>">
                                </div>
                            <?php
                            elseif (str_contains($file['type'], 'video')): ?>
                                <div>
                                    <video src="<?= $file['url'] ?>" controls></video>
                                </div>
                            <?php
                            endif; ?>
                        <?php
                        endforeach; ?>
                        <button class="right"></button>
                    </div>
                <?php
                endif; ?>
                <div class="botoes">
                    <button class="danger" id="rejeitar-solicitacao">Rejeitar Solicitação</button>
                    <button class="danger" id="cancelar-agendamento">Cancelar agendamento</button>
                </div>

            </div>
            <div id="orcamento" class="<?= $os->orcamentoTravado ? ' travado' : '' ?>">
                <div class="plus-wrapper">
                    <button class="plus" id="adicionar-itens"></button>
                </div>
                <div class="resultado"></div>
            </div>
            <div id="execucao">
                <form>
                    <div class="campos">
                        <div class="campo">
                            <div class="rotulo">
                                <label for="previsao-entrega">Previsão de entrega</label>
                            </div>
                            <div class="controle checks data-previsao-entrega">
                                <span id="sp-previsao-entrega"></span>
                                <button class="close" id="remove-previsao-entrega"></button>
                                <input type="hidden" id="previsao-entrega" value="<?= $os->previsaoEntrega ?>">
                                <?php foreach ($opcoesAgendamento['dias'] as $dia): ?>
                                    <label>
                                        <input type="radio"
                                               name="data-previsao-entrega"
                                               value="<?= $dia['data'] ?>"
                                               data-hoje="<?= $dia['e_hoje'] ? '1' : '0' ?>">
                                        <?= $dia['rotulo'] ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="mensagem"></div>
                        </div>
                        <div class="campo">
                            <div class="controle">
                                <br>
                            </div>
                        </div>
                        <div class="campo">
                            <div class="controle checks hora-previsao-entrega">
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="09:00">
                                    09:00
                                </label>
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="11:00">
                                    11:00
                                </label>
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="13:30">
                                    13:30
                                </label>
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="15:30">
                                    15:30
                                </label>
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="17:00">
                                    17:00
                                </label>
                                <label>
                                    <input type="radio" name="hora-previsao-entrega" value="18:00">
                                    18:00
                                </label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div id="resumo" class="<?= $os->orcamentoTravado ? ' travado' : '' ?>"></div>
            <div id="historico">
                <br>
                <table>
                    <tbody>
                    <?php
                    foreach ($historico as $h): ?>
                        <tr>
                            <td><span class="status <?= $h['status']->value ?>"><?= $h['status']->label() ?></span></td>
                            <td><?= $h['data_h'] ?></td>
                            <td class="usuario"><?= e($h['usuario']) ?></td>
                        </tr>
                    <?php
                    endforeach; ?>
                    </tbody>
                </table>
                <br>
            </div>
        </section>
    </div>

    <div id="actions">
        <div class="botoes">
            <button id="dar-entrada">Dar entrada</button>
            <button class="primario" id="agendar">Agendar</button>
            <button id="cancelar-devolver" class="danger">Cancelar/Devolver</button>
            <button id="concluir-orcamento" class="primario">Concluir Orçamento</button>
            <button id="voltar-analise" class="primario">Voltar para Análise</button>
            <button id="aprovar" class="primario">Aprovar</button>
            <button id="finalizar" class="primario">Finalizar</button>
            <button id="reenviar-finalizacao" class="primario">Reenviar</button>
            <button id="concluir" class="primario">Concluir saída e pagamento</button>

        </div>
    </div>

    <div class="tela moda" id="rejeitar">
        <div>
            <button class="close back"></button>
            <h2>Rejeitar Solicitação</h2>
            <form id="form-rejeicao">
                <input type="hidden" name="hash" value="<?= $hash ?>">
                <div class="campos">
                    <div class="campo">
                        <div class="rotulo">
                            <label>Qual é o motivo?</label>
                        </div>
                        <div class="controle checks">
                            <?php foreach (MotivoRejeicao::cases() as $m): ?>
                                <label>
                                    <input type="radio" name="motivo-rejeicao" value="<?= $m->value ?>">
                                    <?= $m->label() ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="campo">
                        <br>
                        <hr>
                        <br>
                    </div>
                    <div class="campo">
                        <div class="controle checks">
                            <label>
                                <input type="checkbox" name="notificar-cliente" checked>
                                Notificar cliente
                            </label>
                        </div>
                    </div>
                </div>
            </form>
            <div class="botoes">
                <button class="perigo" id="rejeita-solicitacao">Rejeitar Solicitação</button>
            </div>
        </div>
    </div>

    <div class="tela moda" id="entrada">
        <div>
            <button class="back close"></button>
            <h2>Dar entrada</h2>
            <form>
                <input type="hidden" name="hash" value="<?= $hash ?>">
                <div class="campos">
                    <div class="campo">
                        <div class="rotulo">
                            <label for="km">KM</label>
                        </div>
                        <div class="controle">
                            <input type="number" id="km" name="km" min="0" required>
                        </div>
                        <div class="mensagem"></div>
                    </div>
                    <div class="campo">
                        <div class="rotulo">
                            <label>Nível Tanque</label>
                        </div>
                        <div class="controle checks">
                            <?php foreach (NivelTanque::cases() as $n): ?>
                                <label>
                                    <input type="radio" name="nivel-tanque" value="<?= $n->value ?>" required>
                                    <?= $n->label() ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="botoes">
                    <button class="primario" id="confirma-entrada">
                        Confirmar entrada
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="tela moda" id="agendamento">
        <div>
            <button class="back close"></button>
            <h2>Agendar</h2>
            <form>
                <input type="hidden" name="hash" value="<?= $hash ?>">
                <div class="campos">
                    <div class="campo dia">
                        <div class="rotulo">
                            <label>Dia</label>
                        </div>
                        <div class="controle checks" id="datas-agendamento"></div>
                        <div class="mensagem"></div>
                    </div>
                    <div class="campo horario">
                        <div class="rotulo">
                            <label for="horario">Horário</label>
                        </div>
                        <div class="controle">
                            <select id="horario" name="horario" required></select>
                        </div>
                        <div class="mensagem"></div>
                    </div>
                    <div class="campo">
                        <br>
                        <hr>
                        <br>
                    </div>
                    <div class="campo">
                        <div class="controle checks">
                            <label>
                                <input type="checkbox" name="notificar-cliente" checked>
                                Notificar cliente
                            </label>
                        </div>
                    </div>
                </div>
                <div class="botoes">
                    <button class="primario" id="confirmar-agendamento">Confirmar agendamento</button>
                </div>
            </form>
        </div>
    </div>

    <div class="tela moda" id="os-itens">
        <div>
            <input type="text" id="search-os-item" placeholder="Pesquisar produto, serviço ou categoria">
            <div class="resultados">
                <?= str_repeat('txt ', 0) ?>
            </div>
            <div class="botoes">
                <button class="back primario">OK</button>
            </div>
        </div>
    </div>

    <div class="tela moda" id="novo-produto-servico">
        <div>
            <button class="close back"></button>
            <h2>Cadastrar novo</h2>
            <br>
            <form>
                <input type="hidden" name="hash" value="<?= $hash ?>">
                <div class="campos">
                    <div class="campo">
                        <div class="rotulo">
                            <label for="novo-produto-servico-nome">Nome</label>
                        </div>
                        <div class="controle">
                            <input type="text" id="novo-produto-servico-nome" name="nome" required>
                        </div>
                        <div class="mensagem"></div>
                    </div>
                    <div class="campo">
                        <div class="rotulo"></div>
                        <div class="controle checks">
                            <label>
                                <input type="radio"
                                       name="novo-produto-servico-tipo"
                                       value="<?= OsItemTipo::PRODUTO->value ?>"
                                       checked>
                                <?= OsItemTipo::PRODUTO->label() ?>
                            </label>
                            <label>
                                <input type="radio"
                                       name="novo-produto-servico-tipo"
                                       value="<?= OsItemTipo::SERVICO->value ?>">
                                <?= OsItemTipo::SERVICO->label() ?>
                            </label>
                        </div>
                    </div>
                    <div class="campo" id="campo-novo-produto-categoria">
                        <div class="rotulo">
                            <label for="novo-produto-categoria">Categoria</label>
                        </div>
                        <div class="controle">
                            <select id="novo-produto-categoria" name="categoria-produto">
                                <option value=""></option>
                                <?php foreach ($categoriasProdutos as $c): ?>
                                    <option value="<?= $c['codigo'] ?>"><?= e($c['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mensagem"></div>
                    </div>
                    <div class="campo" id="campo-novo-servico-categoria">
                        <div class="rotulo">
                            <label for="novo-servico-categoria">Categoria</label>
                        </div>
                        <div class="controle">
                            <select id="novo-servico-categoria" name="categoria-servico">
                                <option value=""></option>
                                <?php foreach ($categoriasServicos as $c): ?>
                                    <option value="<?= $c['codigo'] ?>"><?= e($c['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mensagem"></div>
                    </div>
                </div>
                <div class="botoes">
                    <button class="primario" id="cadastra-novo-produto-servico">Cadastrar novo</button>
                </div>
            </form>
        </div>
    </div>

<?php
$template->fimMain() ?>

<?php
$template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'

      const status = '<?= $os->status->value ?>'

      const celular = '<?= $cliente->celular ?? null ?>'

      const orcamentoTravado = <?= $os->orcamentoTravado ? 'true' : 'false' ?>

    </script>
    <script type="application/json" class="tipos-codigos"><?= json_encode($tiposCodigos) ?></script>
    <script type="module" src="os-oficina.js?v=16"></script>
<?php
$template->fimJs() ?>

<?php
$template->renderiza() ?>