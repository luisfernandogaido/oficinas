import {abre} from '../../core/templates/gaido/js/alerta.js';

const copy = () => {
  navigator.clipboard.writeText(link).then(() => {
    abre('Copiado!', 2);
  });
};

const share = () => {
  navigator.share({
    url: link,
    text: texto,
  }).then();
};

document.querySelector('#share .copy').addEventListener('click', copy);
document.querySelector('#share .share').addEventListener('click', share);
