import * as alerta from '../../core/templates/gaido/js/alerta.js';
import {Trello} from '../../core/templates/gaido/js/trello.js';

const lista = async () => {
  try {
    clearTimeout(timer);
    const tarefas = document.querySelector('#tarefas');
    const pars = new URLSearchParams({
      txt: txt.value,
      arquivadas: arquivadas.value,
    });
    const res = await fetch('lista.php?' + pars);
    const r = await res.json();
    tarefas.innerHTML = '';
    r.forEach(l => {
      const tarefa = document.createElement('div');
      tarefa.dataset.codigo = l.codigo;
      tarefa.dataset.nome = l.nome;
      tarefa.classList.add('tarefa');
      if (l.started) {
        tarefa.classList.add('started');
      }

      //language=html
      tarefa.innerHTML = `
          <div class="textos">
              <div class="projeto"></div>
              <div class="nome"></div>
              <div class="cards"></div>
          </div>
          <div class="controles">
              <div class="tempo"></div>
              <button class="start"></button>
              <a class="button edit" href="tarefa.php?codigo=${l.codigo}"></a>
              <button class="archive"></button>
          </div>
      `;
      tarefa.querySelector('.projeto').textContent = l.projeto;
      tarefa.querySelector('.nome').textContent = l.nome;
      tarefa.querySelector('.nome').addEventListener('click', copyName);
      tarefa.querySelector('.tempo').textContent = l.tempo_total.substr(0, 5);
      tarefa.querySelector('.start').addEventListener('click', startStop);
      tarefa.querySelector('.archive').addEventListener('click', archive);
      if (l.arquivada == 1) {
        tarefa.querySelector('.archive').classList.add('oculto');
      }
      l.cards.forEach((c) => {
        const a = document.createElement('a');
        a.href = c;
        a.target = '_blank';
        tarefa.querySelector('.cards').append(a);
      });
      tarefas.append(tarefa);
    });
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  } finally {
    timer = setTimeout(lista, 60000);
  }
};

const changeArquivadas = () => {
  arquivadas.value = arquivadas.value == '0' ? '1' : 0;
  update();
  lista().then();
};

const update = () => {
  switch (arquivadas.value) {
    case '1':
      bArquivadas.textContent = 'Arquivadas';
      break;
    case '0':
      bArquivadas.textContent = 'Ativas';
      break;
  }
};

const startStop = async ev => {
  try {
    const tarefa = ev.currentTarget.parentNode.parentElement;
    const fd = new FormData();
    fd.append('codigo', tarefa.dataset.codigo);
    fd.append('acao', tarefa.classList.contains('started') ? 'stop' : 'start');
    const res = await fetch('start-stop.php', {
      method: 'POST',
      body: fd,
    });
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    tarefa.classList.toggle('started');
    document.querySelectorAll('#tarefas .tarefa').forEach(t => {
      if (t == tarefa) {
        return;
      }
      t.classList.remove('started');
    });
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const tecla = ev => {
  clearTimeout(keyTimer);
  keyTimer = setTimeout(lista, 750);
};

const getTreloConf = async () => {
  const res = await fetch('trello-conf.php');
  trelloConf = await res.json();
  if (!trelloConf.token) return;
  trello = new Trello(trelloConf.key, trelloConf.token);
};

const teclaWindow = async ev => {
  if ((ev.key == 'v' || ev.key == 'V') && ev.ctrlKey) {
    const text = await navigator.clipboard.readText();
    if (!text.includes('https://trello.com/c/')) return;
    alerta.abre('Inserindo tarefa...');
    const cardId = text.replace('https://trello.com/c/', '').substr(0, 8);
    const linkTrello = text.substr(0, 29);
    await insereTarefa(cardId, linkTrello);
  }
};

const insereTarefa = async (cardId, text) => {
  try {
    console.log(cardId, text);
    const card = await trello.card(cardId);
    const board = await trello.cardBoard(cardId);
    const nome = card.name;
    const boardName = board.name;
    const codProjeto = trelloConf.boards_projetos[boardName] || trelloConf.boards_projetos['default'];
    const body = new URLSearchParams({
      cod_projeto: codProjeto,
      nome: nome,
      link_trello: text,
    });
    const res = await fetch('insere-tarefa.php', {
      method: 'POST',
      body,
    });
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem);
      return;
    }
    alerta.fecha();
    await lista();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const archive = async ev => {
  try {
    const l = ev.currentTarget.parentNode.parentNode;
    const res = await fetch('arquiva.php?codigo=' + l.dataset.codigo);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    l.remove();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const copyName = ev => {
  const tarefa = ev.currentTarget.parentElement.parentElement;
  if (!('timer' in tarefa)) {
    tarefa.timer = null;
  }
  navigator.clipboard.writeText(tarefa.dataset.nome).then(() => {
    const divNome = tarefa.querySelector('.nome');
    divNome.classList.add('copy');
    setTimeout(() => {
      divNome.classList.remove('copy');
    }, 375);
  }, () => {
    alerta.abre('Erro ao copiar nome para a área de transferência.', 10, 'OK');
  });
};

const anima = divNome => {
};

const txt = document.querySelector('#txt');
const arquivadas = document.querySelector('#arquivadas');
const bArquivadas = document.querySelector('button.arquivadas');
let timer;
let keyTimer;
let trelloConf;

/**
 * @type {Trello}
 */
let trello;
bArquivadas.addEventListener('click', changeArquivadas);
txt.addEventListener('input', tecla);
window.addEventListener('keydown', teclaWindow);
update();
getTreloConf().then(lista);