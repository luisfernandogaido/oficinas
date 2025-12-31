import { abre } from '../../core/templates/gaido/js/alerta.js'
import { isTouch } from '../../core/templates/gaido/js/gaido.js'

const update = () => {
  if (buttonHistory.classList.contains('active')) {
    document.body.classList.add('history')
  } else {
    document.body.classList.remove('history')
  }
}

const load = async () => {
  try {
    ss()
    clearTimeout(timerPooling)
    const qs1 = queryString()
    const res = await fetch('load.php?' + qs1)
    const html = await res.text()
    const qs2 = queryString()
    if (qs1 != qs2) return
    document.querySelector('.cards').innerHTML = html
    document.querySelector('.cards').querySelectorAll('button.delete').forEach(b => {
      b.addEventListener('click', exclui)
    })
    timerPooling = setTimeout(load, POOLING)

    //isso é só uma prova de conceito: é possível trafegar HTML e JSON.
    const data = JSON.parse(document.querySelector('.cards .data').textContent)
    console.log(data)

  } catch (e) {
    abre(e, 10, 'OK')
  }
}

const queryString = () => {
  const pseudoStatus = document.querySelector('#chips .chip.selected')?.dataset.pseudoStatus ?? ''
  const par = new URLSearchParams({
    pseudo_status: pseudoStatus,
    historico: buttonHistory.classList.contains('active'),
    search: document.querySelector('#search').value,
  })
  return par.toString()
}

const selecionaPseudoStatus = e => {
  const chip = e.currentTarget
  const selected = document.querySelector('#chips .chip.selected')
  if (selected) {
    selected.classList.remove('selected')
  }
  if (chip != selected) {
    chip.classList.add('selected')
  }
  load().then()
}

const toggleHistory = () => {
  buttonHistory.classList.toggle('active')
  update()
  load().then()
  if (buttonHistory.classList.contains('active') && !isTouch()) {
    setTimeout(() => {
      inputSearch.focus()
    }, 0)
  }
}

const ss = () => {
  const url = new URL(window.location.href)
  if (buttonHistory.classList.contains('active')) {
    url.searchParams.set('history', '1')
  } else {
    url.searchParams.delete('history')
  }
  if (document.documentElement.scrollTop) {
    url.searchParams.set('scroll', String(document.documentElement.scrollTop))
  } else {
    url.searchParams.delete('scroll')
  }
  if (document.querySelector('#search').value) {
    url.searchParams.set('search', document.querySelector('#search').value)
  } else {
    url.searchParams.delete('search')
  }
  history.replaceState(null, '', url)
}

const ls = () => {
  const url = new URL(window.location.href)
  if (url.searchParams.has('history')) {
    buttonHistory.classList.add('active')
  }
  if (url.searchParams.has('search')) {
    document.querySelector('#search').value = url.searchParams.get('search')
  }
}

const teclaSearch = ev => {
  if (ev.key != 'Escape') return
  if (inputSearch.value) return
  buttonHistory.classList.remove('active')
  update()
  load().then()
}

const inputSearchListener = () => {
  clearTimeout(timer)
  timer = setTimeout(load, DEBOUNCE)

}

const exclui = async e => {
  e.stopPropagation()
  e.preventDefault()
  const a = e.currentTarget.closest('a')
  const res = await fetch('actions/exclui.php?codigo=' + a.dataset.codigo)
  const r = await res.json()
  if(r.erro){
    abre(r.erro, 10, 'OK')
    return
  }
  a.remove()
}

let timer
let timerPooling
let POOLING = 300_000
let DEBOUNCE = 500
const buttonHistory = document.querySelector('button.history')
const inputSearch = document.querySelector('input[type=search]')

buttonHistory.addEventListener('click', toggleHistory)
inputSearch.addEventListener('keydown', teclaSearch)
inputSearch.addEventListener('input', inputSearchListener)
document.querySelectorAll('#chips .chip').forEach(chip => {
  chip.addEventListener('click', selecionaPseudoStatus)
})
document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    load().then()
  } else {
    clearTimeout(timerPooling)
  }
})
window.addEventListener('beforeunload', ss)

ls()
update()

load().then()