import { inputSaveStep, salvaEVai } from './passos.js'

document.querySelectorAll('form textarea').forEach(el => {
  el.addEventListener('input', inputSaveStep)
})

document.querySelector('#prosseguir').addEventListener('click', () => salvaEVai('passo5.php'))