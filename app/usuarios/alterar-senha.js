import * as gd from '../../core/templates/gaido/js/gaido.js';
import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const altera = async () => {
  if (forms.reportaInvalidos(f)) return;
  try {
    alerta.abre('Alterando...');
    const r = await forms.post(f, 'altera-senha.php');
    if (r.erro) {
      alerta.abre(r.mensagem, 10, 'OK');
      return;
    }
    alerta.abre('Alterada!');
    setTimeout(() => {
      history.back();
    }, 1000);
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const validaSenha = () => {
  if (!senha.value || !senha2.value) return;
  if (senha.value != senha2.value) {
    forms.setValidade(senha, 'Senhas não coincidem');
    forms.setValidade(senha2, 'Senhas não coincidem');
    return;
  }
  forms.setValidade(senha, '');
  forms.setValidade(senha2, '');
};

const relaxa = () => {
  forms.oculta(old);
  old.required = false;

};

const f = document.querySelector('form');
const old = document.getElementById('old');
const senha = document.getElementById('senha');
const senha2 = document.getElementById('senha2');

forms.registra(f);
document.getElementById('b-alterar').addEventListener('click', altera);

senha.addEventListener('blur', validaSenha);
senha2.addEventListener('blur', validaSenha);

relaxa();