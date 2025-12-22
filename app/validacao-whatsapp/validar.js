import { post, registraAll, reportaInvalidos } from '../../core/templates/gaido/js/forms.js'
import { whatsAppLinkOpen } from '../../core/templates/gaido/js/whatsapp.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'

const cola = ev => {
  setTimeout(() => {
    let numero = document.querySelector('#numero').value
    numero = numero.replace(/\D/g, '')
    if (numero.startsWith('55')) numero = numero.substring(2)
    document.querySelector('#numero').value = numero
    avaliaNumero().then()
  }, 50)
}

const avaliaNumero = async () => {
  document.querySelector('#usuarios-celular-informado').innerHTML = '<div class="carregando"></div>'
  const res = await fetch('avalia-numero.php?numero=' + document.querySelector('#numero').value)
  const html = await res.text()
  document.querySelector('#usuarios-celular-informado').innerHTML = html
  document.querySelectorAll('#usuarios-celular-informado .usuario button.gerar-token').forEach(b => {
    b.addEventListener('click', geraToken)
  })
}

const geraToken = async ev => {
  try {
    const div = ev.currentTarget.closest('.usuario')
    console.log(div.dataset)
    const pars = new URLSearchParams({
      codUsuario: div.dataset.codigo,
      email: div.dataset.email,
      celular: document.querySelector('#numero').value,
    })
    const res = await fetch('gera-aut.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    await navigator.clipboard.writeText(r.link)
    abre('Link copiado!', 2)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const enterNumero = ev => {
  if (ev.key != 'Enter') return
  avaliaNumero().then()
}

const salva = async () => {
  try {
    const f = document.querySelector('form')
    if (reportaInvalidos(f)) return
    const r = await post(f, 'salva.php')
    console.log(r)
    const numero = document.querySelector('#numero').value
    if (!numero) return
    whatsAppLinkOpen(
      `+55${document.querySelector('#numero').value}`,
      document.querySelector('#resposta').value,
    )
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const mudaValidacao = ev => {
  const button = ev.currentTarget
  if (button.value == '1') {
    document.querySelector('#resposta').value = 'Validado.'
  } else {
    document.querySelector('#resposta').value =
      'Olá! Um outro cadastro já foi usado com esse número. Informe o email usado.'
  }
  document.querySelector('#resposta').focus()
}

registraAll()
document.querySelector('#numero').addEventListener('paste', cola)
document.querySelector('#numero').addEventListener('input', cola)
document.querySelector('#numero').addEventListener('keydown', enterNumero)
document.querySelector('#b-salva').addEventListener('click', salva)
document.querySelectorAll('input[name="validado"]').forEach(button => {
  button.addEventListener('click', mudaValidacao)
})