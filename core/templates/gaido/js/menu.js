import * as botoes from './botoes.js'
import * as popover from './popover.js'

const hambuger = document.querySelector('body > header button.menu')
const menu = document.querySelector('body > header menu')
const voltar = document.querySelector('body > header menu .voltar')
let dados = {}

const le = () => {
  const hash = location.hash
  const pars = new URLSearchParams(location.hash.substr(1))
  if (pars.has('menu')) {
    pars.delete('menu')
    if (pars.toString()) {
      history.replaceState(null, '', '#' + pars.toString())
    } else {
      history.replaceState(null, '', window.location.pathname + window.location.search)
    }
  }
  const d = window.localStorage.getItem('menu-gaido')
  if (!d) return
  dados = JSON.parse(d)
  const subs = document.querySelectorAll('body > header menu a:not([href])')
  if (!dados.estadosSubs || subs.length != dados.estadosSubs.length) return
  const n = subs.length
  for (let i = 0; i < n; i++) {
    if (dados.estadosSubs[i]) {
      subs[i].classList.add('aberto')
      let s = subs[i].nextSibling.nextSibling
      s.classList.add('aberto')
    }
  }
  setTimeout(() => {
    menu.scrollTop = dados.scrollTop
  }, 0)
}

le()

const salva = () => {
  window.localStorage.setItem('menu-gaido', JSON.stringify(dados))
}

const abre = ev => {
  botoes.fechaDropdowns()
  menu.classList.add('aberto')
  if (!ev) return
  const hash = new URLSearchParams(location.hash.substr(1))
  hash.set('menu', '1')
  location.hash = hash
}

const fecha = ev => {
  if (!menu.classList.contains('aberto')) return
  menu.classList.remove('aberto')
  if (!ev) return
  history.back()
}

const abrFecMen = ev => {
  const a = ev.currentTarget
  const s = a.nextSibling.nextSibling
  if (a.classList.contains('aberto')) {
    a.classList.remove('aberto')
    s.classList.remove('aberto')
    s.style.height = '0'
  } else {
    a.classList.add('aberto')
    s.classList.add('aberto')
    s.style.height = 'auto'
    const h = s.offsetHeight
    s.style.height = '0'
    setTimeout(() => {
      s.style.height = `${h}px`
    }, 15)
  }
}

hambuger.addEventListener('click', ev => {
  ev.stopPropagation()
  if (menu.classList.contains('aberto')) {
    fecha(ev)
    return
  }
  abre(ev)
  popover.hide()
})

menu.addEventListener('click', ev => {
  ev.stopPropagation()
})

voltar.addEventListener('click', fecha)

document.body.addEventListener('click', fecha)
document.body.addEventListener('keydown', ev => {
  if (ev.key != 'Escape') return
  fecha(ev)
})

document.querySelectorAll('body > header menu a:not([href])').forEach(a => {
  a.addEventListener('click', abrFecMen)
})

document.querySelectorAll('body > header menu a[href]').forEach(a => {
  a.addEventListener('click', fecha)
})

document.querySelectorAll('body > header menu a').forEach(a => {
  a.addEventListener('click', () => {
    dados.scrollTop = menu.scrollTop
    dados.estadosSubs = [...document.querySelectorAll('body > header menu a:not([href])')].
      map(a => a.classList.contains('aberto'))
    salva()
  })
})

window.addEventListener('popstate', () => {
  const hash = new URLSearchParams(location.hash.substr(1))
  if (hash.has('menu')) return
  fecha()
})

const headers = document.querySelectorAll('body > header, main > header')

const bTema = document.getElementById('menu-tema')

bTema.addEventListener('click', () => {
  document.body.classList.toggle('dark')
  window.localStorage.setItem('tema-dark', document.body.classList.contains('dark').toString())
})

if (window.localStorage.getItem('tema-dark') == 'true' && !window.TEMA_PROFINANC) {
  document.body.classList.add('dark')
}

const bInstall = document.getElementById('menu-install')
let evInstall
window.addEventListener('beforeinstallprompt', ev => {
  evInstall = ev
  bInstall.classList.remove('oculto')
})
bInstall.addEventListener('click', ev => {
  ev.preventDefault()
  evInstall.prompt()
  evInstall.userChoice.then(choiceResult => {
    bInstall.classList.add('oculto')
  })
})

export { fecha }