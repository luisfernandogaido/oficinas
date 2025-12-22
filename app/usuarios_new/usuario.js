import { abre } from '../../core/templates/gaido/js/alerta.js'

const toggleValidacaoPendente = async ev => {
  try {
    const value = ev.currentTarget.checked
    const pars = new URLSearchParams({
      codUsuario: document.querySelector('#codigo').value,
      pendente: value,
    })
    const res = await fetch('toggle-validacao-pendente.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
    }
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const toggleForcarAssinatura = async ev => {
  try {
    const value = ev.currentTarget.checked
    const pars = new URLSearchParams({
      codUsuario: document.querySelector('#codigo').value,
      forca: value,
    })
    const res = await fetch('toggle-forcar-assinatura.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
    }
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const toggleWhatsappValidado = async ev => {
  try {
    const value = ev.currentTarget.checked
    const pars = new URLSearchParams({
      codUsuario: document.querySelector('#codigo').value,
      validado: value,
    })
    const res = await fetch('toggle-whatsapp-validado.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
    }
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const exclui = async () => {
  try {
    const res = await fetch('exclui.php?codigo=' + document.querySelector('#codigo').value)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    history.back()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

document.querySelector('#validacao_pendente').addEventListener('click', toggleValidacaoPendente)
document.querySelector('#forcar_assinatura').addEventListener('click', toggleForcarAssinatura)
document.querySelector('#whatsapp_validado').addEventListener('click', toggleWhatsappValidado)
document.querySelector('button.delete-red').addEventListener('click', exclui)

window.addEventListener('pageshow', e => {
  if (!e.persisted) return
  setTimeout(() => {
    location.reload()
  }, 50)
})