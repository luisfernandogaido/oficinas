import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';
import {reloadCache} from '../../core/templates/gaido/js/gaido.js';

const entra = async () => {
  if (forms.reportaInvalidos(f)) return;
  try {
    alerta.abre('Entrando...');
    await forms.post(f, 'entra.php');
    await reloadCache();
    location.href = url;
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const esqueci = () => {
  location.replace('esqueci.php');
};

const criar = () => {
  location.replace('criar.php');
};

const pars = new URLSearchParams(location.search);
const url = pars.get('url') || '../index.php';
const f = document.querySelector('form');
forms.registra(f);
document.getElementById('b-entrar').addEventListener('click', entra);
document.querySelector('#b-esqueci').addEventListener('click', esqueci);
if (document.querySelector('#b-criar')) {
  document.querySelector('#b-criar').addEventListener('click', criar);
}

reloadCache().then();