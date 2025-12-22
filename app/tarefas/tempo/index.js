import * as alerta from '../../../core/templates/gaido/js/alerta.js';
import { addTime } from '../../../core/templates/gaido/js/time.js';

const TTL = 3600000;

const pad = n => {
  return n.toString().padStart(2, '0');
};

const update = () => {
  bModo.textContent = modos.get(modo.value).rotulo;
  const partes = dia.value.split('-');
  const data = new Date(dia.value);
  switch (modo.value) {
    case 'dia':
      periodo.textContent = `${partes[2]}/${partes[1]} ${diasSemana.get(data.getUTCDay())}`;
      break;
    case 'semana':
      data.setUTCDate(data.getUTCDate() - data.getUTCDay());
      const domingo = `${pad(data.getUTCDate())}/${pad(data.getUTCMonth() + 1)}`;
      data.setUTCDate(data.getUTCDate() + 6);
      const sabado = `${pad(data.getUTCDate())}/${pad(data.getUTCMonth() + 1)}`;
      periodo.textContent = `${domingo} - ${sabado}`;
      break;
    case 'mes':
      periodo.textContent = `${meses.get(partes[1])} de ${partes[0]}`;
      break;
    case 'ano':
      periodo.textContent = `${data.getUTCFullYear()}`;
      break;
  }
};

const nextModo = () => {
  modo.value = modos.get(modo.value).next;
  update();
  load().then();
  save();
};

const before = () => {
  const d = new Date(dia.value);
  switch (modo.value) {
    case 'dia':
      d.setUTCDate(d.getUTCDate() - 1);
      break;
    case 'semana':
      d.setUTCDate(d.getUTCDate() - 7);
      break;
    case 'mes':
      d.setUTCMonth(d.getUTCMonth() - 1);
      break;
    case 'ano':
      d.setUTCMonth(d.getUTCMonth() - 12);
      break;
  }
  dia.value = d.toISOString().substr(0, 10);
  update();
  load().then();
  save();
};

const next = () => {
  const d = new Date(dia.value);
  switch (modo.value) {
    case 'dia':
      d.setUTCDate(d.getUTCDate() + 1);
      break;
    case 'semana':
      d.setUTCDate(d.getUTCDate() + 7);
      break;
    case 'mes':
      d.setUTCMonth(d.getUTCMonth() + 1);
      break;
    case 'ano':
      d.setUTCMonth(d.getUTCMonth() + 12);
      break;
  }
  dia.value = d.toISOString().substr(0, 10);
  update();
  load().then();
  save();
};

const hoje = () => {
  const date = new Date();
  const dateUTC = Date.UTC(date.getFullYear(), date.getMonth(), date.getDate());
  dia.value = new Date(dateUTC).toISOString().substr(0, 10);
  update();
  load().then();
  save();
};

