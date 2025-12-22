import { abre } from '../../core/templates/gaido/js/alerta.js'

const solicitaAtendimento = async () => {
  try {
    const res = await fetch('abre-os.php?h=' + workspaceHash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    location.href = '../os/os.php?h=' + r.os_hash
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

document.querySelector('#solicitar-atendimento').addEventListener('click', solicitaAtendimento)
document.body.classList.remove('dark')
