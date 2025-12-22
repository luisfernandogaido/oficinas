import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';
import * as gd from '../../core/templates/gaido/js/gaido.js';

const registra = async () => {
  try {
    if (forms.reportaInvalidos(f)) return;
    await forms.post(f, 'registra.php');
    document.getElementById('p-email').textContent = document.getElementById('email').value;
    gd.hide(document.getElementById('passo1'));
    gd.show(document.getElementById('passo2'));
    localStorage.setItem('si', 'criada');
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');

forms.registra(f);
document.querySelector('button.primario').addEventListener('click', registra);
document.querySelector('#b-ja-tem-conta')?.addEventListener('click', () => {
  location.replace('index.php');
});

document.addEventListener('visibilitychange', () => {
  if (document.visibilityState === 'visible') {
    if (logado) {
      location.replace('index.php');
    }
  }
});

if (logado) {
  location.replace('index.php');
}