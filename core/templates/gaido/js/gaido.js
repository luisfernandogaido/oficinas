import './menu.js'
import './botoes.js'
import './forms.js'
import './alerta.js'
import './tabelas.js'
import './tabs.js'
import './tooltips.js'
import './tela.js'
import './popover.js'
import './search-panel.js'
import './../../../../app/app.js'
import { getWebGLFingerprint } from './fingerprint.js'

document.body.classList.remove('oculto')
const touch = 'ontouchstart' in window
if (touch) {
  document.body.classList.add('touch')
}
if (window.TEMA_PROFINANC) {
  document.body.classList.add('profinanc')
}
if (navigator.userAgent.includes('iPhone')) {
  document.body.classList.add('iphone')
}

const loading = document.getElementById('loading')

const loadingVisivel = ev => {
  if (ev.ctrlKey) return
  loading.classList.add('visivel')
  setTimeout(() => {
    loading.classList.remove('visivel')
  }, 30000)
}

window.addEventListener('pagehide', () => {
  loading.classList.remove('visivel')
})

const registraLoading = () => {
  setTimeout(() => {
    document.querySelectorAll(
      'a[href]:not([target="_blank"]):not([href="#"], [href="javascript:void(0)"]):not([download])',
    ).forEach(a => {
      if (a.href.includes('#')) return
      a.addEventListener('click', loadingVisivel)
    })
  }, 5000)
}

const observa = () => {
  observer.observe(document.body, { attributes: true, childList: true, subtree: true })
}

const registraUpdateChecks = () => {
  document.querySelectorAll('.checks input[type=checkbox], .checks input[type=radio]').forEach(check => {
    check.addEventListener('change', updateChecks)
  })
}

const observer = new MutationObserver(() => {
  registraLoading()
  document.querySelectorAll('input[type="number"]').forEach(el => {
    el.addEventListener('keydown', previneVirgulas)
  })
  observer.disconnect()
  if (document.querySelector('.checks')) {
    registraUpdateChecks()
  }
  observa()
  document.querySelectorAll('input[type="search"]').forEach(el => {
    if('noEsc' in el.dataset) return
    el.addEventListener('keydown', escSearch)
  })
  registraThemeToggles()
})
observa()
registraLoading()

const temScroll = () => {
  const tamanho = touch ? 56 : 1
  const delta = document.documentElement.scrollHeight - document.documentElement.clientHeight
  if (delta > tamanho) {
    document.body.classList.add('scroll')
    return
  }
  if (document.body.classList.contains('scroll')) {
    document.body.classList.remove('scroll')
  }
}

setInterval(temScroll, 1000)

/**
 *
 * @param {Element} el
 */
const show = el => {
  el.classList.remove('oculto', 'oculta')
}

/**
 *
 * @param {Element | Node} el
 */
const hide = el => {
  el.classList.add('oculto')
}

const load = () => {
  loading.classList.add('visivel')
}

const carregando = `<div class="carregando"></div>`

const loadHide = () => {
  loading.classList.remove('visivel')
}

const location = url => {
  load()
  window.location.href = url
}

const reloadCache = async () => {
  // if ('serviceWorker' in navigator && navigator.userAgent.includes('Chrome')) {
  //   navigator.serviceWorker.register(window.SITE + 'app/sw.js').then(registration => {
  //     if (registration.active) {
  //       const serviceWorker = registration.active;
  //       serviceWorker.postMessage('reload');
  //     }
  //   }).then();
  // }
}

const versao = () => {
  if (!getSw()) return
  fetch(window.SITE + 'core/versao.php').then(res => res.json()).then(r => {
    const versao = localStorage.getItem('versao')
    localStorage.setItem('versao', r)
    if (versao != r) reloadCache().then()
  })
}

const share = async (title, text, url) => {
  if ('share' in navigator) {
    let sharedData = { title, text }
    if (url) {
      sharedData.url = url
    }
    navigator.share(sharedData)
  } else {
    const url = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(`${title} - ${text}`)
    window.open(url)
  }
}

// versao();
// sw();

const isStandalone = () => {
  return window.matchMedia('(display-mode: standalone)').matches
}

