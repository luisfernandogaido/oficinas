import { addReloadPersisted, isTouch } from '../../core/templates/gaido/js/gaido.js'
import { abre } from '../../core/templates/gaido/js/alerta.js'
import { send } from '../../core/templates/gaido/js/whatsapp.js'

const editarInformacoes = () => {
  const pars = new URLSearchParams({ h: hash, edit: '1' })
  location.href = 'passo1.php?' + pars
}

const scrollToLeft = () => {
  const carrossel = document.querySelector('#carrossel')
  const scrollAmmount = carrossel.clientWidth * 0.9
  carrossel.scrollBy({ left: -scrollAmmount, behavior: 'smooth' })
}

const scrollToRight = () => {
  const carrossel = document.querySelector('#carrossel')
  const scrollAmmount = carrossel.clientWidth * 0.9
  carrossel.scrollBy({ left: scrollAmmount, behavior: 'smooth' })
}

const cancela = async () => {
  try {
    const res = await fetch('actions/cancela.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    location.reload()
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const entrarEmContato = () => send(whatsAppOficina)

const comoChegar = () => window.open(linkMaps)

const tenhoDuvidas = () => send(whatsAppOficina, textoTenhoDuvidas)

const aprovarOrcamento = () => {
  location.hash = '#aprovacao-orcamento'
}

const aprovaOrcamento = async () => {
  try {
    const res = await fetch('actions/aprova.php?hash=' + hash)
    const r = await res.json()
    if (r.erro) {
      abre(r.erro, 10, 'OK')
      return
    }
    history.back()
    setTimeout(() => {
      location.reload()
    }, 125)
  } catch (e) {
    abre(e, 10, 'OK')
  }
}

document.querySelector('#editar-informacoes')?.addEventListener('click', editarInformacoes)
document.querySelectorAll('#carrossel img').forEach(img => {
  img.addEventListener('click', ev => {
    window.open(ev.currentTarget.src)
  })
})

document.querySelector('#carrossel button.left')?.addEventListener('click', scrollToLeft)
document.querySelector('#carrossel button.right')?.addEventListener('click', scrollToRight)
document.querySelector('#cancelar')?.addEventListener('click', cancela)
document.querySelector('#contato')?.addEventListener('click', entrarEmContato)
document.querySelector('#como-chegar')?.addEventListener('click', comoChegar)
document.querySelector('#tenho-duvidas')?.addEventListener('click', tenhoDuvidas)
document.querySelector('#aprovar-orcamento')?.addEventListener('click', aprovarOrcamento)
document.querySelector('#confirma-orcamento')?.addEventListener('click', aprovaOrcamento)
window.addEventListener('keydown', ev => {
  console.log(ev.key)
  switch (ev.key) {
    case 'ArrowLeft':
      scrollToLeft()
      break
    case 'ArrowRight':
      scrollToRight()
  }
})
addReloadPersisted()
document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    location.reload()
  }
})

if (isTouch()) {
  document.body.classList.add('sem-botoes')
}
setTimeout(() => {
  const carrossel = document.querySelector('#carrossel')
  if (carrossel && carrossel.scrollWidth <= carrossel.clientWidth) {
    document.body.classList.add('sem-botoes')
  }
}, 100)
