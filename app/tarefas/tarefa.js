import * as alerta from '../../core/templates/gaido/js/alerta.js';
import * as forms from '../../core/templates/gaido/js/forms.js';
import * as gd from '../../core/templates/gaido/js/gaido.js';

const salva = async () => {
  if (forms.reportaInvalidos(f)) return;
  try {
    alerta.abre('salvando...');
    const r = await forms.post(f, 'salva.php');
    alerta.fecha();
    document.querySelector('#codigo').value = r.id;
    window.history.back();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const exclui = async () => {
  try {
    alerta.abre('excluindo...');
    const r = await forms.post(f, 'exclui.php');
    alerta.fecha();
    window.history.back();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const update = () => {
  if (started.value == 1) {
    f.classList.add('started');
  } else {
    f.classList.remove('started');
  }
};

const pasteTrello = async () => {
  const text = await navigator.clipboard.readText();
  if (!text.includes('https://trello.com')) return;
  const card = document.createElement('div');
  card.className = 'trello-card';
  //language=html
  card.innerHTML = `
      <input type="hidden" name="trello-card[]" value="">
      <a href="" target="_blank"></a>
      <button class="close"></button>
  `;
  card.querySelector('input').value = text;
  card.querySelector('a').href = text;
  card.querySelector('a').textContent = text;
  card.querySelector('.close').addEventListener('click', removeTrello);
  document.querySelector('#trello-cards').append(card);
};

const removeTrello = ev => {
  const b = ev.currentTarget;
  b.parentNode.remove();
};

const startStop = async ev => {
  try {
    const fd = new FormData();
    fd.append('codigo', document.querySelector('#codigo').value);
    fd.append('acao', f.classList.contains('started') ? 'stop' : 'start');
    const res = await fetch('start-stop.php', {
      method: 'POST',
      body: fd,
    });
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    started.value = started.value == '0' ? '1' : '0';
    update();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const tempoTotal = async () => {
  const codigo = document.querySelector('#codigo');
  if (codigo.value == '0') {
    return;
  }
  try {
    clearTimeout(timer);
    const res = await fetch('tempo-total.php?codigo=' + codigo.value);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    document.querySelector('#tempo').textContent = r.tempo_total.substr(0, 5);
    started.value = r.is_started ? '1 ' : '0';
    update();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  } finally {
    timer = setTimeout(tempoTotal, 60000);
  }
};

const arquiva = async () => {
  try {
    const res = await fetch('arquiva.php?codigo=' + codigo.value);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    window.history.back();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');
const started = document.querySelector('#is-started');
const bStart = document.querySelector('button.start');
const bExcluir = document.querySelector('#b-excluir');
const bArquivar = document.querySelector('#b-arquivar');
let timer;
if (bExcluir) {
  bStart.addEventListener('click', startStop);
  bExcluir.addEventListener('click', exclui);
  if (bArquivar) {
    bArquivar.addEventListener('click', arquiva);
  }
}
f.addEventListener('keydown', ev => {
  if (ev.key == 'Enter') {
    ev.preventDefault();
    salva().then();
    return;
  }
});

forms.registra(f);
document.querySelector('button.primario').addEventListener('click', salva);
document.querySelector('button.paste').addEventListener('click', pasteTrello);
document.querySelectorAll('#trello-cards .close').forEach(b => {
  b.addEventListener('click', removeTrello);
});
update();
tempoTotal().then();