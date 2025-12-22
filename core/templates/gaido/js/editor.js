/*
referências:
https://www.slatejs.org/examples/richtext
https://trix-editor.org/
https://www.kindacode.com/article/popular-open-source-wysiwyg-editors-for-react/

Definir a diferença entre o que é geral o suficiente para estar no editor e o que é específico demais para ser
configurado à parte é uma arte que eu estou descobrindo empiricamente, maravilhado. (Gaido, 29/07/2022)
 */

let loaded = false;

const load = () => {
  if (loaded) return;
  loadCss();
  loaded = true;
};

const loadCss = () => {
  if (loaded) return;
  const link = document.createElement('link');
  link.rel = 'stylesheet';
  link.href = SITE + 'core/templates/gaido/css/editor.css';
  document.head.append(link);
};

class Editor {

  #ctn;
  #removerEstilos;
  #encapsularImagens;
  #recuarComTab;

  /**
   * @var {HTMLDivElement}
   */
  #controles;

  /**
   * @var {HTMLDivElement}
   */
  #conteudo;

  /**
   * @var {MutationObserver}
   */
  #observer;

  /**
   * @var {Range}
   */
  #range;

  /**
   * @var {HTMLDivElement}
   */
  #retratilLink;

  /**
   * @var {Function}
   */
  #pasteFilesListener;

  /**
   * @var {HTMLDivElement}
   */
  #bubble;

