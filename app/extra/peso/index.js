import * as forms from '../../../core/templates/gaido/js/forms.js';
import * as alerta from '../../../core/templates/gaido/js/alerta.js';
import * as texto from '../../../core/templates/gaido/js/texto.js';

const salva = async () => {
  try {
    if (forms.reportaInvalidos(f)) return;
    alerta.abre('Salvando...');
    const r = await forms.post(f, 'salva.php');
    alerta.fecha();
    renderiza([r]);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const lista = async () => {
  try {
    const res = await fetch('lista.php');
    const r = await res.json();
    renderiza(r);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const renderiza = dados => {
  //language=html
  const tr = `
      <td class="data"></td>
      <td class="peso num"></td>
      <td class="delta"></td>
      <td class="acoes">
          <button class="delete"></button>
      </td>
  `;
  let ultimo = 0;
  let ultimaLinha = document.querySelector('tbody tr');
  if (ultimaLinha) {
    ultimo = parseFloat(ultimaLinha.dataset.peso);
  }
  dados.forEach(p => {
    const delta = p.peso - ultimo;
    ultimo = p.peso;
    const l = document.createElement('tr');
    l.dataset.codigo = p.codigo;
    l.dataset.peso = p.peso;
    l.innerHTML = tr;
    l.querySelector('.data').textContent = texto.data(p.data);
    l.querySelector('.peso').textContent = texto.numero(p.peso, 1);
    l.querySelector('.delta').textContent = texto.numero(delta, 1);
    l.querySelector('.delete').addEventListener('click', exclui);
    t.insertAdjacentElement('afterbegin', l);
  });
};

const exclui = async ev => {
  try {
    const l = ev.currentTarget.parentNode.parentNode;
    const res = await fetch(`exclui.php?codigo=${l.dataset.codigo}`);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    l.parentNode.removeChild(l);
    console.log(r);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');
const t = document.querySelector('tbody');
forms.registra(f);
document.querySelector('.primario').addEventListener('click', salva);
document.querySelector('#peso').addEventListener('focus', ev => {
  ev.currentTarget.select();
});

lista().then();