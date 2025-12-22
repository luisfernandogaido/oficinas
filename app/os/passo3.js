import { registraAll } from '../../core/templates/gaido/js/forms.js'
import { salvaEVai } from './passos.js'

document.querySelectorAll('input[type="radio"]').forEach(b => {
  b.addEventListener('click', () => salvaEVai('passo4.php'))
})

registraAll()