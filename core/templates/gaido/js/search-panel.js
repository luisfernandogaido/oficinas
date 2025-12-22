const init = () => {
  if (!panel || !button) return
  document.addEventListener('click', () => {
    panelHide()
  })
  document.addEventListener('keydown', e => {
    if (e.key == 'Escape') panel.classList.remove('show')
    if (e.key == '/') {
      console.log(e.target)
    }
  })
  button.addEventListener('click', e => {
    e.stopPropagation()
    panel.classList.toggle('show')
    // history.pushState({ painel: true }, null)
  })
  panel.addEventListener('click', e => {
    e.stopPropagation()
  })
  window.addEventListener('popstate', e => {
    console.log(e.state)
  })
  // if (history?.state?.painel) history.back()
}

const panelHide = () => {
  panel.classList.remove('show')
  // history.back()

}

const panel = document.querySelector('.search-panel')
const button = document.querySelector('button.tune')

init()

export {
  panelHide,
}