  /**
   *
   * @param ctn {HTMLDivElement}
   * @param removerEstilos {boolean}
   * @param encapsularImagens {boolean}
   * @param recuarComTab {boolean}
   */
  constructor(ctn, removerEstilos = true, encapsularImagens = true, recuarComTab = false) {
    this.#ctn = ctn;
    this.#removerEstilos = removerEstilos;
    this.#encapsularImagens = encapsularImagens;
    this.#recuarComTab = recuarComTab;
    this.#ctn.classList.add('editor');
    const conteudo = this.#ctn.innerHTML;
    load();
    document.addEventListener('selectionchange', this.#selectionchange);
    window.addEventListener('resize', this.#posicionaRetrateis);

    //language=html
    this.#ctn.innerHTML = `
        <div class="controles">
            <button class="negrito" title="Negrito (Ctrl + B)"></button>
            <button class="italico" title="Itálico (Ctrl + I)"></button>
            <button class="oculto tachado" title="Tachado"></button>
            <button class="limpar-formatacao" title="Limpar formatação (Ctrl + \\)"></button>
            <button class="oculto enviar-arquivo" title="Enviar arquivo (Ctrl + Shift + U)"></button>
            <button class="oculto pesquisar-arquivo" title="Inserir arquivo (Ctrl + Shift + F)"></button>
            <button class="linque" title="Inserir link (Ctrl + K)"></button>
            <button class="oculto h1" title="Título 1 (Ctrl + 1)"></button>
            <button class="oculto h2" title="Título 2 (Ctrl + 2)"></button>
            <button class="oculto h3" title="Título 3 (Ctrl + 3)"></button>
            <button class="oculto lista-nao-ordenada" title="Lista com marcadores"></button>
            <button class="oculto lista-ordenada" title="Lista numerada"></button>
            <button class="oculto alinhar-esquerda" title="Alinhar à esquerda"></button>
            <button class="oculto centralizar" title="Centralizar"></button>
            <button class="oculto alinhar-direita" title="Alinhar à direita"></button>
            <button class="oculto format-indent" title="Aumentar recuo (Tab)"></button>
            <button class="oculto format-outdent" title="Diminuir recuo (Shift + Tab)" )></button>
            <button class="oculto citacao" title="Citação"></button>
            <button class="oculto codigo" title="Código"></button>
        </div>
        <div class="conteudo" contenteditable="true" spellcheck="false"></div>

        <div class="link retratil">
            <div class="ctn-link">
                <input type="url" class="link" placeholder="Colar um link" required>
                <button class="flat">Aplicar</button>
            </div>
        </div>
    `;

    this.#controles = this.#ctn.querySelector('.controles');
    this.#conteudo = this.#ctn.querySelector('.conteudo');
    this.#retratilLink = this.#ctn.querySelector('.retratil.link');
    this.#conteudo.innerHTML = conteudo;
    this.#observer = new MutationObserver(() => {
      this.#registraEventosEdicao();
    });
    this.#observer.observe(this.#conteudo, {attributes: true, childList: true, subtree: true});

    this.#controles.querySelector('.negrito').addEventListener('click', this.#negrito);
    this.#controles.querySelector('.italico').addEventListener('click', this.#italico);
    this.#controles.querySelector('.tachado').addEventListener('click', this.#tachado);
    this.#controles.querySelector('.h1').addEventListener('click', this.#h1);
    this.#controles.querySelector('.h2').addEventListener('click', this.#h2);
    this.#controles.querySelector('.h3').addEventListener('click', this.#h3);
    this.#controles.querySelector('.alinhar-esquerda').addEventListener('click', this.#alinharEsquerda);
    this.#controles.querySelector('.centralizar').addEventListener('click', this.#centralizar);
    this.#controles.querySelector('.alinhar-direita').addEventListener('click', this.#alinharDireita);
    this.#controles.querySelector('.format-indent').addEventListener('click', this.#indent);
    this.#controles.querySelector('.format-outdent').addEventListener('click', this.#outdent);
    this.#controles.querySelector('.lista-nao-ordenada').addEventListener('click', this.#listaNaoOrdenada);
    this.#controles.querySelector('.lista-ordenada').addEventListener('click', this.#listaOrdenada);
    this.#controles.querySelector('.citacao').addEventListener('click', this.#citacao);
    this.#controles.querySelector('.codigo').addEventListener('click', this.#codigo);
    this.#controles.querySelector('.linque').addEventListener('click', this.#link);
    this.#controles.querySelector('.limpar-formatacao').addEventListener('click', this.#limpaFormatacao);
    this.#ctn.querySelectorAll('.retratil').forEach(el => {
      el.addEventListener('keydown', this.#onkeydownRetratil);
    });

    this.#retratilLink.querySelector('.flat').addEventListener('click', this.#insereLink);
    this.#retratilLink.querySelector('input').addEventListener('keydown', this.#keydownLink);

    this.#conteudo.addEventListener('keydown', this.#onkeydown);
    this.#conteudo.addEventListener('focus', this.#onfocus);
    this.#conteudo.addEventListener('blur', this.#onblur);
    this.#conteudo.addEventListener('scroll', this.#onscroll);
    this.#conteudo.addEventListener('paste', this.#onpaste);
    this.#hideRetrateis();
    this.#registraEventosEdicao();
    this.#conteudo.querySelectorAll('.bubble').forEach(bubble => {
      bubble.remove();
    });
    this.#bubble = document.createElement('div');
    this.#bubble.className = 'bubble shadow';
    this.#bubble.contentEditable = 'false';
    this.#conteudo.append(this.#bubble);
  }

  focus = (conteudo = false) => {
    if (conteudo) this.#conteudo.focus();
    this.#focaRange();
    this.#conteudo.scrollIntoView({block: 'center', inline: 'center'});
  };

  #selectionchange = ev => {
    const selection = window.getSelection();
    if (!this.#conteudo.contains(selection.focusNode)) return;
    this.#controles.querySelector('.negrito').classList.toggle('active', document.queryCommandState('bold'));
    this.#controles.querySelector('.italico').classList.toggle('active', document.queryCommandState('italic'));
    this.#controles.querySelector('.tachado').classList.toggle('active', document.queryCommandState('strikethrough'));
    this.#controles.querySelector('.h1').classList.toggle('active', this.#toggleTag(selection.focusNode, 'h1'));
    this.#controles.querySelector('.h2').classList.toggle('active', this.#toggleTag(selection.focusNode, 'h2'));
    this.#controles.querySelector('.h3').classList.toggle('active', this.#toggleTag(selection.focusNode, 'h3'));
    this.#controles.querySelector('.alinhar-esquerda').classList.toggle(
        'active',
        document.queryCommandState('justifyLeft'),
    );
    this.#controles.querySelector('.centralizar').classList.toggle(
        'active',
        document.queryCommandState('justifyCenter'),
    );
    this.#controles.querySelector('.alinhar-direita').classList.toggle(
        'active',
        document.queryCommandState('justifyRight'),
    );
    this.#controles.querySelector('.citacao').classList.toggle('active', this.#toggleCitacao(selection.focusNode));
    this.#controles.querySelector('.codigo').classList.toggle('active', this.#toggleTag(selection.focusNode, 'pre'));
    this.#controles.querySelector('.linque').classList.toggle('active', this.#toggleLink(selection.focusNode));
    this.#showAppropriatedBubble(selection.focusNode);
    this.#range = null;
  };

  /**
   *
   * @param node
   * @param tag
   * @returns {boolean|HTMLElement}
   */
  #blocoNo = (node, tag) => {
    let nd = node;
    while (nd) {
      if (nd.nodeType != Node.ELEMENT_NODE) {
        nd = nd.parentNode;
        continue;
      }
      if (nd.classList.contains('editor')) {
        return false;
      }
      if (nd.tagName == tag.toUpperCase()) {
        return nd;
      }
      nd = nd.parentNode;
    }
  };

  #estaNoBloco = (node, tag) => {
    return this.#blocoNo(node, tag) !== false;
  };

  #toggleTag = (focusNode, tag) => {
    if (this.#estaNoBloco(focusNode, tag)) {
      this.#controles.querySelector('.negrito').classList.remove('active');
      return true;
    }
    return false;
  };

  #toggleLink = focusNode => {
    if (focusNode.nodeType == Node.TEXT_NODE) {
      let parent = focusNode.parentNode;
      return parent.tagName == 'A';
    }
    return false;
  };

  #toggleFigure = focusNode => {
    return focusNode.nodeType == Node.ELEMENT_NODE && focusNode.tagName == 'FIGURE';
  };

  #toggleCitacao = focusNode => {
    const bloco = this.#blocoNo(focusNode, 'blockquote');
    if (!bloco) {
      return false;
    }
    if (bloco.classList.contains('quote')) {
      this.#controles.querySelector('.citacao').classList.remove('active');
      return true;
    }
  };

  #exeCmd = (comando, valor) => {
    this.#conteudo.focus();
    document.execCommand(comando, false, valor);
    this.#conteudo.focus();
    this.#selectionchange();
  };

  #negrito = () => {
    this.#exeCmd('bold');
  };

  #italico = () => {
    this.#exeCmd('italic');
  };

  #tachado = () => {
    this.#exeCmd('strikethrough');
  };

  #formataBloco = tag => {
    this.#conteudo.focus();
    const selection = window.getSelection();
    const bloco = this.#blocoNo(selection.focusNode, tag);
    if (bloco) {
      const div = document.createElement('div');
      div.innerHTML = bloco.innerHTML;
      bloco.replaceWith(div);
      const sel = window.getSelection();
      sel.selectAllChildren(div);
      sel.getRangeAt(0).collapse(false);
      return;
    }
    document.execCommand('formatBlock', false, tag);
    this.#selectionchange();
  };

  #h1 = () => {
    this.#formataBloco('h1');
  };

  #h2 = () => {
    this.#formataBloco('h2');
  };

  #h3 = () => {
    this.#formataBloco('h3');
  };

  #alinharEsquerda = () => {
    this.#exeCmd('justifyLeft');
    const selection = window.getSelection();
    const bloco = this.#blocoNo(selection.focusNode, 'div');
    if (!bloco) {
      return;
    }
    bloco.style.removeProperty('text-align');
    if (bloco.style.length == 0) {
      bloco.removeAttribute('style');
    }
  };

  #centralizar = () => {
    this.#exeCmd('justifyCenter');
  };

  #alinharDireita = () => {
    this.#exeCmd('justifyRight');
  };

  #indent = () => {
    this.#exeCmd('indent');
  };

  #outdent = () => {
    this.#exeCmd('outdent');
  };

  #listaNaoOrdenada = () => {
    this.#exeCmd('insertUnorderedList');
  };

  #listaOrdenada = () => {
    this.#exeCmd('insertOrderedList');
  };

  #citacao = () => {
    this.#formataBloco('blockquote');
    const blockquote = this.#blocoNo(window.getSelection().focusNode, 'blockquote');
    if (!blockquote) return;
    blockquote.classList.add('quote');
  };

  #codigo = () => {
    this.#formataBloco('pre');
  };

  #link = () => {
    if (this.#controles.querySelector('.linque').classList.contains('active')) {
      const sel = window.getSelection();
      sel.selectAllChildren(sel.focusNode.parentNode);
      document.execCommand('unlink');
      sel.collapseToEnd();
      return;
    }
    this.#showRetratil(this.#retratilLink);
    this.#retratilLink.querySelector('input.link').focus();
    this.#retratilLink.querySelector('input.link').value = '';
  };

  #limpaFormatacao = () => {
    this.#exeCmd('removeFormat');
    const sel = window.getSelection();
    const range = sel.getRangeAt(0);
    const docFrag = range.cloneContents();
    docFrag.querySelectorAll('*').forEach(el => {
      el.removeAttribute('style');
    });
    const div = document.createElement('div');
    div.append(docFrag);
    this.insertHtml(div.innerHTML);
  };

  #keydownLink = ev => {
    if (ev.key != 'Enter') return;
    ev.preventDefault();
    this.#insereLink();
  };

  #insereLink = () => {
    const inputUrl = this.#retratilLink.querySelector('.link');
    const url = inputUrl.value;
    if (inputUrl.validationMessage) {
      return;
    }
    this.#hideRetrateis();
    this.#focaRange();
    document.execCommand('createLink', false, url);
  };

  #onkeydown = ev => {
    const key = ev.key.toLowerCase();
    switch (key) {
      case 'tab':
        if (!this.#recuarComTab) return;
        ev.preventDefault();
        if (ev.shiftKey) {
          this.#exeCmd('outdent');
        } else {
          this.#exeCmd('indent');
        }
        break;
      case 'k':
        if (!ev.ctrlKey) return;
        ev.preventDefault();
        this.#link();
        break;
      case '1':
        if (!ev.ctrlKey) return;
        ev.preventDefault();
        this.#h1();
        break;
      case '2':
        if (!ev.ctrlKey) return;
        ev.preventDefault();
        this.#h2();
        break;
      case '3':
        if (!ev.ctrlKey) return;
        ev.preventDefault();
        this.#h3();
        break;
      case '\\':
        if (!ev.ctrlKey) return;
        this.#limpaFormatacao();
        break;
    }
  };

  #onkeydownRetratil = ev => {
    switch (ev.key) {
      case 'Escape':
        this.#focaRange();
    }
  };

  #focaRange = () => {
    if (!this.#range) return;
    //referência: https://gist.github.com/dantaex/543e721be845c18d2f92652c0ebe06aa
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(this.#range);
  };

  #onfocus = ev => {
    this.#hideRetrateis();
  };

  #onblur = ev => {
    this.#range = window.getSelection().getRangeAt(0);
  };

  #onscroll = ev => {

  };

  #onpaste = ev => {
    ev.preventDefault();
    const fd = new FormData();
    let hasFile = false;
    let hasTextHtml = false;
    for (let item of ev.clipboardData.items) {
      if (item.type == 'text/html') {
        hasTextHtml = true;
        item.getAsString(data => {
          this.insertHtml(data);
        });
      } else if (item.type == 'text/plain' && !ev.clipboardData.types.includes('text/html')) {
        //todo será que é por isso que arquivos de texto não estão sendo enviados via paste?
        item.getAsString(data => {
          this.insertHtml(data);
        });
      } else if (item.kind == 'file' && !hasTextHtml) {
        hasFile = true;
        const file = item.getAsFile();
        fd.append('files[]', file, file.name);
      }
    }
    if (hasFile && this.#pasteFilesListener) {
      this.#pasteFilesListener(fd);
    }
    this.#conteudo.focus();
  };

  #showRetratil = div => {
    const old = this.#ctn.querySelector('.retratil.open');
    if (old) {
      old.classList.remove('open');
    }
    div.classList.add('open');
    this.#posicionaRetrateis();
  };

  #hideRetrateis = () => {
    const domRect = this.#controles.getBoundingClientRect();
    this.#ctn.querySelectorAll('.retratil.open').forEach(div => {
      div.classList.remove('open');
      div.style.top = 'calc(var(--altura-header) + 1px)';
    });
    this.#posicionaRetrateis();
  };

  #posicionaRetrateis = () => {
    setTimeout(() => {
      const domRect = this.#controles.getBoundingClientRect();
      const top = domRect.top + document.body.scrollTop;
      const retratil = this.#ctn.querySelector('.retratil.open');
      if (retratil) {
        retratil.style.top = `${top + domRect.height - 2}px`;
        retratil.style.left = `${domRect.left}px`;
        retratil.style.width = `${domRect.width}px`;
      }
      this.#ctn.querySelectorAll('.retratil:not(.open)').forEach(div => {
        div.style.top = `calc(var(--altura-header) + 1px)`;
        div.style.left = `${domRect.left}px`;
        div.style.width = `${domRect.width}px`;
      });
    }, 25);
  };

  #showBuble = el => {
    this.#bubble.classList.add('show');
    const rect = el.getBoundingClientRect();
    const y = rect.y + el.offsetHeight / 2;
    if (y < innerHeight / 2) {
      this.#bubble.style.top = `${el.offsetTop + el.offsetHeight + 8}px`;
    } else {
      this.#bubble.style.top = `${el.offsetTop - 8 - this.#bubble.offsetHeight}px`;
    }
    if (matchMedia('(min-width: 768px)').matches) {
      this.#bubble.style.left = `${el.offsetLeft}px`;
      this.#bubble.style.right = `auto`;
    } else {
      this.#bubble.style.left = `1rem`;
      this.#bubble.style.right = `1rem`;
      this.#bubble.style.width = `auto`;
    }
  };

  #hideBuble = () => {
    this.#bubble.classList.remove('show');
  };

