import * as svg from './svg.js';
import * as texto from '../../templates/gaido/js/texto.js';

const touch = 'ontouchstart' in window;

let larguraTotal = 1000;
let alturaTotal = 1000;

let cores = [
  '#659353',
  '#fec200',
  '#ac352d',
];

function setCores (arrCores) {
  cores = arrCores;
}

/**
 *
 * @param {HTMLElement} gb
 */
function arranjaTamanhos (gb) {
  const eixoX = gb.querySelector('.x');
  const gap = gb.querySelector('.y2');
  gap.style.flexBasis = eixoX.offsetHeight + 'px';
}

function posicionaHint (ev) {
  if (ev.pageY < innerHeight / 2) {
    hint.style.top = ev.pageY + 'px';
  } else {
    hint.style.top = (ev.pageY - hint.offsetHeight) + 'px';
  }
  if (ev.pageX < innerWidth / 2) {
    hint.style.left = ev.pageX + 'px';
  } else {
    hint.style.left = (ev.pageX - hint.offsetWidth) + 'px';
  }
}

/**
 *
 * @param {MouseEvent} ev
 */
function sobreBarra (ev) {
  const barra = ev.currentTarget;
  hint.classList.remove('oculto');
  if (barra.gb.opt.hint) {
    hint.innerHTML = '';
    hint.appendChild(barra.gb.opt.hint(ev));
  } else {
    if (barra.gb.opt.type == 'float') {
      hint.textContent = texto.moeda(barra.dados.elemento.valor);
    } else {
      hint.textContent = Math.round(barra.dados.elemento.valor).toString();
    }
  }
  posicionaHint(ev);
}

/**
 *
 * @param {MouseEvent} ev
 */
function moveBarra (ev) {
  posicionaHint(ev);
}

/**
 *
 * @param {MouseEvent} ev
 */
function saiBarra (ev) {
  hint.classList.add('oculto');
}

/**
 *
 * @type {{multiplo: number, cores: Array, hint: function, nY: number, click: function}}
 */
const defaultOpt = {
  /**
   * @var {function} Função que é chamada ao clicar sobre uma barra. Recebe ev como parâmetro.
   */
  click: null,

  /**
   * @var {function} Função que é chamada ao passar o mouse sobre uma barra. Recebe ev como parâmetro.
   * Deve retornar o elemento que será apresentado no hint.
   */
  hint: null,

  /**
   * @var {number} Número de divisões no eixo y.
   */
  nY: 10,

  /**
   * @var {number} Número pelo qual o maior valor do eixo y deve ser divisível.
   */
  multiplo: 50,

  /**
   * @var {Array} Relação de cores utilizadas no gráfico se o JSON de dados não contiver cores especificadas.
   */
  cores: [],

  /**
   * @var {boolean} Por padrão, o conteúdo do rótulo é texto. Se verdadeiro, é html.
   */
  rotuloHtml: false,
  type: 'float'

};

/**
 *
 * @param {HTMLElement} gb
 * @param {Object} opt
 * @param {Array} dados
 */
function cria (gb, dados = [], opt = defaultOpt) {
  gb.opt = Object.assign(defaultOpt, opt);
  if (gb.opt.cores.length) {
    setCores(gb.opt.cores);
  }
  gb.classList.add('grafico-barras');
  fetch(window.SITE + 'core/js/svg/barra.html').then(res => res.text()).then(html => {
    gb.innerHTML = html;
    window.addEventListener('resize', () => {
      arranjaTamanhos(gb);
    });
    popula(gb, dados);
  });
}

/**
 * @param {HTMLElement} gb
 * @param {Array} dados estruturados com o formato como no exemplo:
 *
 [
 {
    "rotulo": "Janeiro/2019",
    "valores": [
      [{"valor": 7730, "cor": "#72a23c"}],
      [{"valor": 1145.8, "cor": "#6ec4df"}],
    ]
  },
 {
    "rotulo": "Fevereiro/2019",
    "valores": [
      [{"valor": 1730, "cor": "#82a23c"}],
      [{"valor": 2145.8, "cor": "#7ec4df"}],
    ]
  }
 ]
 *
 *  Todos os valores dos campos são exemplos.
 *  Todos os arrays podem conter quantidade variada de elementos.
 *  Todos os campos do exemplo possuem significado para o gráfico, sendo que `cor` é o único opcional.
 *  Todos os objetos podem ter campos adicionais.
 *  Cada barra do gráfico conterá um campo chamado `dados` que contém os campos `periodo`, `serie` e `elemento`.
 */
