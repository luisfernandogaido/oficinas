import {registraAll} from '../../core/templates/gaido/js/forms.js';
import {whatsAppLinkOpen, whatsAppLinkMobile} from '../../core/templates/gaido/js/whatsapp.js';

const focus = ev => ev.currentTarget.select();
const input = ev => {
  const digitos = '0123456789';
  const caixa = ev.currentTarget;
  const value = caixa.value;
  let newValue = '';
  for (let i = 0; i < value.length; i++) {
    const c = value[i];
    if (!digitos.includes(c)) continue;
    newValue += c;
  }
  if (newValue.startsWith('0')) newValue = newValue.substring(1);
  if (newValue.length == 11 || newValue.length == 10) {
    geraLink(newValue);
  } else {
    link = null;
    document.querySelector('#qrcode img').classList.add('hidden');
  }
};

const geraLink = async numero => {
  link = whatsAppLinkMobile(numero, msg);
  const pars = new URLSearchParams({link});
  document.querySelector('#qrcode img').src = 'qrcode.php?' + pars.toString();
  document.querySelector('#qrcode img').classList.remove('hidden');
};

const abre = () => {
  const digitos = '0123456789';
  const caixa = document.querySelector('#numero');
  const value = caixa.value;
  let newValue = '';
  for (let i = 0; i < value.length; i++) {
    const c = value[i];
    if (!digitos.includes(c)) continue;
    newValue += c;
  }
  if (newValue.startsWith('0')) newValue = newValue.substring(1);
  whatsAppLinkOpen(newValue, msg);
};

const msg = 'Olá, tudo bem?';
let link;

document.querySelector('#numero').addEventListener('focus', focus);
document.querySelector('#numero').addEventListener('input', input);
document.querySelector('#button-abre').addEventListener('click', abre);

registraAll();