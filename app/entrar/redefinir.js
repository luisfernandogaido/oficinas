import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const envia = async () => {
  try {
    if (forms.reportaInvalidos(f)) return;
    alerta.abre('Enviando...');
    await forms.post(f, 'altera-senha.php');
    const qs = new URLSearchParams();
    qs.set('msg', 'Senha alterada');
    location.href = '../index.php?' + qs.toString();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const valida = () => {
  if (!senha.value || !senha2.value) return;
  if (senha.value != senha2.value) {
    forms.setValidade(senha, 'Senhas não coincidem');
    forms.setValidade(senha2, 'Senhas não coincidem');
    return;
  }
  forms.setValidade(senha);
  forms.setValidade(senha2);
};

const f = document.querySelector('form');
const senha = document.getElementById('senha');
const senha2 = document.getElementById('senha2');
forms.registra(f);
document.querySelector('#b-enviar').addEventListener('click', envia);
senha.addEventListener('blur', valida);
senha2.addEventListener('blur', valida);