function popula (gb, dados) {
  if (!dados.length) {
    return;
  }
  const g = gb.querySelector('svg');
  while (g.firstChild) {
    g.removeChild(g.firstChild);
  }
  const larguraGrade = larguraTotal / dados.length;
  const series = dados[0].valores.length;
  const larguraBarra = larguraGrade / (series + 1);
  const margemBarra = larguraBarra / (series + 1);
  const margemSuperior = alturaTotal / gb.opt.nY;
  const alturaDisponivel = alturaTotal - margemSuperior;
  const eixoX = gb.querySelector('.x');
  eixoX.innerHTML = '';
  let maiorValor = 0;
  for (let d of dados) {
    for (let pilha of d.valores) {
      let acumaldo = 0;
      for (let valor of pilha) {
        acumaldo += valor.valor;
      }
      maiorValor = Math.max(maiorValor, acumaldo);
    }
  }
  const multiplo = gb.opt.multiplo || gb.opt.nY;
  maiorValor = Math.ceil(maiorValor / multiplo) * multiplo;
  const eixoY = gb.querySelector('.y1');
  eixoY.innerHTML = '';
  for (let i = gb.opt.nY; i > 0; i--) {
    let d = document.createElement('div');
    let valor = (maiorValor * i / gb.opt.nY);
    if (gb.opt.type == 'float') {
      d.textContent = texto.moeda(valor);
    } else {
      d.textContent = Math.round(valor).toString();
    }
    d.style.flexBasis = (100 / gb.opt.nY) + '%';
    eixoY.appendChild(d);
    let line = svg.criaElemento('line');
    let y = margemSuperior * i;
    line.setAttribute('class', 'grade');
    line.setAttribute('x1', '0');
    line.setAttribute('y1', y.toString());
    line.setAttribute('x2', larguraTotal.toString());
    line.setAttribute('y2', y.toString());
    g.appendChild(line);
  }
  for (let i = 0; i < dados.length; i++) {
    let x = larguraGrade * (i + 1);
    let line = svg.criaElemento('line');
    line.setAttribute('class', 'grade');
    line.setAttribute('x1', x.toString());
    line.setAttribute('y1', '0');
    line.setAttribute('x2', x.toString());
    line.setAttribute('y2', alturaTotal.toString());
    g.appendChild(line);
    let rotulo = document.createElement('div');
    if (gb.opt.rotuloHtml) {
      rotulo.innerHTML = dados[i].rotulo;
    } else {
      rotulo.textContent = dados[i].rotulo;
    }
    rotulo.style.flexBasis = (100 / dados.length) + '%';
    eixoX.appendChild(rotulo);
    for (let j = 0; j < series; j++) {
      const xBarra = larguraGrade * i + margemBarra * (j + 1) + j * larguraBarra;
      let acumulado = 0;
      const elementos = dados[i].valores[j].length;
      const escalaOpacidade = 1 / elementos;
      for (let k = 0; k < dados[i].valores[j].length; k++) {
        let hBarra = (alturaTotal * (dados[i].valores[j][k].valor / maiorValor));
        if (hBarra < 0) {
          hBarra = 0;
        }
        const barra = svg.criaElemento('rect');
        barra.gb = gb;
        const yBarra = margemSuperior + alturaDisponivel - hBarra - acumulado;
        acumulado += hBarra;
        if (dados[i].valores[j][k].cor) {
          barra.setAttribute('fill', dados[i].valores[j][k].cor);
        } else {
          barra.setAttribute('fill', cores[j % cores.length]);
          barra.style.opacity = (1 - escalaOpacidade * k).toString();
        }
        barra.setAttribute('x', xBarra.toString());
        barra.setAttribute('y', yBarra.toString());
        barra.setAttribute('width', larguraBarra.toString());
        barra.setAttribute('height', hBarra.toString());
        barra.addEventListener('mouseover', sobreBarra);
        barra.addEventListener('mousemove', moveBarra);
        barra.addEventListener('mouseout', saiBarra);
        barra.dados = {
          periodo: dados[i],
          serie: dados[i].valores[j],
          elemento: dados[i].valores[j][k],
        };
        if (gb.opt.click) {
          barra.addEventListener('click', ev => {
            if (touch) {
              hint.classList.add('oculto');
            }
            gb.opt.click(ev);
          });
        }
        g.appendChild(barra);
      }
    }
  }
  arranjaTamanhos(gb);
}

const hint = document.createElement('div');
hint.className = 'grafico-barras-hint oculto';
document.body.appendChild(hint);

export { cria, popula, setCores };