  #showAppropriatedBubble = focusNode => {
    if (this.#toggleLink(focusNode)) {
      this.#showBubbleLink(focusNode.parentNode);
    } else if (this.#toggleFigure(focusNode)) {
      const img = focusNode.querySelector('img');
      if (img) {
        this.#showBubbleImg(img);
      } else {
        this.#hideBuble();
      }
    } else {
      this.#hideBuble();
    }
  };

  #registraEventosEdicao = () => {
    this.#conteudo.querySelectorAll('img').forEach(img => {
      img.addEventListener('click', this.#clicaImagem);
    });
  };

  #clicaImagem = ev => {
    ev.preventDefault();
    this.#conteudo.blur();
    const img = ev.currentTarget;
    this.#showBubbleImg(img);
  };

  #showBubbleLink = a => {
    if (this.#bubble.contains(a)) return;
    //language=html
    this.#bubble.innerHTML = `
        <a href="${a.href}" target="_blank" style="display: block">Abrir</a>
    `;
    this.#bubble.querySelector('a').addEventListener('click', this.#hideBuble);
    this.#showBuble(a);
  };

  #showBubbleImg = img => {
    //language=html
    this.#bubble.classList.add('img');
    this.#bubble.innerHTML = `
        <a href="${img.src}" class="abrir" target="_blank">Abrir</a>
        <a class="remover">Remover</a>
    `;
    this.#bubble.querySelector('.abrir').addEventListener('click', this.#hideBuble);
    this.#bubble.querySelector('.remover').addEventListener('click', () => {
      this.#deleteImage(img);
    });
    this.#showBuble(img);
  };

  #deleteImage = img => {
    const sel = window.getSelection();
    sel.removeAllRanges();
    const range = new Range();
    range.selectNode(img.parentNode.parentNode);
    sel.addRange(range);
    this.#exeCmd('delete');
    this.#exeCmd('delete');
  };

  insertHtml = data => {
    data = data.replaceAll(/https:\/\/www\.youtube\.com\/watch\?v=([a-zA-Z0-9\-_]{11})/g, (p1, p2) => {
      //language=html
      return `
          <iframe class="yt"
                  src="https://www.youtube.com/embed/${p2}"
                  title="YouTube video player"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen>
          </iframe>
      `;
    });
    data = data.replaceAll(/https:\/\/youtu\.be\/([a-zA-Z0-9\-_]{11})/g, (p1, p2) => {
      //language=html
      return `
          <iframe class="yt"
                  src="https://www.youtube.com/embed/${p2}"
                  title="YouTube video player"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen>
          </iframe>
      `;
    });
    const template = document.createElement('template');
    template.innerHTML = data;
    if (this.#removerEstilos) {
      template.content.querySelectorAll('*').forEach(node => {
        if (node.nodeType != Node.ELEMENT_NODE) return;
        node.removeAttribute('style');
        node.removeAttribute('id');
        // node.removeAttribute('class');
      });
    }
    if (this.#encapsularImagens) {
      template.content.querySelectorAll('img').forEach(img => {
        const pai = img.parentNode;
        if (pai.tagName == 'FIGURE') return;
        const ctnFigure = document.createElement('div');
        ctnFigure.className = 'ctn-figure';
        const figure = document.createElement('figure');
        //language=html
        figure.innerHTML = `
            <figcaption>Informe a legenda</figcaption>
        `;
        figure.insertAdjacentElement('afterbegin', img);
        ctnFigure.append(figure);
        pai.append(ctnFigure);
        ctnFigure.insertAdjacentHTML('afterend', '<br><br>');
      });
    }
    this.#exeCmd('insertHTML', template.innerHTML);
    this.#registraEventosEdicao();
  };

  botaoNegrito = mostrar => {
    this.#controles.querySelector('.negrito').classList.toggle('oculto', !mostrar);
  };
  botaoItalico = mostrar => {
    this.#controles.querySelector('.italico').classList.toggle('oculto', !mostrar);
  };
  botaoTachado = mostrar => {
    this.#controles.querySelector('.tachado').classList.toggle('oculto', !mostrar);
  };
  botaoH1 = mostrar => {
    this.#controles.querySelector('.h1').classList.toggle('oculto', !mostrar);
  };
  botaoH2 = mostrar => {
    this.#controles.querySelector('.h2').classList.toggle('oculto', !mostrar);
  };
  botaoH3 = mostrar => {
    this.#controles.querySelector('.h3').classList.toggle('oculto', !mostrar);
  };
  botaoAlinharEsquerda = mostrar => {
    this.#controles.querySelector('.alinhar-esquerda').classList.toggle('oculto', !mostrar);
  };
  botaoCentralizar = mostrar => {
    this.#controles.querySelector('.centralizar').classList.toggle('oculto', !mostrar);
  };
  botaoAlinharDireita = mostrar => {
    this.#controles.querySelector('.alinhar-direita').classList.toggle('oculto', !mostrar);
  };
  botaoAumentarRecuo = mostrar => {
    this.#controles.querySelector('.format-indent').classList.toggle('oculto', !mostrar);
  };
  botaoDiminuirRecuo = mostrar => {
    this.#controles.querySelector('.format-outdent').classList.toggle('oculto', !mostrar);
  };
  botaoListaNaoOrdenada = mostrar => {
    this.#controles.querySelector('.lista-nao-ordenada').classList.toggle('oculto', !mostrar);
  };
  botaoListaOrdenada = mostrar => {
    this.#controles.querySelector('.lista-ordenada').classList.toggle('oculto', !mostrar);
  };
  botaoCitacao = mostrar => {
    this.#controles.querySelector('.citacao').classList.toggle('oculto', !mostrar);
  };
  botaoCodigo = mostrar => {
    this.#controles.querySelector('.codigo').classList.toggle('oculto', !mostrar);
  };
  botaoLinque = mostrar => {
    this.#controles.querySelector('.linque').classList.toggle('oculto', !mostrar);
    this.#retratilLink.classList.toggle('oculto', !mostrar);
  };
  botaoLimparFormatacao = mostrar => {
    this.#controles.querySelector('.limpar-formatacao').classList.toggle('oculto', !mostrar);
  };

  botaoEnviarArquivo = callback => {
    const mostrar = callback != undefined;
    this.#controles.querySelector('.enviar-arquivo').classList.toggle('oculto', !mostrar);
    if (mostrar) {
      this.#controles.querySelector('.enviar-arquivo').addEventListener('click', callback);
      this.#conteudo.addEventListener('keydown', ev => {
        if (ev.key == 'U' && ev.ctrlKey && ev.shiftKey) {
          callback();
        }
      });
    }
  };

  botaoPesquisarArquivo = callback => {
    const mostrar = callback != undefined;
    this.#controles.querySelector('.pesquisar-arquivo').classList.toggle('oculto', !mostrar);
    if (mostrar) {
      this.#controles.querySelector('.pesquisar-arquivo').addEventListener('click', callback);
      this.#conteudo.addEventListener('keydown', ev => {
        if (ev.key == 'F' && ev.ctrlKey && ev.shiftKey) {
          callback();
        }
      });
    }
  };

  addPasteFilesListener = callback => {
    this.#pasteFilesListener = callback;
  };

  html = () => {
    const template = document.createElement('template');
    template.innerHTML = this.#conteudo.innerHTML.trim();
    template.content.querySelectorAll('.bubble').forEach(bubble => {
      bubble.remove();
    });
    return template.innerHTML;
  };

  text = () => {
    return this.#conteudo.innerText.trim();
  };
}

export {
  Editor,
};