class MonEditor {

  /**
   *
   * @param {HTMLElement} ctn
   * @param {HTMLElement} input
   */
  constructor (ctn, input) {

    this.timer = null;

    /**
     * @type HTMLElement
     */
    this.ctn = ctn;

    /**
     *
     * @type {HTMLInputElement}
     */
    this.input = input;

    this.ctn.className = 'editor';

    const conteudoInicial = this.input.value;
    this.input.classList.add('oculto');

    //language=html
    this.ctn.innerHTML = `
        <div class="acoes">
            <button class="bold" title="Negrito (CTRL+B)"></button>
            <button class="link" title="Adicionar link (CTRL+K)"></button>
            <button class="format-clear" title="Limpar formatação (CTRL+\)"></button>
        </div>
        <div contenteditable="true"></div>
    `;

    /**
     *
     * @type {HTMLDivElement}
     */
    this.conteudo = this.ctn.querySelector('div[contenteditable="true"]');
    this.conteudo.innerHTML = conteudoInicial;

    this.ctn.querySelector('.bold').addEventListener('click', () => {
      this.conteudo.focus();
      document.execCommand('bold');
    });

    this.ctn.querySelector('.link').addEventListener('click', () => {
      this.conteudo.focus();
      navigator.clipboard.readText().then(text => {
        document.execCommand('createLink', false, text);
      });
    });

    this.ctn.querySelector('.format-clear').addEventListener('click', () => {
      this.conteudo.focus();
      // document.execCommand('removeFormat');
      const range = window.getSelection().getRangeAt(0);
      const textNode = document.createTextNode(range.toString());
      if(range.toString() == this.conteudo.textContent){
        range.selectNodeContents(this.conteudo);
      }
      range.deleteContents();
      range.insertNode(textNode);
    });

    this.conteudo.addEventListener('keydown', ev => {
      if (!ev.ctrlKey) return;
      switch (ev.key) {
        case 'k':
        case 'K':
          ev.preventDefault();
          this.conteudo.focus();
          navigator.clipboard.readText().then(text => {
            document.execCommand('createLink', false, text);
          });
          break;
        case '\\':
          ev.preventDefault();
          this.conteudo.focus();
          document.execCommand('removeFormat');
          break;
      }
    });

    this.timer = setInterval(() => {
      this.input.value = this.conteudo.innerHTML;
    }, 150);

  }

  updateFromValue () {
    this.conteudo.innerHTML = this.input.value;
    clearInterval(this.timer);
    this.timer = setInterval(() => {
      this.input.value = this.conteudo.innerHTML;
    }, 150);
  }

}

export { MonEditor };