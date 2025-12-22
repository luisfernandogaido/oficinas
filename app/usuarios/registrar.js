import {post, registraAll, reportaInvalidos} from '../../core/templates/gaido/js/forms.js';
import {abre} from '../../core/templates/gaido/js/alerta.js';

const registra = async () => {
  try {
    if (reportaInvalidos(f)) return;
    await post(f, 'registra.php');
    location.hash = '#validacao-email';
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const enviaCodigoValidacao = async () => {
  try {
    if (reportaInvalidos(f2)) return;
    const r = await post(f2, 'envia-codigo-validacao.php');
    if (r.sucesso) {
      if (from == 'assine') {
        location.replace('../assinatura/assine.php');
      } else {
        location.href = '../index.php';
      }
      return;
    }
    if (!r.repetir) {
      history.back();
      document.querySelector('#nome').value = '';
      document.querySelector('#email').value = '';
      document.querySelector('#senha').value = '';
      document.querySelector('#celular').value = '';
      abre('Número de tentativas se esgotou. Tente refazer o registro.', 10, 'OK');
      return;
    }
    document.querySelector('#codigo-validacao').value = '';
    abre('Código inválido. Tente novamente.', 10, 'OK');
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const f = document.querySelector('#form-usuario');
const f2 = document.querySelector('#form-validacao');

document.querySelector('#botao-registrar').addEventListener('click', registra);
document.querySelector('#botao-enviar-codigo-validacao').addEventListener('click', enviaCodigoValidacao);
registraAll();

if (whatsAppValidado) {
  document.querySelector('#campo-whatsapp').classList.add('hidden');
}

if (location.hash != '') {
  history.back();
}