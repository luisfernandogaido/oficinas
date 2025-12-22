import * as alerta from '../../core/templates/gaido/js/alerta.js';

const exclui = async ev => {
  const l = ev.currentTarget.parentNode.parentElement;
  try {
    alerta.abre('excluindo...');
    const res = await fetch('exclui.php?codigo=' + l.dataset.codigo);
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.mensagem);
      return;
    }
    alerta.fecha();
    l.remove();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

document.querySelectorAll('table .delete').forEach(b => {
  b.addEventListener('click', exclui);
});