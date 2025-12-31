<?php

use app\os\OsViewModel;
use modelo\Os;
use modelo\OsItem;
use modelo\OsStatus;
use modelo\Usuario;
use modelo\Veiculo;
use modelo\Workspace;

/**
 * @var Os $os
 * @var Workspace $ws
 * @var Usuario $dono
 * @var Veiculo $veiculo
 * @var OsItem[] $itensProduto
 * @var OsItem[] $itensServico
 * @var OsViewModel $osVM
 */

?>

<?php $template = new templates\Gaido() ?>

<?php $template->iniCss() ?>
    <link rel="stylesheet" href="os-cliente.css?v=2">
<?php $template->fimCss() ?>

<?php $template->iniMain() ?>

    <header>
        <div class="banner"><?= e($ws->nome) ?></div>
    </header>
    <div class="ctn">
        <section>
            <h1>
                OS #<?= $os->codigo ?>
            </h1>
            <p class="c f">
                <span class="status <?= $os->status->value ?>"><?= $os->status->label() ?></span>
            </p>
            <?php if ($os->status == OsStatus::PENDENTE_MODERACAO || $os->status == OsStatus::SOLICITADA): ?>
                <p class="c f">
                    Solicitação enviada. Aguarde pelo nosso retorno.
                </p>
            <?php elseif ($os->status == OsStatus::AGENDADA): ?>
                <p class="c f">
                    <?= $osVM->previsaoEntrada ?>
                </p>
            <?php elseif ($os->status == OsStatus::ANALISE): ?>
                <p class="c f">
                    Avisaremos assim que o orçamento estiver completo.
                </p>
            <?php elseif ($os->status == OsStatus::AGUARDANDO_APROVACAO): ?>
                <p class="c f">
                    Já levantamos todas as peças e serviços necessários para o reparo.
                </p>
                <p class="c f">
                    Aguardamos sua aprovação para iniciar o serviço imediatamente.
                </p>
            <?php elseif ($os->status == OsStatus::EM_ANDAMENTO): ?>
                <?php if ($os->previsaoEntrega): ?>
                    <p class="c f">
                        Seu veículo deve ficar pronto em:
                        <br>
                        <?= $osVM->previsaoEntrega ?>
                    </p>

                <?php else: ?>
                    <p class="c f">
                        Tudo certo por aqui. Estamos trabalhando no seu veículo.
                    </p>
                <?php endif; ?>
            <?php elseif ($os->status == OsStatus::FINALIZADA): ?>
                <p class="c f">
                    Tudo testado e aprovado. Aguardando sua retirada.
                </p>
            <?php elseif ($os->status == OsStatus::CONCLUIDA): ?>
                <p class="c f">
                    Missão cumprida! Dirija com segurança e conte sempre com a gente.
                </p>
            <?php elseif ($os->status == OsStatus::REJEITADA): ?>
                <p class="c f">
                    <?= $os->motivoRejeicao->mensagemCliente() ?>
                </p>
            <?php endif; ?>

        </section>
        <?php if ($osVM->podeEditarProblema): ?>
            <div class="botoes" style="margin: 0.5rem auto 1rem auto">
                <a class="button" id="editar-informacoes">Editar informações</a>
            </div>
        <?php endif; ?>
        <?php if ($osVM->temEstimativa): ?>
            <section id="orcamento">
                <h2>Estimativa Preliminar</h2>
                <p>
                    Requer diagnóstico presencial.
                </p>
                <br>
                <div class="resumo">
                    <div class="linha">
                        <div class="label">Produtos</div>
                        <div class="value">R$<?= $produtosH ?></div>
                    </div>
                    <div class="linha">
                        <div class="label">Serviços</div>
                        <div class="value">R$<?= $servicosH ?></div>
                    </div>
                    <?php if ($os->desconto): ?>
                        <div class="linha">
                            <div class="label">Desconto</div>
                            <div class="value">R$<?= $os->descontoH ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="linha maior">
                        <div class="label">Total estimado</div>
                        <div class="value">R$<?= $os->valorH ?></div>
                    </div>
                </div>
            </section>
        <?php elseif ($osVM->temOrcamento): ?>
            <section id="orcamento">
                <?php if ($os->status == OsStatus::AGUARDANDO_APROVACAO): ?>
                    <h2>Diagnóstico Concluído</h2>
                    <p>
                        Orçamento válido por 7 dias.
                    </p>
                <?php else: ?>
                    <h2>Orçamento</h2>
                <?php endif; ?>
                <br>
                <?php if ($itensProduto): ?>
                    <h3>Produtos</h3>
                <?php endif; ?>
                <div class="itens produtos">
                    <?php foreach ($itensProduto as $item): ?>
                        <div class="item">
                            <div class="nome">
                                <?= e($item->quantidade) ?> x <?= e($item->nome) ?>
                            </div>
                            <div class="subtotal">
                                <?= moeda($item->subtotal) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($itensServico): ?>
                    <br>
                    <h3>Serviços</h3>
                    <div class="itens servicos">
                        <?php foreach ($itensServico as $item): ?>
                            <div class="item">
                                <div class="nome">
                                    <?= e($item->nome) ?>
                                </div>
                                <div class="subtotal">
                                    <?= moeda($item->subtotal) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <br>
                <hr>
                <br>
                <div class="resumo">
                    <div class="linha">
                        <div class="label">Produtos</div>
                        <div class="value">R$<?= $produtosH ?></div>
                    </div>
                    <div class="linha">
                        <div class="label">Serviços</div>
                        <div class="value">R$<?= $servicosH ?></div>
                    </div>
                    <?php if ($os->desconto): ?>
                        <div class="linha">
                            <div class="label">Desconto</div>
                            <div class="value">R$<?= $os->descontoH ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="linha maior">
                        <div class="label">Total orçamento</div>
                        <div class="value">R$<?= $os->valorH ?></div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <section class="d2">
            <h1>
                <img class="tipo" src="<?= $veiculo->tipo->value ?>.png">
            </h1>
            <p class="c">
                <?= $veiculo->marca ?>
            </p>
            <p class="c">
                <?= $veiculo->modelo ?>
            </p>
            <p class="c">
                <?= $veiculo->ano ?>
            </p>
            <p class="c">
                <?= $veiculo->placa ?>
            </p>
        </section>
        <section class="d2">
            <table>
                <tbody>
                <tr>
                    <th>Tipo</th>
                    <td><?= $os->problema->label() ?></td>
                </tr>
                <?php if ($os->quando): ?>
                    <tr>
                        <th>Quando ocorreu</th>
                        <td><?= $os->quando->label() ?></td>
                    </tr>
                <?php endif; ?>
                <?php if ($os->frequencia): ?>
                    <tr>
                        <th>Frequência</th>
                        <td><?= $os->frequencia->label() ?></td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </section>
        <?php if ($temTexto): ?>
            <section class="d2">
                <div class="fields">
                    <?php if ($os->sintomas): ?>
                        <div class="field">
                            <label>Sintomas</label>
                            <div>
                                <?= e($os->sintomas) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($os->condicoes): ?>
                        <div class="field">
                            <label>Condições</label>
                            <div>
                                <?= e($os->condicoes) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($os->obsCliente): ?>
                        <div class="field">
                            <label>Observações</label>
                            <div>
                                <?= e($os->obsCliente) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
        <section class="d2">
            <p>
                OS criada em <?= $osVM->criacaoH ?>
            </p>
        </section>
    </div>
