const delaySearch = 250
const delayBlur = 125

/**
 * Retorna false se o elemento está oculto.
 * @param {Element} el
 */
const visivel = el => {
  if (!el) return
  return !el.classList.contains('oculto') && !el.classList.contains('oculta')
}

class Suggest {

  /**
   * Cria um suggest com base em uma caixa de pesquisa e uma fonte de dados.
   * @param {string} idInput id da caixa de pesquisa.
   * @param {string} source Fonte de dados utilizada na pesquisa.
   * @param {Function} aoSelecionar Executada quando o usuário do aplicativo selecionar uma opção. Recebe como parâmetro
   * os dados do item selecionado.
   * @param {Function} aoPesquisar Usada se o programador quiser enviar parâmetros personalizados em cada pesquisa além
   * do texto digitado. Deve retornar um objeto com chaves e valores. Eles serão enviados via GET junto com o texto
   * da caixa.
   */
  constructor (idInput, source, aoSelecionar = null, aoPesquisar = null) {
    /**
     * Timer que controla quando uma pesquisa deve ser feita após o término de uma digitação.
     * @type {?number}
     */
    this._timer = null
    this.abrirAoFocar = false

    /**
     * Caixa de texto de pesquisa.
     * @type {HTMLElement}
     */
    this.input = document.getElementById(idInput)
    this.input.addEventListener('keydown', ev => this._tecla(ev))
    this.input.addEventListener('input', ev => this._digita(ev))
    this.input.addEventListener('blur', ev => this._sai(ev))
    this.input.addEventListener('focus', ev => this._entra(ev))
    this.input.addEventListener('mousedown', ev => this._clica(ev))

    /**
     * Fonte de dados utilizada na pesquisa.
     * @type {string}
     */
    this.source = source
    this.aoSelecionar = aoSelecionar
    this.aoPesquisar = aoPesquisar

    /**
     * Container do suggest. Dentro dele há o input e a lista pesquisa.
     * @type {Node & ParentNode}
     */
    this.ctn = this.input.parentNode
    this.ctn.classList.add('suggest')

    /**
     * Lista de sugestões.
     * @type {HTMLDivElement}
     */
    this.lista = document.createElement('div')
    this.lista.className = 'lista oculta'
    this.input.insertAdjacentElement('afterend', this.lista)
    window.addEventListener('resize', this._redimensiona)
    setInterval(() => {
      this._redimensiona()
    }, 1000)
  }

  /**
   * @param {KeyboardEvent} ev
   * @private
   */
  _tecla (ev) {

    /*
    ArrowLeft
    ArrowRight
    PageUp
    PageDown
    Home
    End
     */

    if (ev.key == 'Enter') {
      ev.preventDefault()
      clearTimeout(this._timer)
      if (visivel(this.lista)) {
        const hover = this.lista.querySelector('.hover')
        if (!hover) return
        this._seleciona(hover)
      } else {
        this.lista.classList.remove('oculta')
        this._pesquisa().then()
      }
      return
    }

    if (ev.key == 'Escape') {
      this.lista.classList.add('oculta')
      return
    }

    const upAndDown = key => {
      ev.preventDefault()
      if (!visivel(this.lista)) {
        return
      }
      const items = this.ctn.querySelectorAll('.lista a')
      if (!items.length) return
      let lastHover = this.ctn.querySelector('.lista a.hover')
      let hover
      if (!lastHover) {
        if (key == 'ArrowDown') {
          hover = items[0]
        } else {
          hover = items[items.length - 1]
        }
      } else {
        lastHover.classList.remove('hover')
        if (key == 'ArrowDown') {
          hover = lastHover.nextSibling
        } else {
          hover = lastHover.previousSibling
        }
        if (!hover) {
          if (key == 'ArrowDown') {
            hover = items[0]
          } else {
            hover = items[items.length - 1]
          }
        }
      }
      hover.classList.add('hover')
      hover.scrollIntoView({ block: 'center' })
    }

    if (ev.key == 'ArrowDown' || ev.key == 'ArrowUp') {
      upAndDown(ev.key)
      return
    }

  };

  /**
   * @private
   */
  _digita () {
    clearTimeout(this._timer)
    this._timer = setTimeout(() => {
      this._pesquisa().then()
    }, delaySearch)
  };

  /**
   * @private
   */
  _sai () {
    clearTimeout(this._timer)
    this._timer = setTimeout(() => {
      this.lista.classList.add('oculta')
    }, delayBlur)
  };

  /**
   * @private
   */
  _entra () {
    clearTimeout(this._timer)
    if (this.abrirAoFocar) {
      this._pesquisa().then()
    }
  };

  /**
   * @private
   */
  _clica () {
    if (document.activeElement == this.input) {
      this._pesquisa().then()
    }
  }

  /**
   * @private
   */
  async _pesquisa () {
    this.lista.innerHTML = ''
    const par = new URLSearchParams()
    par.set('search', this.input.value)
    if (this.aoPesquisar) {
      for (let [k, v] of Object.entries(this.aoPesquisar())) {
        par.set(k, v.toString())
      }
    }
    const res = await fetch(this.source + '?' + par.toString())
    /**
     * @type {Object[]}
     */
    const r = await res.json()
    if (r.erro) throw r.mensagem
    if (this.input.value != par.get('search')) return
    r.forEach(item => {
      let a = document.createElement('a')
      a.textContent = item.text
      a.dados = item
      a.dataset.dados = JSON.stringify(item)
      a.addEventListener('mousedown', ev => {
        this._seleciona(ev.currentTarget)
      })
      a.addEventListener('mouseover', ev => {
        this._hover(ev.currentTarget)
      })
      this.lista.appendChild(a)
    })
    this.lista.classList.remove('oculta')
    this._redimensiona()
  }

  /**
   * @param {HTMLAnchorElement | EventTarget} a
   * @private
   */
  _seleciona (a) {
    this.input.value = a.dados.text
    clearTimeout(this._timer)
    this.lista.classList.add('oculta')
    if (this.aoSelecionar) {
      this.aoSelecionar(a.dados)
    }
  }

  /**
   *
   * @param a
   * @private
   */
  _hover (a) {
    const lastOver = this.lista.querySelector('.hover')
    if (lastOver) lastOver.classList.remove('hover')
    a.classList.add('hover')
  }

  /**
   * Ajusta o tamanho e o posicionamento da lista para ocupar até máximo de espaço disponível da tela sem scroll
   * externo a ela. Se o input estiver do meio da tela pra baixo, põe a lista pra cima. A altura máxima é calculada
   * com a distância do input até uma das extremidades da tela (topo e final, dependendo do caso).
   * @private
   */
  _redimensiona () {
    if (!visivel(this.lista)) return
    const rectLista = this.lista.getBoundingClientRect()
    const rectInput = this.input.getBoundingClientRect()
    const meio = window.innerHeight / 2
    const posVert = (rectInput.top + rectInput.bottom) / 2
    const abrirPara = posVert < meio ? 'baixo' : 'cima'
    if (abrirPara == 'baixo') {
      this.lista.style.maxHeight = `${innerHeight - rectInput.bottom}px`
      this.lista.style.top = `${rectInput.height}px`
      this.lista.style.bottom = 'auto'
    } else {
      this.lista.style.maxHeight = `${rectInput.top}px`
      this.lista.style.top = `auto`
      this.lista.style.bottom = `${rectInput.height}px`
    }
  };

}

export { Suggest }