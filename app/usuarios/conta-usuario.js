import {abre} from '../../core/templates/gaido/js/alerta.js';

const inputNome = () => {
  clearTimeout(timer);
  timer = setTimeout(salva, 750);
};

const salva = async () => {
  try {
    const body = new URLSearchParams({
      nome: document.querySelector('#nome').value,
    });
    const res = await fetch('conta-usuario-salva.php', {method: 'post', body});
    const r = await res.json();
    if(r.erro){
      abre(r.erro, 10, 'OK');
    }
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

let timer;

document.querySelector('#nome').addEventListener('input', inputNome);