<?php if ($files): ?>
    <div id="carrossel">
        <button class="left"></button>

        <?php foreach ($files as $file): ?>
            <?php if (str_contains($file['type'], 'image')): ?>
                <div>
                    <img src="<?= $file['url'] ?>" alt="<?= $file['name'] ?>">
                </div>
            <?php elseif (str_contains($file['type'], 'video')): ?>
                <div>
                    <video src="<?= $file['url'] ?>" controls></video>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <button class="right"></button>
    </div>
<?php endif; ?>


<?php if ($os->status == OsStatus::SOLICITADA): ?>
<?php elseif ($os->status == OsStatus::AGENDADA): ?>
    <div id="actions">
        <div class="botoes">
            <button id="contato">Entrar em contato</button>
            <button class="primario" id="como-chegar">Como chegar</button>
        </div>
    </div>
<?php elseif ($os->status == OsStatus::ANALISE): ?>
<?php elseif ($os->status == OsStatus::AGUARDANDO_APROVACAO): ?>
    <div id="actions">
        <div class="botoes">
            <button id="tenho-duvidas">Tenho dúvidas</button>
            <button class="primario" id="aprovar-orcamento">Aprovar orçamento</button>
        </div>
    </div>
<?php endif; ?>

<?php if ($os->status == OsStatus::SOLICITADA || $os->status == OsStatus::AGENDADA): ?>
    <div class="botoes">
        <button class="danger" id="cancelar">Cancelar solicitação</button>
    </div>
<?php endif; ?>

    <div class="tela moda" id="aprovacao-orcamento">
        <div>
            <h2>Confirmar Aprovação?</h2>
            <button class="back close"></button>
            <br>
            <p>
                O valor total é <b>R$ <?= $os->valorH ?></b>.
            </p>
            <p>
                Ao confirmar, iniciaremos o serviço imediatamente.
            </p>
            <br>
            <div class="botoes">
                <button class="back">Cancelar</button>
                <button class="primario" id="confirma-orcamento">Confirmar e Iniciar</button>
            </div>
        </div>
    </div>

<?php $template->fimMain() ?>

<?php $template->iniJs() ?>
    <script>
      const hash = '<?= $hash ?>'
      const whatsAppOficina = '<?= $dono->celularRaw ?>'
      const linkMaps = '<?= $linkMaps ?>'
      const totalOrcamento = '<?= $os->valorH ?>'
      const textoTenhoDuvidas =
        `Tenho dúvidas sobre o orçamento *#<?= $os->codigo ?>*, no valor de *R$<?= $os->valorH ?>*.`
    </script>
    <script type="module" src="os-cliente.js?v=4"></script>
<?php $template->fimJs() ?>

<?php $template->renderiza() ?>