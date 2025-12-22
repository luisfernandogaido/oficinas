import *  as popover from './popover.js';

const abreDropdown = ev => {
  ev.stopPropagation();
  popover.hide();
  fechaDropdowns();
  import('./menu.js').then(menu => {
    menu.fecha();
  });
  const b = ev.currentTarget;
  const d = b.parentNode.querySelector('div');
  const rect = b.getBoundingClientRect();
  const y = (rect.top + rect.bottom) / 2;
  const x = (rect.left + rect.right) / 2;
  if (y < window.innerHeight / 2) {
    d.style.top = '0';
    d.style.bottom = 'auto';
  } else {
    d.style.top = 'auto';
    d.style.bottom = '0';
  }
  if (x < window.innerWidth / 2) {
    d.style.left = '0';
    d.style.right = 'auto';
  } else {
    d.style.left = 'auto';
    d.style.right = '0';
  }
  d.classList.add('aberto');
};

const fechaDropdown = ev => {
  ev.stopPropagation();
  const b = ev.currentTarget;
  setTimeout(() => {
    b.parentNode.classList.remove('aberto');
  }, 100);
};

const fechaDropdowns = () => {
  document.querySelectorAll('.buttons > div.aberto').forEach(dd => dd.classList.remove('aberto'));
};

const registraDropdowns = () => {
  document.querySelectorAll('.buttons > button, .buttons > a.button').forEach(b => {
    b.addEventListener('click', abreDropdown);
  });

  document.querySelectorAll('.buttons > div > button, .buttons > div > a.button').forEach(b => {
    b.addEventListener('click', fechaDropdown);
  });
};

const back = ev => {
  const cur = ev.currentTarget;
  const pai = cur.parentNode;
  if (pai.classList.contains('estatico')) return;
  if (history.length > 1) {
    window.history.back();
    return;
  }
  if ('home' in cur.dataset) {
    location.replace(SITE + 'app/index.php');
    return;
  }
  window.close();
};

const registraBotoesVoltar = () => {
  document.querySelectorAll('header:not(.estatico) button.voltar, button.back').forEach(b => {
    b.addEventListener('click', back);
  });
};

const registraRegioesBotoesNovo = () => {
  const btns = document.querySelectorAll('.tela :is(button.novo, a.button.novo, button.next, a.button.next, .butbot)');
  btns.forEach(b => {
    const tela = b.parentNode;
    if (!tela.classList.contains('tela')) return;
    if (tela.classList.contains('com-novo')) return;
    tela.classList.add('com-novo');
  });
};

document.body.addEventListener('click', fechaDropdowns);

const observer = new MutationObserver(() => {
  registraDropdowns();
  registraBotoesVoltar();
  registraRegioesBotoesNovo();
});
observer.observe(document.body, {attributes: true, childList: true, subtree: true});

registraDropdowns();
registraBotoesVoltar();
registraRegioesBotoesNovo();
if (document.querySelector('button.novo, a.button.novo, button.next, a.button.next, .butbot')) {
  document.getElementById('regiao-botoes').classList.remove('oculta');
}

export {fechaDropdowns};