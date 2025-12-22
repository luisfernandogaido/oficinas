import * as alerta from '../../core/templates/gaido/js/alerta.js'
import { addReloadPersisted } from '../../core/templates/gaido/js/gaido.js'

const excluir = ev => {
  l = ev.currentTarget.parentNode.parentNode
  exclui().then()
}

const exclui = async () => {
  try {
    alerta.abre('Excluindo...')
    const res = await fetch(`exclui.php?codigo=${l.dataset.codigo}`)
    const r = await res.json()
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK')
      return
    }
    l.parentNode.removeChild(l)
    alerta.abre('Excluído.', 5, 'OK')
  } catch (e) {
    alerta.abre(e, 10, 'OK')
  }
}

const alterna = async ev => {
  try {
    alerta.abre('Salvando...')
    const b = ev.currentTarget
    b.classList.toggle('checked')
    const pars = new URLSearchParams()
    pars.set('codigo', b.parentNode.parentNode.dataset.codigo)
    pars.set('ativa', b.classList.contains('checked') ? '1' : '0')
    const res = await fetch('alterna_atividade.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK')
      return
    }
    alerta.abre('Salvo', 1)
  } catch (e) {
    alerta.abre(e, 10, 'OK')
  }
}

let l

addReloadPersisted()
document.querySelectorAll('table .delete').forEach(b => {
  b.addEventListener('click', excluir)
})
document.querySelectorAll('table .check').forEach(b => {
  b.addEventListener('click', alterna)
})

