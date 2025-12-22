import {addTime} from '../../../core/templates/gaido/js/time.js';

let tempoTotal = '00:00:00';
document.querySelectorAll('table tr').forEach(tr => {
  tempoTotal = addTime(tempoTotal, tr.dataset.tempo);
});
document.querySelector('#total').textContent = tempoTotal;