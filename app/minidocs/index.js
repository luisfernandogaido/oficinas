import {abre} from '../../core/templates/gaido/js/alerta.js';
import {loadState, saveState} from '../../core/templates/gaido/js/gaido.js';

const list = async () => {
  try {
    ss();
    const pars = new URLSearchParams({
      search: document.querySelector('#search').value,
    });
    const res = await fetch('list.php?' + pars.toString());
    const html = await res.text();
    const pars2 = new URLSearchParams({
      search: document.querySelector('#search').value,
    });
    if (pars.toString() != pars2.toString()) return;
    document.querySelector('#docs').innerHTML = html;
    document.querySelectorAll('#docs .delete-red').forEach(b => {
      b.addEventListener('click', exclui);
    });

  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const exclui = async ev => {
  try {
    ev.preventDefault();
    ev.stopPropagation();
    const b = ev.currentTarget;
    const doc = b.closest('.doc');

    const body = new URLSearchParams({
      hash: doc.dataset.hash,
    });
    const res = await fetch('delete.php', {method: 'post', body});
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    doc.remove();
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const ss = () => {
  saveState('minidocs', {
    search: document.querySelector('#search').value,
  });
};

const ls = () => {
  return loadState('minidocs', 5 * 60 * 1000);
};

const inputSearch = () => {
  clearTimeout(timer);
  timer = setTimeout(list, 500);
};

let timer;

document.querySelector('#search').addEventListener('input', inputSearch);

const state = ls();
if (state) {
  document.querySelector('#search').value = state.search;
}

list().then();