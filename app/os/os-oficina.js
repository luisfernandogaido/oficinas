import { addReloadPersisted, isTouch, updateChecks } from '../../core/templates/gaido/js/gaido.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'
import { send, Sender } from '../../core/templates/gaido/js/whatsapp.js'
import { registraAll, reportaInvalidos } from '../../core/templates/gaido/js/forms.js'
import { e, moeda } from '../../core/templates/gaido/js/texto.js'
import { previsao } from '../../core/templates/gaido/js/date.js'

const update = () => {
  updatePorStatus()
  updatePorAbaAtiva()
  updatePickedOsItens()
  updateOrcamento()
  updateFormNovoProdutoServico()
  updateOrcamentoTravado()
  updateExecucao()
}

const updateOrcamento = () => {
  const itens = document.querySelectorAll('#orcamento .itens .item')
  let total = 0
  itens.forEach(item => {
    const quantidade = parseFloat(item.querySelector('input[name="quantidade"]').value)
    const preco = parseFloat(item.querySelector('input[name="preco"]').value)
    const subtotal = quantidade * preco
    if (!isNaN(subtotal)) total += subtotal
    item.querySelector('div.subtotal').textContent = moeda(subtotal)
    document.querySelector('#orcamento .totais span').textContent = moeda(total)
  })
  updateOrcamentoTravado()
}

const updateFormNovoProdutoServico = () => {
  const tipo = document.querySelector('input[name="novo-produto-servico-tipo"]:checked').value
  if (tipo == 'produto') {
    document.querySelector('#campo-novo-produto-categoria').classList.remove('hidden')
    document.querySelector('#campo-novo-produto-categoria select').required = true
    document.querySelector('#campo-novo-servico-categoria').classList.add('hidden')
    document.querySelector('#campo-novo-servico-categoria select').required = false
  } else {
    document.querySelector('#campo-novo-produto-categoria').classList.add('hidden')
    document.querySelector('#campo-novo-produto-categoria select').required = false
    document.querySelector('#campo-novo-servico-categoria').classList.remove('hidden')
    document.querySelector('#campo-novo-servico-categoria select').required = true
  }
}

const updatePorAbaAtiva = () => {
  const painelAtivo = document.querySelector('#abas section div.active')
  if (painelAtivo) painelAtivo.classList.remove('active')
  const act = document.querySelector('#abas nav button.active')
  if (act) document.querySelector(`#${act.dataset.target}`).classList.add('active')
  asyncOnFocusTab()
}

//concentra eventos assíncronos que devem ser disparados ao focar numa aba.
//evita que evento assíncronos recém disparados sejam enviados duplicadamente ao servidor
//por questões de repetição de chamadas indesejadas na lógica da construção da tela.
const asyncOnFocusTab = () => {
  const aba = document.querySelector('#abas nav button.active')?.dataset.target
  if (mapAsyncs.has(aba)) {
    const lastDate = mapAsyncs.get(aba)
    const delta = new Date() - lastDate
    if (delta < 125) {
      console.log('asyncOnFocusTab prevent', aba)
      return
    }
  }
  mapAsyncs.set(aba, new Date())
  switch (aba) {
    case 'orcamento':
      loadItens().then()
      break
    case 'resumo':
      loadResumo().then()
      break
  }
}

