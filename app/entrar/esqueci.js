import * as forms from '../../core/templates/gaido/js/forms.js';
import * as gd from '../../core/templates/gaido/js/gaido.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const envia = async () => {
  try {
    if (forms.reportaInvalidos(f)) return;
    alerta.abre('Enviando...');
    await forms.post(f, 'reset.php');
    //language=html
    p.innerHTML = `
        Enviamos um link de recuperação para
    `;
    gd.hide(document.querySelector('.botoes'));
    gd.hide(document.querySelector('form .campos'));
    email.textContent = document.getElementById('email').value;
    gd.show(email);
    alerta.fecha();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const f = document.querySelector('form');
const bEnviar = document.getElementById('b-enviar');
const p = document.querySelector('form p');
const email = document.querySelector('form .email');
forms.registra(f);
bEnviar.addEventListener('click', envia);
document.querySelector('#b-voltar-entrar').addEventListener('click', ()=>{
  location.replace('index.php');
})
