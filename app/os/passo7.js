import { registraAll, reportaInvalidos } from '../../core/templates/gaido/js/forms.js'
import { send, whatsAppLinkOpen } from '../../core/templates/gaido/js/whatsapp.js'
import { saveStep } from './passos.js'

const enviaMensagem = () => {
  const f = document.querySelector('form')
  if (reportaInvalidos(f)) return
  const mensagem = `Código de validação: ${token}`
  send(destinatario, mensagem)
  // whatsAppLinkOpen(destinatario, mensagem)
  fetch('../validacao-whatsapp/envia.php').
    then(res => res.json()).
    then(r => {
      console.log(r)
      saveStep().then()
    })
}

const checaValidacao = async e => {
  clearTimeout(timer)
  const pars = new URLSearchParams({ token: token, os_hash: hash })
  const res = await fetch('checa-validacao.php?' + pars)
  const r = await res.json()
  if (r.validado) {
    const pars = new URLSearchParams(window.location.search)
    if (pars.has('edit')) {
      history.back()
    } else {
      location.replace('os.php?home=1&h=' + hash)
    }
    return
  }
  timer = setTimeout(checaValidacao, 2000)
}

let timer

registraAll()
document.querySelector('#wrapper-enviar button').addEventListener('click', enviaMensagem)
document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    checaValidacao().then()
  } else {
    clearTimeout(timer)
  }
})
if (token != '') {
  checaValidacao().then()
} else {
  const pars = new URLSearchParams(window.location.search)
  if (pars.has('edit')) {
    history.back()
  } else {
    document.querySelector('form').classList.add('hidden')
    location.replace('os.php?home=1&h=' + hash)
  }
}