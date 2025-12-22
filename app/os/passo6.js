import { Suggest } from '../../core/templates/gaido/js/suggest.js'
import { registraAll, reportaInvalidos } from '../../core/templates/gaido/js/forms.js'
import { salvaEVai } from './passos.js?v=2'

const update = () => {
  if (document.querySelector('#codigo-fipe').value == '') {
    document.querySelector('#fipe-search').classList.remove('hidden')
    document.querySelector('#fipe-panel').classList.add('hidden')
  } else {
    document.querySelector('#fipe-search').classList.add('hidden')
    document.querySelector('#fipe-panel').classList.remove('hidden')
    document.querySelector('#fipe-panel .marca').textContent = document.querySelector('#marca').value
    document.querySelector('#fipe-panel .modelo').textContent = document.querySelector('#modelo').value
    document.querySelector('#fipe-panel .ano').textContent = document.querySelector('#ano').value
    document.querySelector('#fipe-panel .combustivel').textContent = document.querySelector('#combustivel').value
    document.querySelector('#fipe-panel .codigo-fipe').textContent = document.querySelector('#codigo-fipe').value
  }
  const novoCadastro = document.querySelector('input[type="radio"][value="0"]').checked
  document.querySelectorAll('div.campos-novo').forEach(div => {
    div.classList.toggle('hidden', !novoCadastro)
  })
  document.querySelector('#placa').required = novoCadastro
  document.querySelector('#km').required = novoCadastro
}

const selecionaFipe = dados => {
  document.querySelector('#fipe-search').value = ''
  document.querySelector('#tipo').value = dados.tipo
  document.querySelector('#marca').value = dados.marca
  document.querySelector('#modelo').value = dados.modelo
  document.querySelector('#ano').value = dados.ano
  document.querySelector('#combustivel').value = dados.combustivel
  document.querySelector('#codigo-fipe').value = dados.codigoFipe
  document.querySelector('#valor-fipe').value = dados.valor
  document.querySelector('#id-fipe').value = dados.id
  update()
}

const removeFipe = () => {
  document.querySelector('#marca').value = ''
  document.querySelector('#modelo').value = ''
  document.querySelector('#ano').value = ''
  document.querySelector('#combustivel').value = ''
  document.querySelector('#codigo-fipe').value = ''
  document.querySelector('#valor-fipe').value = ''
  document.querySelector('#id-fipe').value = ''
  update()
  document.querySelector('#fipe-search').focus()
}

const salva = async () => {
  if (reportaInvalidos(document.querySelector('form'))) return
  await salvaEVai('passo7.php')
}

const suggest = new Suggest('fipe-search', '../fipe/find.php', selecionaFipe)

registraAll()
document.querySelector('#fipe-panel button.close').addEventListener('click', removeFipe)
document.querySelector('#prosseguir').addEventListener('click', salva)
document.querySelectorAll('.campo.veiculos input').forEach(el => {
  el.addEventListener('click', update)
})
document.querySelectorAll('input[type="radio"][name="cod-veiculo"]').forEach(b => {
  if (b.value == '0') return
  b.addEventListener('click', () => salvaEVai('passo7.php'))
})

update()

