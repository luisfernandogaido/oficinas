import { abre } from '../../core/templates/gaido/js/alerta.js'

const saveStep = async () => {
  clearTimeout(timer)
  const body = new FormData(document.querySelector('form'))
  const res = await fetch('save-step.php', { method: 'POST', body: body })
  const r = await res.json()
  if (r.erro) {
    throw r.erro
  }
  console.log('saved')
}

const inputSaveStep = () => {
  clearTimeout(timer)
  timer = setTimeout(saveStep, 1000)
}

const salvaEVai = async endpoint => {
  try {
    await saveStep()
    const pars = new URLSearchParams(window.location.search)
    if (pars.has('edit')) {
      location.replace(`${endpoint}?edit=1&h=${document.querySelector('#hash').value}`)
    } else {
      location.href = `${endpoint}?h=${document.querySelector('#hash').value}`
    }
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

let timer

export {
  saveStep,
  salvaEVai,
  inputSaveStep,
}