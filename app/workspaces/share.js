import { abre } from '../../core/templates/gaido/js/alerta.js'

const shareQrCode = async () => {
  try {
    const body = new URLSearchParams({
      link,
    })
    const res = await fetch('../spread/share-qrcode.php', { method: 'post', body })
    const blob = await res.blob()
    img.src = URL.createObjectURL(blob)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const copia = async () => {
  await navigator.clipboard.writeText(link)
  document.querySelector('#copiar-link').textContent = 'Copiado!'
  setTimeout(() => {
    document.querySelector('#copiar-link').textContent = 'Copiar link'
  }, 5000)
}

const img = document.querySelector('#qrcode img')
// document.querySelector('#button-print').addEventListener('click', () => window.print())
document.querySelector('#copiar-link').addEventListener('click', copia)

shareQrCode().then()
