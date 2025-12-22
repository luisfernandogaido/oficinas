import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const salva = async () => {
  if (forms.reportaInvalidos(f)) return;
  try {
    alerta.abre('salvando...');
    await forms.post(f, 'salva.php');
    window.history.back();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');

forms.registra(f);
document.querySelector('button.primario').addEventListener('click', salva);