const previneVirgulas = ev => {
  if (ev.key == ',') {
    ev.preventDefault()
  }
}

const updateChecks = () => {
  document.querySelectorAll('.checks input[type=checkbox], .checks input[type=radio]').forEach(check => {
    const label = check.parentNode
    if (check.checked) {
      label.classList.add('sel')
    } else {
      label.classList.remove('sel')
    }
  })
}

registraUpdateChecks()
updateChecks()

const backOrClose = () => {
  if (history.length > 1) {
    window.history.back()
    return
  }
  window.close()
}

const topo = async () => {
  return new Promise(resolve => {
    setTimeout(() => {
      document.documentElement.scrollTop = 0
      resolve()
    }, 0)
  })
}

const sleep = async timeout => {
  return new Promise(resolve => {
    setTimeout(() => {
      resolve()
    }, timeout)
  })
}

const saveState = (key, state) => {
  state.ts = (new Date()).getTime()
  localStorage.setItem(key, JSON.stringify(state))
}

const loadState = (key, ttl) => {
  const item = localStorage.getItem(key)
  if (!item) return
  const state = JSON.parse(item)
  const delta = (new Date()).getTime() - state.ts
  if (ttl && delta > ttl * 1000) return
  return state
}

const deleState = key => {
  localStorage.removeItem(key)
}

const escSearch = ev => {
  if (ev.key != 'Escape') return
  const input = ev.currentTarget
  if (input.value != '') return
  ev.preventDefault()
  history.back()
}

const toggleTheme = () => {
  document.body.classList.toggle('dark')
  window.localStorage.setItem('tema-dark', document.body.classList.contains('dark').toString())
}

const registraThemeToggles = () => {
  document.querySelectorAll('button.theme').forEach(b => {
    b.addEventListener('click', toggleTheme)
  })
}

registraThemeToggles()

const isTouch = () => {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0
}

const hasKeyBoard = () => {
  return window.matchMedia('(any-hover: hover)').matches
}

const fingerPrint = () => {
  try {
    const canvas = document.createElement('canvas')
    const ctx = canvas.getContext('2d')
    ctx.textBaseline = 'top'
    ctx.font = `14px Arial`
    ctx.textBaseline = 'alphabetic'
    ctx.fillStyle = '#f60'
    ctx.fillRect(125, 1, 62, 20)
    ctx.fillStyle = '#069'
    ctx.fillText('Browser fingerprinting', 2, 15)
    ctx.fillStyle = 'rgba(102, 204, 0, 0.7)'
    ctx.fillText('Browser fingerprinting', 4, 17)
    const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection
    const data = {
      user_agent: navigator.userAgent,
      cookie_enabled: navigator.cookieEnabled,
      language: navigator.language,
      languages: navigator.languages,
      screen_width: screen.width,
      screen_height: screen.height,
      screen_color_depth: screen.colorDepth,
      screen_pixel_depth: screen.pixelDepth,
      window_width: innerWidth,
      window_height: innerHeight,
      network_type: connection ? connection.effectiveType : null,
      save_data: connection ? connection.saveData : false,
      media_devices_supported: 'mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices,
      webgl_suport: !!window.WebGLRenderingContext,
      webgl_fp: getWebGLFingerprint(),
      webrtc_suport: !!window.RTCPeerConnection,
      // canvas: canvas.toDataURL(),
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
      device_memory: navigator.deviceMemory,
      hardware_concurrency: navigator.hardwareConcurrency,
      max_touch_points: navigator.maxTouchPoints,
      plugins: Array.from(navigator.plugins).map(p => p.name),
    }
    return data
  } catch (e) {
    return { erro: e }
  }
}

const addReloadPersisted = () => {
  window.addEventListener('pageshow', ev => {
    if (!ev.persisted) return
    setTimeout(() => {
      window.location.reload()
    }, 50)
  })
}

export {
  show,
  hide,
  load,
  carregando,
  loadHide,
  location,
  reloadCache,
  share,
  isStandalone,
  updateChecks,
  backOrClose,
  topo,
  sleep,
  saveState,
  loadState,
  deleState,
  isTouch,
  hasKeyBoard,
  fingerPrint,
  addReloadPersisted,
  escSearch,
}