import {abre} from '../../core/templates/gaido/js/alerta.js';

const shareQrCode = async () => {
  try {
    const body = new URLSearchParams({
      link,
    });
    const res = await fetch('share-qrcode.php', {method: 'post', body});
    const blob = await res.blob();
    img.src = URL.createObjectURL(blob);
    document.querySelector('#qrcode').classList.remove('hidden');
    if (instrucoes) {
      document.querySelector('.botoes').classList.remove('hidden');
      document.querySelector('.instrucoes').classList.remove('hidden');
    }
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const img = document.querySelector('#qrcode img');
document.querySelector('#button-print').addEventListener('click', () => window.print());

shareQrCode().then();
