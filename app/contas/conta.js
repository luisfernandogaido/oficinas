import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const salva = async () => {
  if (forms.reportaInvalidos(f)) {
    alerta.abre('Corrija o formulário.', 10, 'OK');
    return;
  }
  try {
    alerta.abre('Salvando...');
    const r = await forms.post(f, 'salva.php');
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    alerta.abre('Salvo.');
    history.back();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');
forms.registra(f);
document.querySelector('button.primario').addEventListener('click', salva);