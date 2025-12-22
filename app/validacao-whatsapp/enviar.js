import {registraAll} from '../../core/templates/gaido/js/forms.js';
import {whatsAppLinkOpen} from '../../core/templates/gaido/js/whatsapp.js';

const update = () => {
  if (estado == 'aguardando_envio') {
    document.querySelector('#msg-inicial')?.classList.remove('hidden');
    document.querySelector('#msg-espera')?.classList.add('hidden');
    document.querySelector('#msg-resultado')?.classList.add('hidden');
    if (document.querySelector('form button.primario')) {
      document.querySelector('form button.primario').textContent = 'Enviar mensagem de validação';
    }
  } else if (estado == 'aguardando_resposta') {
    document.querySelector('#msg-inicial')?.classList.add('hidden');
    document.querySelector('#msg-espera')?.classList.remove('hidden');
    document.querySelector('#msg-resultado')?.classList.add('hidden');
    if (document.querySelector('form button.primario')) {
      document.querySelector('form button.primario').textContent = 'Reenviar mensagem';
    }
  }
};

const enviaMensagem = () => {
  const mensagem = `Código de validação: ${token}`;
  whatsAppLinkOpen(destinatario, mensagem);
  estado = 'aguardando_resposta';
  update();
  fetch('envia.php').
      then(res => res.json()).
      then(r => {
        console.log(r);
      });
};

registraAll();
document.querySelector('form button.primario')?.addEventListener('click', enviaMensagem);

let estado = 'aguardando_envio';

update();