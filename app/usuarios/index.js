import {loadState, saveState, updateChecks} from '../../core/templates/gaido/js/gaido.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';
import * as texto from '../../core/templates/gaido/js/texto.js';
import {abre} from '../../core/templates/gaido/js/alerta.js';

const update = () => {
  if (master && document.querySelector('#filtro input[name="conta"]:checked').value) {
    document.querySelector('button.filter').classList.add('filtered');
    return;
  }
  if (document.querySelector('#filtro input[name="perfil"]:checked').value) {
    document.querySelector('button.filter').classList.add('filtered');
    return;
  }
  document.querySelector('button.filter').classList.remove('filtered');
};

const filtrar = () => {
  location.hash = '#filtro';
};

const okFiltro = () => {
  history.back();
};

const ls = () => {
  const state = loadState('usuarios', 1000 * 60 * 5);
  if (!state) return;
  document.querySelector('#search').value = state.search;
  if (master) {
    document.querySelector(`#filtro input[name="conta"][value="${state.cod_conta}"]`).checked = true;
  }
  document.querySelector(`#filtro input[name="perfil"][value="${state.perfil}"]`).checked = true;
  updateChecks();
};

const ss = () => {
  saveState('usuarios', {
    search: document.querySelector('#search').value,
    cod_conta: master ? document.querySelector('#filtro input[name="conta"]:checked').value : '',
    perfil: document.querySelector('#filtro input[name="perfil"]:checked').value,
  });
};

const load = async () => {
  try {
    document.querySelector('#resultado').innerHTML = '<div class="carregando"></div>';
    const pars = new URLSearchParams({
      search: document.querySelector('#search').value,
      cod_conta: master ? document.querySelector('#filtro input[name="conta"]:checked').value : '',
      perfil: document.querySelector('#filtro input[name="perfil"]:checked').value,
    });
    const res = await fetch('load.php?' + pars.toString());
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.erro, 10, 'OK');
      return;
    }
    //language=html
    document.querySelector('#resultado').innerHTML = `
        <div class="resultados"></div>
        <table class="cards mob">
            <tbody></tbody>
        </table>
    `;
    document.querySelector('#resultado .resultados').textContent = texto.resultados(r.length);
    document.querySelector('#resultado .resultados').dataset.n = r.length;
    const t = document.querySelector('#resultado tbody');
    r.forEach(l => {
      const tr = document.createElement('tr');
      tr.dataset.codigo = l.codigo;
      tr.dataset.nome = l.nome;
      tr.dataset.celular = l.celular;
      //language=html
      tr.innerHTML = `
          <td class="codigo num" data-th=""></td>
          <td class="conta" data-th=""></td>
          <td class="nome" data-th=""></td>
          <td class="perfil" data-th=""></td>
          <td class="status" data-th=""></td>
          <td class="criacao" data-th=""></td>
          <td class="acoes">
              <a href="usuario.php?codigo=${l.codigo}" class="button edit"></a>
              <a href="personifica.php?codigo=${l.codigo}" class="button person"></a>
              <button class="share"></button>
              <button class="delete-red"></button>
          </td>
      `;
      tr.querySelector('.codigo').textContent = l.codigo;
      tr.querySelector('.conta').textContent = l.conta;
      tr.querySelector('.nome').textContent = l.nome;
      tr.querySelector('.perfil').textContent = l.perfil;
      tr.querySelector('.status').textContent = l.nome_status;
      tr.querySelector('.criacao').textContent = l.criacao;
      tr.querySelector('button.share').addEventListener('click', share);
      tr.querySelector('button.delete-red').addEventListener('click', exclui);
      if (!master && !adminPersonifica) {
        tr.querySelector('a.button.person').classList.add('hidden');
      }
      if (!master) {
        tr.querySelector('.conta').classList.add('hidden');
      }
      t.append(tr);
    });

  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const inputSearch = () => {
  clearTimeout(timer);
  timer = setTimeout(ssLoad, 750);
};

const ssLoad = () => {
  ss();
  update();
  load().then();
};

const exclui = async ev => {
  try {
    const tr = ev.currentTarget.parentElement.parentElement;
    const res = await fetch('exclui.php?codigo=' + tr.dataset.codigo);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.erro, 10, 'OK');
      return;
    }
    tr.remove();
    const n = parseInt(document.querySelector('#resultado .resultados').dataset.n) - 1;
    document.querySelector('#resultado .resultados').dataset.n = n.toString();
    document.querySelector('#resultado .resultados').textContent = texto.resultados(n);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const share = ev => {
  linha = ev.currentTarget.parentNode.parentNode;
  document.querySelector('#share .nome').textContent = linha.dataset.nome;
  location.hash = '#share';
};

const saibaMais = () => {
  document.querySelector('#share .mais').classList.remove('hidden');
  document.querySelector('#share .saiba-mais').classList.add('hidden');
};

const compartilhaEmail = async () => {
  try {
    history.back();
    alerta.abre('Enviando...');
    const pars = new URLSearchParams();
    pars.set('codigo', linha.dataset.codigo);
    pars.set('tipo', 'email');
    const res = await fetch('share.php?' + pars);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    alerta.abre('Enviado', 2);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const compartilhaWhats = async () => {
  try {
    history.back();
    const pars = new URLSearchParams();
    pars.set('codigo', linha.dataset.codigo);
    pars.set('tipo', 'whats');
    const res = await fetch('share.php?' + pars);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    const text = 'Olá, ' + linha.dataset.nome + '. Acesse o aplicativo clicando no link ' +
        window.SITE + 'app/usuarios/token-use.php?token=' + r.token;
    const pars2 = new URLSearchParams();
    pars2.set('phone', '55' + linha.dataset.celular);
    pars2.set('text', text);
    const url = 'https://api.whatsapp.com/send?' + pars2;
    window.open(url);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const compartilhaLink = async () => {
  try {
    history.back();
    const pars = new URLSearchParams();
    pars.set('codigo', linha.dataset.codigo);
    pars.set('tipo', 'copylink');
    const res = await fetch('share.php?' + pars);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    navigator.clipboard.writeText(r.link).then(() => {
      abre('Copiado!', 2);
    });
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const tecla = ev => {
  if (ev.ctrlKey && ev.key == ',') {
    filtrar();
  }
};

let timer;
let linha;

document.querySelector('button.filter').addEventListener('click', filtrar);
document.querySelector('#filtro button.primario').addEventListener('click', okFiltro);
document.querySelector('#search').addEventListener('input', inputSearch);
document.querySelectorAll('#filtro input[type="radio"]').forEach(el => {
  el.addEventListener('click', () => {
    document.querySelector('#filtro button.primario').focus();
    ssLoad();
  });
});
document.querySelector('#share .email').addEventListener('click', compartilhaEmail);
document.querySelector('#share .copylink').addEventListener('click', compartilhaLink);
document.querySelector('#share .whatsapp').addEventListener('click', compartilhaWhats);
document.querySelector('button.saiba-mais').addEventListener('click', saibaMais);
window.addEventListener('keydown', tecla);
ls();
update();
load().then();
document.querySelector('#search').focus();