const load = async () => {
  const pars = new URLSearchParams({
    modo: modo.value,
    dia: dia.value,
  });
  const projetos = document.querySelector('#projetos');
  projetos.innerHTML = ``;
  try {
    alerta.abre('carregando...');
    const res = await fetch(`load.php?` + pars);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    alerta.fecha();
    projetos.innerHTML = ``;
    let tempoTotal = '00:00:00';
    r.forEach(l => {
      const projeto = document.createElement('div');
      projeto.className = 'projeto';
      projeto.dataset.codigo = l.cod_projeto;
      projeto.dataset.tempo = l.tempo;
      tempoTotal = addTime(tempoTotal, l.tempo);
      //language=html
      projeto.innerHTML = `
          <div class="nome"></div>
          <div class="tempo"></div>
          <a class="button view"
             href="projeto.php?codigo=${projeto.dataset.codigo}&dia=${dia.value}"
             target="_blank">
          </a>
          <div class="detalhes"></div>
      `;
      projeto.querySelector('.nome').textContent = l.projeto;
      projeto.querySelector('.tempo').textContent = l.tempo;
      projeto.querySelector('.nome').addEventListener('click', showHide);
      projeto.querySelector('.tempo').addEventListener('click', showHide);
      projetos.append(projeto);
    });
    const sec = document.createElement('section');
    //language=html
    sec.innerHTML = `
        <div class="texto">Total</div>
        <div class="tempo"></div>
    `;
    sec.querySelector('.tempo').textContent = tempoTotal;
    projetos.append(sec);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const showHide = ev => {
  const projeto = ev.currentTarget.parentNode;
  projeto.classList.toggle('show');
  if (!projeto.classList.contains('show')) return;
  loadShow(projeto).then();
};

const loadShow = async projeto => {
  const pars = new URLSearchParams({
    modo: modo.value,
    dia: dia.value,
    cod_projeto: projeto.dataset.codigo,
  });
  const detalhes = projeto.querySelector('.detalhes');
  try {
    const res = await fetch('load-tarefas.php?' + pars);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    detalhes.innerHTML = ``;
    r.forEach(l => {
      const tarefa = document.createElement('div');
      tarefa.dataset.codigo = l.cod_tarefa;
      tarefa.className = 'tarefa';

      //language=html
      tarefa.innerHTML = `
          <div class="textos">
              <div class="nome"></div>
              <div class="cards"></div>
          </div>
          <a class="button open-in-new" href="../tarefa.php?codigo=${l.cod_tarefa}"></a>
          <div class="tempo"></div>
      `;
      tarefa.querySelector('.nome').textContent = l.tarefa;
      tarefa.querySelector('.tempo').textContent = l.tempo;
      const cards = tarefa.querySelector('.cards');
      l.cards.forEach(c => {
        const a = document.createElement('a');
        a.target = '_blank';
        a.href = c;
        a.textContent = c;
        cards.append(a);
      });
      detalhes.append(tarefa);
    });
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }

};

const save = () => {
  const pars = {
    modo: modo.value,
    dia: dia.value,
    ts: (new Date()).getTime(),
  };
  window.localStorage.setItem('tarefas-tempo', JSON.stringify(pars));
};

const teclaWindow = async ev => {
  switch (ev.key) {
    case 'ArrowLeft':
      before();
      break;
    case 'ArrowRight':
      next();
      break;
    case 'd':
    case 'D':
      modo.value = 'dia';
      update();
      load().then();
      save();
      break;
    case 's':
    case 'S':
      modo.value = 'semana';
      update();
      load().then();
      save();
      break;
    case 'm':
    case 'M':
      modo.value = 'mes';
      update();
      load().then();
      save();
      break;
    case 'a':
    case 'A':
      modo.value = 'ano';
      update();
      load().then();
      save();
      break;
    case 'h':
    case 'H':
      hoje();
      break;
  }
  console.log(ev.key);
};

const modos = new Map([
  ['dia', { rotulo: 'Dia', next: 'semana' }],
  ['semana', { rotulo: 'Semana', next: 'mes' }],
  ['mes', { rotulo: 'Mês', next: 'ano' }],
  ['ano', { rotulo: 'Ano', next: 'dia' }],
]);

const meses = new Map([
  ['01', 'Janeiro'],
  ['02', 'Fevereiro'],
  ['03', 'Março'],
  ['04', 'Abril'],
  ['05', 'Maio'],
  ['06', 'Junho'],
  ['07', 'Julho'],
  ['08', 'Agosto'],
  ['09', 'Setembro'],
  ['10', 'Outubro'],
  ['11', 'Novembro'],
  ['12', 'Dezembro'],
]);

const diasSemana = new Map([
  [0, 'dom'],
  [1, 'seg'],
  [2, 'ter'],
  [3, 'qua'],
  [4, 'qui'],
  [5, 'sex'],
  [6, 'sáb'],
]);

const modo = document.querySelector('#modo');
const dia = document.querySelector('#dia');

const bHoje = document.querySelector('#b-hoje');
const bModo = document.querySelector('#b-modo');
const bBefore = document.querySelector('#controles .navigate-before');
const bNext = document.querySelector('#controles .navigate-next');
const periodo = document.querySelector('#periodo');

bHoje.addEventListener('click', hoje);
bModo.addEventListener('click', nextModo);
bBefore.addEventListener('click', before);
bNext.addEventListener('click', next);
window.addEventListener('keydown', teclaWindow);

let item = window.localStorage.getItem('tarefas-tempo');
if (item) {
  const pars = JSON.parse(item);
  if ((new Date()).getTime() - pars.ts < TTL) {
    modo.value = pars.modo;
    dia.value = pars.dia;
  }
}
update();
load().then();