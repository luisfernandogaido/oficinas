import { panelHide } from '../../core/templates/gaido/js/search-panel.js'
import { registraAll } from '../../core/templates/gaido/js/forms.js'
import { loadState, saveState, updateChecks } from '../../core/templates/gaido/js/gaido.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'
import { whatsAppLinkOpen } from '../../core/templates/gaido/js/whatsapp.js'
import * as alerta from '../../core/templates/gaido/js/alerta.js'
import { hide } from '../../core/templates/gaido/js/popover.js'

const find = async () => {
  try {
    const pars = generateParams()
    pars.set('page', page.toString())
    const res = await fetch('find.php?' + pars)
    const html = await res.text()
    const pars2 = generateParams()
    pars2.set('page', page.toString())
    if (pars.toString() != pars2.toString()) return
    document.querySelector('#resultado').innerHTML = html
    if (document.querySelector('#resultado .error')) return
    document.documentElement.scrollTop = ls()?.scroll || 0
    document.querySelector('#resultado').querySelectorAll('button.whatsapp').forEach(b => {
      b.addEventListener('click', comunicaWhatsApp)
    })
    document.querySelector('#resultado').querySelectorAll('button.share').forEach(b => {
      b.addEventListener('click', openShare)
    })
    const previousButton = document.querySelector('#resultado').querySelector('.paginacao .arrow-left')
    const nextButton = document.querySelector('#resultado').querySelector('.paginacao .arrow-right')
    previousButton?.addEventListener('click', previousPage)
    nextButton?.addEventListener('click', nextPage)
    pages = parseInt(document.querySelector('.paginacao')?.dataset.pages || 0)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const nextPage = () => {
  if (page == pages - 1) return
  document.documentElement.scrollTop = 0
  page++
  ss()
  find().then()
}

const previousPage = () => {
  if (page == 0) return
  document.documentElement.scrollTop = 0
  page--
  ss()
  find().then()
}

const generateParams = () => {
  const fd = new FormData(document.querySelector('form'))
  fd.append('search', document.querySelector('#search').value)
  return new URLSearchParams(fd)
}

const inputSearch = () => {
  clearTimeout(timer)
  document.documentElement.scrollTop = 0
  page = 0
  ss()
  timer = setTimeout(find, 500)
}

const comunicaWhatsApp = ev => {
  const tr = ev.currentTarget.closest('tr')
  const nome = tr.dataset.nome
  const celular = tr.dataset.celular
  if (celular == '55') {
    abre('Sem celular', 5, 'OK')
    return
  }
  whatsAppLinkOpen(celular, `Olá, ${nome.trim()}.`)

}

const openShare = e => {
  tr = e.currentTarget.closest('tr')
  document.querySelector('#whatsapp-share').classList.toggle('hidden', tr.dataset.celular == '55')
  document.querySelector('#email-share').classList.toggle('hidden', tr.dataset.semEmail == '1')
}

const copyShare = async () => {
  try {
    hide()
    const pars = new URLSearchParams()
    pars.set('codigo', tr.dataset.codigo)
    pars.set('tipo', 'copylink')
    const res = await fetch('share.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK')
      return
    }
    navigator.clipboard.writeText(r.link).then(() => {
      abre('Copiado!', 2)
    })
  } catch (e) {
    alerta.abre(e, 10, 'OK')
  }
}

const whatsShare = async () => {
  try {
    hide()
    if (tr.dataset.celular == '55') {
      abre('Sem celular', 5, 'OK')
      return
    }
    const pars = new URLSearchParams()
    pars.set('codigo', tr.dataset.codigo)
    pars.set('tipo', 'whats')
    const res = await fetch('share.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK')
      return
    }
    const text = 'Olá, ' + tr.dataset.nome + '. Acesse o aplicativo clicando no link ' +
      window.SITE + 'app/usuarios/token-use.php?token=' + r.token
    whatsAppLinkOpen(tr.dataset.celular, text)
  } catch (e) {
    alerta.abre(e, 10, 'OK')
  }
}

const emailShare = async () => {
  try {
    hide()
    alerta.abre('Enviando...')
    const pars = new URLSearchParams()
    pars.set('codigo', tr.dataset.codigo)
    pars.set('tipo', 'email')
    const res = await fetch('share.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK')
      return
    }
    alerta.abre('Enviado', 2)
  } catch (e) {
    alerta.abre(e, 10, 'OK')
  }
}

const ss = () => {
  const state = {
    search: document.querySelector('#search').value,
    cod_conta: document.querySelector('#cod-conta').value,
    perfil: document.querySelector('#perfil').value,
    status: document.querySelector('input[name="status"]:checked').value ?? null,
    whatsapp_validado: document.querySelector('#whatsapp-validado').checked,
    page: page,
    scroll: document.documentElement.scrollTop,
  }
  saveState('usuarios_new', state)
}

const ls = () => loadState('usuarios_new', 60 * 5)

const pesquisar = () => {
  document.documentElement.scrollTop = 0
  page = 0
  panelHide()
  ss()
  find().then()
}

const redefinir = () => {
  document.querySelector('.search-panel form').reset()
  updateChecks()
}

const tecla = e => {
  switch (e.key) {
    case 'ArrowLeft':
      previousPage()
      break
    case 'ArrowRight':
      nextPage()
      break
  }
}

const teclaSearch = e => {
  e.stopPropagation()
}

let timer
let tr
let page = 0
let pages

document.querySelector('#redefinir').addEventListener('click', redefinir)
document.querySelector('#pesquisar').addEventListener('click', pesquisar)
document.querySelector('#search').addEventListener('input', inputSearch)
document.querySelector('#search').addEventListener('keydown', teclaSearch)
document.querySelector('#copy-share').addEventListener('click', copyShare)
document.querySelector('#whatsapp-share').addEventListener('click', whatsShare)
document.querySelector('#email-share').addEventListener('click', emailShare)
window.addEventListener('beforeunload', ss)
window.addEventListener('keydown', tecla)
registraAll()
const state = ls()
if (state) {
  document.querySelector('#search').value = state.search
  document.querySelector('#cod-conta').value = state.cod_conta
  document.querySelector('#perfil').value = state.perfil
  document.querySelector(`input[name="status"][value="${state.status ?? 'ativo'}"]`).checked = true
  document.querySelector('#whatsapp-validado').checked = state.whatsapp_validado || false
  page = state.page || 0
}
window.addEventListener('pageshow', () => {
  find().then()
  updateChecks()
})
