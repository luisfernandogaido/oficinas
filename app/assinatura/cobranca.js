import {registraAll} from '../../core/templates/gaido/js/forms.js';
import {abre} from '../../core/templates/gaido/js/alerta.js';

const cancela = async () => {
  try {
    abre('Cancelando...');
    const res = await fetch('cancela.php');
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    abre('Cancelada!', 10, 'OK');
    setTimeout(() => {
      location.replace('../index.php');
    }, 3000);
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const checa = async () => {
  try {
    clearTimeout(timer);
    const res = await fetch('status.php');
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    if (r.status == 'pendente') {
      timer = setTimeout(checa, 5000);
    } else {
      location.replace('../index.php');
    }
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    checa().then();
  }
});

let timer;

registraAll();
document.querySelector('#button-cancelar').addEventListener('click', cancela);

checa().then();