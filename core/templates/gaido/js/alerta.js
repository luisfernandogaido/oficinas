const ctn = document.querySelector('#alerta');
const ctnMsg = document.querySelector('#alerta .msg');
const ctnAct = document.querySelector('#alerta .act');
let timer;

const abre = (msg, seg = 3600, ...acoes) => {
  clearTimeout(timer);
  if (!seg) seg = 3600;
  ctnMsg.textContent = msg;
  ctnAct.innerHTML = '';
  acoes.forEach(act => {
    if (typeof act == 'string') {
      const a = document.createElement('a');
      a.textContent = act;
      a.addEventListener('click', fecha);
      ctnAct.appendChild(a);
      return;
    }
    if (!Array.isArray(act)) throw 'alerta: ação inválida';
    const a = document.createElement('a');
    a.textContent = act[0];
    if (typeof act[1] == 'string') {
      a.href = act[1];
      if (act[2]) {
        a.target = '_blank';
      }
      a.addEventListener('click', fecha);
      ctnAct.appendChild(a);
      return;
    }
    a.addEventListener('click', () => {
      fecha();
      act[1]();
    });
    ctnAct.appendChild(a);
  });
  ctnAct.style.display = ctnAct.childNodes.length ? 'flex' : 'none';
  ctn.style.bottom = -2*(ctn.offsetHeight) + 'px';
  setTimeout(() => {
    ctn.style.bottom = '1rem';
    ctn.classList.add('aberto');
    timer = setTimeout(fecha, 1000 * seg);
  }, 0);
};

const fecha = () => {
  clearTimeout(timer);
  setTimeout(() => {
    ctn.style.bottom = -2*(ctn.offsetHeight) + 'px';
    ctn.classList.remove('aberto');
  }, 0);
};

const pars = new URLSearchParams(location.search);
if (pars.has('erro')) {
  setTimeout(() => {
    abre(pars.get('erro'), 10, 'OK');
  }, 250);
}

if (pars.has('msg')) {
  setTimeout(() => {
    abre(pars.get('msg'), 10, 'OK');
  }, 250);
}

export { abre, fecha };