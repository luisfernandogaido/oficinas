import { registraAll, reportaInvalidos, setValidade } from '../../core/templates/gaido/js/forms.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'

const update = () => {
  document.querySelector('#wrapper-trocar-senha').classList.toggle('hidden', codigo == 0)
  if (informarSenha) {
    document.querySelector('#trocar-senha').textContent = 'Manter senha'
    document.querySelectorAll('.campo.senha').forEach(campo => {
      campo.classList.remove('hidden')
      campo.querySelector('input').required = true
    })
  } else {
    document.querySelector('#trocar-senha').textContent = 'Trocar senha'
    document.querySelectorAll('.campo.senha').forEach(campo => {
      campo.classList.add('hidden')
      campo.querySelector('input').required = false
    })
  }
}

const salva = async () => {
  try {
    const f = document.querySelector('form')
    if (reportaInvalidos(f)) return
    abre('Salvando...')
    const body = new FormData(f)
    const res = await fetch('salva.php', { method: 'POST', body: body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    abre('Salvo')
    setTimeout(() => {
      history.back()
    }, 500)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const toggleInformarSenha = () => {
  informarSenha = !informarSenha
  update()
}

const comparaSenhas = () => {
  const senha = document.querySelector('#senha')
  const senha2 = document.querySelector('#senha2')
  if (!senha.value || !senha2.value) return
  if (senha.value != senha2.value) {
    setValidade(senha, 'Senhas não coincidem')
    setValidade(senha2, 'Senhas não coincidem')
    return
  }
  setValidade(senha, '')
  setValidade(senha2, '')
}

registraAll()
document.querySelector('#trocar-senha').addEventListener('click', toggleInformarSenha)
document.querySelector('#salvar').addEventListener('click', salva)
document.querySelector('#senha').addEventListener('input', comparaSenhas)
document.querySelector('#senha2').addEventListener('input', comparaSenhas)
update()