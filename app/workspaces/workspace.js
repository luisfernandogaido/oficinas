import { registraAll } from '../../core/templates/gaido/js/forms.js'
import { Upload } from '../../core/templates/gaido/js/upload.js'
import { abre, fecha } from '../../core/templates/gaido/js/alerta.js'

const update = () => {
  document.querySelector('form img').src = document.querySelector('#logotipo').value
}

const salva = async () => {
  try {
    const body = new FormData(document.querySelector('form'))
    const res = await fetch('salva.php', { method: 'POST', body: body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    console.log('salvo')
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const saveInput = () => {
  clearTimeout(timer)
  timer = setTimeout(salva, 500)
}

const enviaLogo = async () => {
  abre('Enviando...')
  const res = await upload.send(document.querySelector('form'))
  const r = JSON.parse(res)
  document.querySelector('#logotipo').value = r.url
  update()
  document.querySelector('#arquivo').value = ''
  fecha()
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

const buscaCep = async () => {
  setTimeout(async () => {
    if (!document.querySelector('#cep').checkValidity()) return
    const res = await fetch('../../core/api/cep.php?cep=' + document.querySelector('#cep').value)
    const r = await res.json()
    if (r.erro) {
      abre('Erro ao buscar endereço de CEP', 10, 'OK')
      return
    }
    document.querySelector('#endereco').value = r.logradouro
    document.querySelector('#bairro').value = r.bairro
    document.querySelector('#uf').value = r.uf
    document.querySelector('#cidade').value = r.localidade
    await salva()
  }, 25)
}

const generateProducsAndServices = async () => {
  const res = await fetch('generate-products-services.php?codigo=' + document.querySelector('#codigo').value)
  const r = await res.json()
  if (r.erro) {
    abre(r.erro, 10, 'OK')
    return
  }
  console.log('generateProducsAndServices ok')
}

let timer
const upload = new Upload('upload.php')

document.querySelector('#arquivo').addEventListener('change', enviaLogo)
document.querySelector('#carregar-foto').addEventListener('click', () => document.querySelector('#arquivo').click())
document.querySelector('#nome').addEventListener('input', saveInput)
document.querySelector('#descricao').addEventListener('input', saveInput)
document.querySelector('#cep').addEventListener('input', saveInput)
document.querySelector('#endereco').addEventListener('input', saveInput)
document.querySelector('#numero').addEventListener('input', saveInput)
document.querySelector('#complemento').addEventListener('input', saveInput)
document.querySelector('#bairro').addEventListener('input', saveInput)
document.querySelector('#uf').addEventListener('input', saveInput)
document.querySelector('#cidade').addEventListener('input', saveInput)
document.querySelector('button.delete-red').addEventListener('click', exclui)
document.querySelector('#cep').addEventListener('blur', buscaCep)

registraAll()
generateProducsAndServices().then()
update()
