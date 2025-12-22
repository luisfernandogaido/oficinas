import { registraAll } from '../../core/templates/gaido/js/forms.js'
import { Upload } from '../../core/templates/gaido/js/upload.js'
import { abre, fecha } from '../../core/templates/gaido/js/alerta.js'
import { e } from '../../core/templates/gaido/js/texto.js'
import { salvaEVai } from './passos.js'

const update = () => {

  barraProgresso.classList.toggle('hidden', !uploading)
}

const anexar = () => {
  document.querySelector('#arquivo').click()
}

const envia = async () => {
  try {
    let totalToSend = 0
    console.log(document.querySelector('#arquivo').files)
    for (const file of document.querySelector('#arquivo').files) {
      totalToSend += file.size
    }
    const totalAlready = parseInt(document.querySelector('#files').dataset.totalOriginalSize)
    if (totalToSend + totalAlready > maxSizeTotal) {
      const msg = `Espaço usado para reportar problema da OS não pode exceder ${maxSizeTotalH}`
      abre(msg, 10, 'OK')
      return
    }
    uploading = true
    fecha()
    update()
    const json = await upload.send(document.querySelector('form'))
    const data = JSON.parse(json)
    if (data.erro) {
      abre(data.erro, 10, 'OK')
      return
    }
    await load()
  } catch (e) {
    abre(e, 10, 'OK')
  } finally {
    uploading = false
    document.querySelector('#arquivo').value = ''
    update()
  }
}

const progresso = (perc, loaded, total) => {
  uploading = true
  barraProgresso.querySelector('div').style.width = `${perc}%`
  barraProgresso.querySelector('div span').textContent = `${Math.ceil(perc)}%`
}

const abreFoto = e => {
  const img = e.currentTarget
  open(img.src)

}

const exclui = async e => {
  try {
    const ctn = e.currentTarget.closest('div.file')
    const pars = new URLSearchParams({
      hash: hash,
      id: ctn.dataset.id,
    })
    const res = await fetch('passo5-exclui.php?' + pars)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    const scroll = document.documentElement.scrollTop
    console.log(scroll)
    await load()
    setTimeout(()=>{
      document.documentElement.scrollTop = scroll
    }, 100)

  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const load = async () => {
  try {
    const res = await fetch('passo5-load.php?h=' + hash)
    const html = await res.text()
    document.querySelector('#resultado').innerHTML = html
    document.querySelectorAll('#resultado img').forEach(img => img.addEventListener('click', abreFoto))
    document.querySelectorAll('#resultado button.delete').forEach(img => img.addEventListener('click', exclui))
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const upload = new Upload('passo5-upload.php', progresso)

let uploading = false
const barraProgresso = document.querySelector('div.progresso')

registraAll()
document.querySelector('#anexar').addEventListener('click', anexar)
document.querySelector('#arquivo').addEventListener('change', envia)
document.querySelector('#prosseguir').addEventListener('click', () => salvaEVai('passo6.php'))

load().then()
update()
