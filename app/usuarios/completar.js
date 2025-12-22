import {post, registraAll, reportaInvalidos} from '../../core/templates/gaido/js/forms.js';
import {abre} from '../../core/templates/gaido/js/alerta.js';

const salva = async () => {
  try {
    const f = document.querySelector('form');
    if (reportaInvalidos(f)) return;
    await post(f, 'completa.php');
    if (from == 'assine') {
      location.replace('../assinatura/assine.php');
    } else {
      location.href = '../index.php';
    }
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

registraAll();

document.querySelector('button.primario').addEventListener('click', salva);