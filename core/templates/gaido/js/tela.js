const update = arg => {
  const primeira = document.querySelector('.tela');
  const temTelaSolta = document.querySelector('main > header') != undefined;
  const hash = location.hash;
  if (!hash) {
    if (primeira && !temTelaSolta) show(primeira);
    if (temTelaSolta) {
      document.querySelectorAll('.tela').forEach(tela => tela.classList.remove('show'));
      document.body.classList.remove('moda');
    }
    return;
  }
  if (hash.includes('=')) {
    return;
  }
  const tela = document.querySelector(location.hash);
  if (tela) {
    show(tela);
    if (typeof arg == 'boolean' && arg && tela.classList.contains('moda')) {

      //todo remover comentário back
      // history.back();

    }
    return;
  }
  if (primeira && !temTelaSolta) {
    show(primeira);
  }
};

const esc = ev => {
  if (ev.key != 'Escape') return;
  history.back();
};

const back = ev => {
  history.back();
};

const cancelEsc = ev => {
  ev.stopPropagation();
};

const show = tela => {
  document.querySelectorAll('.tela').forEach(tela => tela.classList.remove('show'));
  if (!tela.classList.contains('moda')) {
    document.body.classList.remove('moda');
  } else {
    document.body.classList.add('moda');
    tela.addEventListener('keydown', esc);
    tela.addEventListener('mousedown', back);
    tela.querySelector('div').addEventListener('mousedown', cancelEsc);
  }
  tela.classList.add('show');
  if (!tela.classList.contains('moda')) {
    setTimeout(() => {
      document.documentElement.scrollTop = 0;
    }, 0);
  }
  if (!tela.classList.contains('moda')) return;
  setTimeout(() => {
    const primeiroControle = tela.querySelector('button');
    if (primeiroControle) primeiroControle.focus();
  }, 10);
};

window.addEventListener('popstate', update);

update(true);