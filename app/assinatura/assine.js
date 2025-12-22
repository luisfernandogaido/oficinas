import { moeda } from '../../core/templates/gaido/js/texto.js'
import { post, registraAll } from '../../core/templates/gaido/js/forms.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'

const update = () => {
  const periodo = document.querySelector('[name="periodo"]:checked').value
  const valor = moeda(valores[periodo])
  document.querySelector('#campo-valor span').textContent = `R$ ${valor}`
}

const geraCobranca = async () => {
  try {
    if (gerando) return
    gerando = true
    abre('Gerando...')
    const r = await post(document.querySelector('form'), 'gera-cobranca.php')
    gerando = false
    window.open(r.asaas_invoice_url, '_blank')
    location.replace('cobranca.php')
  } catch (e) {
    gerando = false
    abre(e, 10, 'OK')
  }
}

let gerando = false

registraAll()
document.querySelectorAll('[name="periodo"]').forEach(b => {
  b.addEventListener('click', update)
})
document.querySelector('#button-gerar-cobranca').addEventListener('click', geraCobranca)

update()

