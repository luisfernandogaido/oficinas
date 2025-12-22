import { abre } from '../../core/templates/gaido/js/alerta.js'
import { registraAll } from '../../core/templates/gaido/js/forms.js'

const save = async () => {
  try {
    const f = document.querySelector('form')
    const body = new FormData(f)
    const res = await fetch('save.php', { method: 'post', body })
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
    }
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

document.querySelectorAll('.checks :is(input[type="radio"], input[type="checkbox"])').forEach(check => {
  check.addEventListener('click', save)
})

registraAll()