const updatePorStatus = () => {
  switch (status) {
    case 'solicitada':
      mostraActions(['dar-entrada', 'agendar'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Estimativa'
      document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.remove('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Agendar'
      document.querySelector('#agendamento h2').textContent = 'Agendar'
      break
    case 'rejeitada':
      mostraActions([])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Estimativa'
      document.querySelector('button[data-target="orcamento"]').classList.add('hidden')
      document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      document.querySelector('button[data-target="resumo"]').classList.add('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Agendar'
      document.querySelector('#agendamento h2').textContent = 'Agendar'
      break
    case 'analise':
      mostraActions(['cancelar-devolver', 'concluir-orcamento'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Estimativa'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Agendar'
      document.querySelector('#agendamento h2').textContent = 'Agendar'
      break
    case 'agendada':
      mostraActions(['agendar', 'dar-entrada'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Estimativa'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.remove('hidden')
      document.querySelector('#agendar').textContent = 'Reagendar'
      document.querySelector('#agendamento h2').textContent = 'Reagendar'
      break
    case 'aguardando_aprovacao':
      mostraActions(['voltar-analise', 'aprovar'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Orçamento'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Reagendar'
      document.querySelector('#agendamento h2').textContent = 'Reagendar'
      break
    case 'em_andamento':
      mostraActions(['voltar-analise', 'finalizar'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Orçamento'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      document.querySelector('button[data-target="execucao"]').classList.remove('hidden')
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Reagendar'
      document.querySelector('#agendamento h2').textContent = 'Reagendar'
      break
    case 'finalizada':
      mostraActions(['reenviar-finalizacao', 'concluir'])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Orçamento'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      if (document.querySelector('#previsao-entrega').value) {
        document.querySelector('button[data-target="execucao"]').classList.remove('hidden')
      } else {
        document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      }
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Reagendar'
      document.querySelector('#agendamento h2').textContent = 'Reagendar'
      break
    case 'concluida':
      mostraActions([])
      document.querySelector('button[data-target="orcamento"]').textContent = 'Orçamento'
      document.querySelector('button[data-target="orcamento"]').classList.remove('hidden')
      if (document.querySelector('#previsao-entrega').value) {
        document.querySelector('button[data-target="execucao"]').classList.remove('hidden')
      } else {
        document.querySelector('button[data-target="execucao"]').classList.add('hidden')
      }
      document.querySelector('button[data-target="resumo"]').classList.remove('hidden')
      document.querySelector('#rejeitar-solicitacao').classList.add('hidden')
      document.querySelector('#cancelar-agendamento').classList.add('hidden')
      document.querySelector('#agendar').textContent = 'Reagendar'
      document.querySelector('#agendamento h2').textContent = 'Reagendar'
      break
    default:
      abre(`status ${status} não implementado em updatePorStatus.`, 10, 'OK')
  }
  if (status == 'analise') {
    document.querySelector('#rejeitar h2').textContent = 'Cancelar e Devolver'
  } else {
    document.querySelector('#rejeitar h2').textContent = 'Rejeitar Solicitação'
  }
}

const updatePickedOsItens = () => {
  document.querySelectorAll('#os-itens .cards .card').forEach(card => {
    card.classList.toggle('checked', pickedOsItens.has(card.dataset.tipoCodigo))
  })
}

const updateOrcamentoTravado = () => {
  if (!orcamentoTravado) return
  if (document.querySelector('#desconto')) {
    document.querySelector('#desconto').readOnly = true
  }
  document.querySelectorAll('#abas #orcamento.travado .itens .item input').forEach(input => input.readOnly = true)
}

const updateExecucao = () => {
  if (document.querySelector('#previsao-entrega').value) {
    document.querySelector('#remove-previsao-entrega').classList.remove('hidden')
    document.querySelector('#sp-previsao-entrega').classList.remove('hidden')
    document.querySelectorAll('.campos > .campo .controle.checks.data-previsao-entrega label').
      forEach(input => input.classList.add('hidden'))
    document.querySelectorAll('.campos > .campo .controle.checks.hora-previsao-entrega label').
      forEach(input => input.classList.add('hidden'))
    document.querySelector('#sp-previsao-entrega').textContent =
      previsao(document.querySelector('#previsao-entrega').value)
  } else {
    document.querySelector('#remove-previsao-entrega').classList.add('hidden')
    document.querySelector('#sp-previsao-entrega').classList.add('hidden')
    document.querySelectorAll('.campos > .campo .controle.checks.data-previsao-entrega label').
      forEach(input => input.classList.remove('hidden'))
    document.querySelectorAll('.campos > .campo .controle.checks.hora-previsao-entrega label').
      forEach(input => input.classList.remove('hidden'))
    document.querySelector('#sp-previsao-entrega').textContent = ''
  }
  if (status != 'em_andamento') {
    document.querySelector('#remove-previsao-entrega').classList.add('hidden')
  }
}

const mostraActions = ids => {
  if (ids.length == 0) {
    document.querySelector('#actions').classList.add('hidden')
    return
  }
  const lastIndex = ids.length - 1
  document.querySelectorAll('#actions .botoes button').forEach(button => {
    button.classList.toggle('show', ids.includes(button.id))
    const index = ids.indexOf(button.id)
    if (button.classList.contains('show')) {
      button.style.order = String(index)
      button.classList.toggle('primario', index == lastIndex)
    }
  })
}

const navAba = e => {
  const aba = e.currentTarget
  let act = document.querySelector('#abas nav button.active')
  if (act) act.classList.remove('active')
  aba.classList.add('active')
  aba.blur()
  if (!matchMedia('(min-width: 768px)').matches) {
    aba.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' })
  }
  document.documentElement.scrollTo({ top: 0 })
  const url = new URL(window.location)
  url.searchParams.set('tab', aba.dataset.target)
  history.replaceState(null, '', url)
  update()
}

const scrollToLeft = () => {
  const carrossel = document.querySelector('#carrossel')
  const scrollAmmount = carrossel.clientWidth * 0.9
  carrossel.scrollBy({ left: -scrollAmmount, behavior: 'smooth' })
}

const scrollToRight = () => {
  const carrossel = document.querySelector('#carrossel')
  const scrollAmmount = carrossel.clientWidth * 0.9
  carrossel.scrollBy({ left: scrollAmmount, behavior: 'smooth' })
}

const consideraBotoesCarrossel = e => {
  if (isTouch()) {
    document.body.classList.add('sem-botoes')
  }
  setTimeout(() => {
    const carrossel = document.querySelector('#carrossel')
    if (carrossel && carrossel.scrollWidth <= carrossel.clientWidth) {
      document.body.classList.add('sem-botoes')
    }
  }, 100)
}

const focaAbaPorUrl = () => {
  const url = new URL(window.location)
  if (url.searchParams.get('tab') !== '') {
    const button = document.querySelector(`#abas nav button[data-target="${url.searchParams.get('tab')}"]`)
    if (button) button.click()
  }
}

const rejeitarSolicitacao = () => {
  location.hash = '#rejeitar'
}

const rejeitaSolicitacao = async () => {
  const motivo = document.querySelector('#rejeitar input[name="motivo-rejeicao"]:checked')?.value
  if (!motivo) {
    abre('Informe um motivo.', 10, 'OK')
    return
  }
  let sender
  const notificar = document.querySelector('#form-rejeicao input[name="notificar-cliente"]').checked
  if (notificar) sender = new Sender()
  try {
    const body = new FormData(document.querySelector('#rejeitar form'))
    const res = await fetch('actions/rejeita.php', { method: 'POST', body: body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      sender?.close()
      return
    }
    if (notificar) sender.send(r.telefone, r.mensagem)
    history.go(-2) //
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    if (notificar) sender?.close()
    abre(e, 10, 'OK')
  }
}

const darEntrada = () => {
  location.hash = '#entrada'
}

const confirmaEntrada = async () => {
  try {
    const f = document.querySelector('#entrada form')
    if (reportaInvalidos(f)) return
    const nivelTanque = document.querySelector('#entrada input[name="nivel-tanque"]:checked')?.value
    if (!nivelTanque) {
      abre('Informe o nível do tanque.', 10, 'OK')
      return
    }
    const body = new FormData(f)
    const res = await fetch('actions/confirma-entrada.php', { method: 'POST', body: body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    history.back()
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const agendar = async () => {
  await carregarOpcoesAgendamento()
  location.hash = '#agendamento'
  document.querySelector('#horario').value = ''
}

const renderizaHorarios = () => {
  const diaSelecionado = document.querySelector('input[name="data-agendamento"]:checked')
  const hoje = diaSelecionado?.dataset?.eHoje == 'true'
  const select = document.querySelector('#horario')
  const oldValue = select.value
  select.innerHTML = '<option value=""></option>'
  opcoesAgendamento.horarios.forEach(horario => {
    if (hoje && !horario.disponivel_se_hoje) return
    select.insertAdjacentHTML('beforeend', `<option value="${horario.horario}">${horario.horario}</option>`)
  })
  if (select.querySelector(`option[value="${oldValue}"]`)) select.value = oldValue
}

const carregarOpcoesAgendamento = async () => {
  const res = await fetch('actions/opcoes-agendamento.php')
  opcoesAgendamento = await res.json()
  const divDatas = document.querySelector('#datas-agendamento')
  divDatas.innerHTML = ''
  opcoesAgendamento.dias.forEach(dia => {
    //language=html
    divDatas.insertAdjacentHTML('beforeend', `
        <label>
            <input type="radio" name="data-agendamento" value="${e(dia.data)}" data-e-hoje="${e(dia.e_hoje)}" required>
            ${e(dia.rotulo)}
        </label>
    `)
  })
  divDatas.querySelectorAll('input').forEach(input => input.addEventListener('change', renderizaHorarios))
  renderizaHorarios()
}

const agenda = async () => {
  let sender
  const notificar = document.querySelector('#agendamento input[name="notificar-cliente"]').checked
  if (notificar) sender = new Sender()
  try {
    const f = document.querySelector('#agendamento form')
    if (reportaInvalidos(f)) {
      if (notificar) sender?.close()
      return
    }
    const body = new FormData(f)
    const res = await fetch('actions/agenda.php', { method: 'POST', body: body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      sender?.close()
      return
    }
    if (notificar) sender.send(r.telefone, r.mensagem)
    history.back()
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
    if (notificar) sender?.close()
  }
}

const focaAbaAoAbrir = () => {
  const url = new URL(window.location.href)
  if (url.searchParams.get('tab')) return
  switch (status) {
    case 'analise':
      document.querySelector('#abas button[data-target="orcamento"]').click()
      break
    case 'finalizada':
      document.querySelector('#abas button[data-target="resumo"]').click()
      break
  }
}

const adicionarItens = () => {
  location.hash = '#os-itens'
  setTimeout(() => {
    if (!isTouch()) {
      document.querySelector('#search-os-item').focus()
    }
    searchOsItem().then()
  }, 25)
}

const teclaSearchOsItem = e => {
  const input = e.currentTarget
  let currentHover = document.querySelector('#os-itens .card.hover')
  const availableCards = document.querySelectorAll('#os-itens .card')
  switch (e.key) {
    case 'Escape':
      if (input.value) {
        input.value = ''
        e.stopPropagation()
        searchOsItem().then()
      }
      break
    case 'ArrowDown':
      e.preventDefault()
      if (!availableCards.length) return
      if (!currentHover) currentHover = availableCards[availableCards.length - 1]
      currentHover.classList.remove('hover')
      const next = currentHover.nextElementSibling
      if (next) {
        currentHover = next
      } else {
        currentHover = availableCards[0]
      }
      currentHover.classList.add('hover')
      currentHover.scrollIntoView({ block: 'center' })
      break
    case 'ArrowUp':
      e.preventDefault()
      if (!availableCards.length) return
      if (!currentHover) currentHover = availableCards[0]
      currentHover.classList.remove('hover')
      const previous = currentHover.previousElementSibling
      if (previous) {
        currentHover = previous
      } else {
        currentHover = availableCards[availableCards.length - 1]
      }
      currentHover.classList.add('hover')
      currentHover.scrollIntoView({ block: 'center' })
      break
    case 'Enter':
      if (currentHover) pickOsItem(currentHover)
      break
    default:
      console.log('teclaSearchOsItem', e.key)
  }
}

const inputSearchOsItem = () => {
  clearTimeout(timer)
  timer = setTimeout(searchOsItem, 125)
}

const searchOsItem = async () => {
  try {
    clearTimeout(timer)
    const pars = new URLSearchParams({
      hash: hash,
      text: document.querySelector('#search-os-item').value,
    })
    const res = await fetch('actions/search-os-item.php?' + pars)
    const html = await res.text()
    const pars2 = new URLSearchParams({
      hash: hash,
      text: document.querySelector('#search-os-item').value,
    })
    if (pars.toString() != pars2.toString()) return
    const ctn = document.querySelector('#os-itens .resultados')
    ctn.innerHTML = html
    //parece que eu não preciso mais desses caras, mesmo
    ctn.querySelectorAll('.card').forEach(card => {
      card.addEventListener('click', e => pickOsItem(e.currentTarget))
    })
    document.querySelector('#cadastrar-produco-servico')?.addEventListener('click', cadastrarProdutoServico)
    update()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const pickOsItem = async card => {
  const tipoCodigo = card.dataset.tipoCodigo
  if (pickedOsItens.has(tipoCodigo)) {
    console.log('pickOsItem', tipoCodigo)
    pickedOsItens.delete(tipoCodigo)
    card.classList.remove('checked')
  } else {
    pickedOsItens.add(tipoCodigo)
    card.classList.add('checked')
  }
  const pars = new URLSearchParams({
    hash: hash,
    tipo: card.dataset.tipo,
    codigo: card.dataset.codigo,
    checked: card.classList.contains('checked'),
  })
  const res = await fetch('actions/pick-os-item.php', { method: 'POST', body: pars })
  const r = await res.json()
  console.log(r)
  if (r.erro) {
    abre(r.erro, 10, 'OK')
    return
  }
  await loadItens()
}

const loadItens = async () => {
  try {
    const ctn = document.querySelector('#orcamento .resultado')
    const res = await fetch('actions/load-itens.php?hash=' + hash)
    const html = await res.text()
    ctn.innerHTML = html
    ctn.querySelectorAll('button.delete').forEach(button => {
      button.addEventListener('click', removeItem)
    })
    ctn.querySelectorAll('input[type=number]').forEach(input => {
      input.addEventListener('focus', focaInputNumber)
    })
    ctn.querySelectorAll('input[type=number]').forEach(input => {
      input.addEventListener('input', inputItemNumber)
    })
    updateOrcamento()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const loadResumo = async () => {
  try {
    const ctn = document.querySelector('#resumo')
    const res = await fetch('actions/load-resumo.php?hash=' + hash)
    const html = await res.text()
    ctn.innerHTML = html
    ctn.querySelectorAll('input[type=number]').forEach(input => {
      input.addEventListener('focus', focaInputNumber)
      input.addEventListener('input', inputDesconto)
    })
    document.querySelector('#enviar-orcamento')?.addEventListener('click', enviarEstimativa)
    document.querySelector('#reabrir')?.addEventListener('click', reabre)
    update()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const focaInputNumber = e => {
  const input = e.currentTarget
  if (input.readOnly) return
  input.select()
  setTimeout(() => {
    input.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }, 125)
}

const inputItemNumber = e => {
  clearTimeout(timer)
  const item = e.currentTarget.closest('.item')
  const quantidade = item.querySelector('input[name="quantidade"]')
  const preco = item.querySelector('input[name="preco"]')
  updateOrcamento()
  if (!quantidade.validity.valid) return
  if (!preco.validity.valid) return
  timer = setTimeout(() => alteraItem(item), 500)
}

const alteraItem = async item => {
  const quantidade = item.querySelector('input[name="quantidade"]')
  const preco = item.querySelector('input[name="preco"]')
  const pars = new URLSearchParams({
    hash: hash,
    codigo: item.dataset.codigo,
    quantidade: quantidade.value,
    preco: preco.value,
  })
  const res = await fetch('actions/altera-item.php', { method: 'POST', body: pars })
  const r = await res.json()
  if (r.erro) {
    abre(r.erro, 10, 'OK')
    return
  }
}

const removeItem = async e => {
  try {
    const item = e.currentTarget.closest('.item')
    const pars = new URLSearchParams({
      hash: hash,
      codigo: item.dataset.codigo,
    })
    const res = await fetch('actions/remove-item.php', { method: 'POST', body: pars })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    item.remove()
    const tipoCodigo = item.dataset.tipoCodigo
    console.log('removeItem', tipoCodigo)
    pickedOsItens.delete(tipoCodigo)
    update()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const cadastrarProdutoServico = () => {
  location.hash = '#novo-produto-servico'
  document.querySelector('#novo-produto-servico-nome').value = document.querySelector('#search-os-item').value
}

const cadastraNovoProdutoServico = async () => {
  try {
    const f = document.querySelector('#novo-produto-servico form')
    if (reportaInvalidos(f)) return
    const res = await fetch('actions/cadastra-novo-produto-servico.php', { method: 'POST', body: new FormData(f) })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    pickedOsItens.add(r.tipo_codigo)
    history.go(-2)
    await loadItens()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const inputDesconto = () => {
  clearTimeout(timer)
  timer = setTimeout(() => alteraDesconto(), 500)
}

const alteraDesconto = async () => {
  const desconto = document.querySelector('#desconto')
  const pars = new URLSearchParams({
    hash: hash,
    desconto: desconto.value,
  })
  const res = await fetch('actions/altera-desconto.php', { method: 'POST', body: pars })
  const r = await res.json()
  if (r.erro) {
    abre(r.erro, 10, 'OK')
    desconto.value = r.desconto
    desconto.select()
    return
  }
  document.querySelector('#resumo .valor-h').textContent = r.valor_h
  console.log(r)
}

const enviarEstimativa = async () => {
  const sender = new Sender()
  try {
    const pars = new URLSearchParams({
      hash: hash,
      orcamento: status == 'analise' || status == 'aguardando_aprovacao',
    })
    const res = await fetch(`actions/enviar-estimativa.php?${pars}`)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      sender.close()
      return
    }
    sender.send(r.numero, r.mensagem)
    setTimeout(() => location.reload(), 1000)
  } catch (e) {
    abre(e, 10, 'OK')
    sender.close()
  }
}

const cancelaAgendamento = async () => {
  try {
    const body = new URLSearchParams({
      hash,
    })
    const res = await fetch('actions/cancela-agendamento.php', { method: 'POST', body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    setTimeout(() => location.reload(), 25)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const toWhatsAppGenerico = () => send(celular)

const concluiOrcamento = async e => {
  await enviarEstimativa()
}

const aprova = async () => {
  try {
    const res = await fetch('actions/aprova.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const voltaAnalise = async () => {
  try {

    const url = new URL(window.location.href)
    url.searchParams.delete('tab')
    history.replaceState(null, null, url.toString())
    if (status == 'em_andamento') {
      const mensagem = 'Ocorreu um imprevisto com sua OS. Ela precisou retornar para análise.'
      send(celular, mensagem)
    }
    const res = await fetch('actions/volta-analise.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    history.replaceState(null, null, url.toString())
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const finaliza = async () => {
  const sender = new Sender()
  try {
    const res = await fetch('actions/finaliza.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      sender.close()
      return
    }
    sender.send(r.numero, r.mensagem)
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    sender.close()
    abre(e, 10, 'OK')
  }
}

const conclui = async () => {
  try {
    const res = await fetch('actions/conclui.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      sender.close()
      return
    }
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const removePrevisaoEntrega = () => {
  document.querySelector('#previsao-entrega').value = ''
  estimaPrevisaoEntrega().then()
}

const estimaPrevisaoEntrega = async () => {
  try {
    const pars = new URLSearchParams({
      hash,
      previsao: document.querySelector('#previsao-entrega').value,
    })
    const res = await fetch(`actions/estima-previsao-entrega.php`, { method: 'POST', body: pars })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    updateExecucao()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const mudaPrevisaoEntrega = () => {
  try {
    const data = document.querySelector('input[name="data-previsao-entrega"]:checked')
    const hora = document.querySelector('input[name="hora-previsao-entrega"]:checked')
    if (!data) return
    if (!hora) return
    document.querySelector('#previsao-entrega').value = `${data.value} ${hora.value}`
    hora.checked = false
    updateChecks()
    updateExecucao()
    estimaPrevisaoEntrega().then()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const reabre = async () => {
  try {
    const res = await fetch('actions/reabre.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    setTimeout(() => location.reload(), 100)
  } catch (e) {
    abre(e, 10, 'OK')
  }

}

let opcoesAgendamento
let timer
const tiposCodigos = JSON.parse(document.querySelector('script.tipos-codigos').textContent)
const pickedOsItens = new Set(tiposCodigos)
let mapAsyncs = new Map()

document.querySelector('#cabecalho .wa').addEventListener('click', toWhatsAppGenerico)
document.querySelectorAll('#abas nav button').forEach(b => {
  b.addEventListener('click', navAba)
})
document.querySelectorAll('#carrossel > div img').forEach(img => {
  img.addEventListener('click', ev => {
    window.open(ev.currentTarget.src)
  })
})
document.querySelector('#carrossel button.left')?.addEventListener('click', scrollToLeft)
document.querySelector('#carrossel button.right')?.addEventListener('click', scrollToRight)
document.querySelector('#rejeitar-solicitacao')?.addEventListener('click', rejeitarSolicitacao)
document.querySelector('#cancelar-devolver')?.addEventListener('click', rejeitarSolicitacao)
document.querySelector('#rejeita-solicitacao')?.addEventListener('click', rejeitaSolicitacao)
document.querySelector('#dar-entrada').addEventListener('click', darEntrada)
document.querySelector('#confirma-entrada').addEventListener('click', confirmaEntrada)
document.querySelector('#agendar').addEventListener('click', agendar)
document.querySelector('#confirmar-agendamento').addEventListener('click', agenda)
document.querySelector('#adicionar-itens').addEventListener('click', adicionarItens)
document.querySelector('#search-os-item').addEventListener('keydown', teclaSearchOsItem)
document.querySelector('#search-os-item').addEventListener('input', inputSearchOsItem)
document.querySelectorAll('input[name="novo-produto-servico-tipo"]').
  forEach(el => el.addEventListener('change', update))
document.querySelector('#cadastra-novo-produto-servico').addEventListener('click', cadastraNovoProdutoServico)
document.querySelector('#cancelar-agendamento').addEventListener('click', cancelaAgendamento)
document.querySelector('#concluir-orcamento').addEventListener('click', concluiOrcamento)
document.querySelector('#aprovar').addEventListener('click', aprova)
document.querySelector('#voltar-analise').addEventListener('click', voltaAnalise)
document.querySelector('#finalizar').addEventListener('click', finaliza)
document.querySelector('#reenviar-finalizacao').addEventListener('click', finaliza)
document.querySelector('#remove-previsao-entrega').addEventListener('click', removePrevisaoEntrega)
document.querySelectorAll('input[name="data-previsao-entrega"], input[name="hora-previsao-entrega"]').forEach(el => {
  el.addEventListener('click', mudaPrevisaoEntrega)
})
document.querySelector('#concluir').addEventListener('click', conclui)

addReloadPersisted()
registraAll()

consideraBotoesCarrossel()
focaAbaPorUrl()
focaAbaAoAbrir()
update()
await carregarOpcoesAgendamento().
  then(loadItens).
  then(loadResumo)

//todo remover
setTimeout(() => {
  if (SITE.includes('localhost')) {
    // adicionarItens()
    // document.querySelector('#search-os-item').value = 'aba'
  }
  searchOsItem().then()
}, 250)