const registra = () => {
  document.querySelectorAll('[data-popover]').forEach(el => {
    el.addEventListener('click', show);
  });
  document.querySelectorAll('.popover').forEach(pop => {
    pop.addEventListener('click', stop);
    const style = getComputedStyle(pop);
    if (pop.dataset.popoverRegistered != 'true') {
      if (style.maxWidth != 'none') pop.dataset.maxWidth = style.maxWidth;
      if (style.maxHeight != 'none') pop.dataset.maxHeight = style.maxHeight;
      pop.dataset.popoverRegistered = 'true';
    }
    if (!pop.observer) pop.observer = observaPop(pop);
  });
};

const stop = ev => {
  ev.stopPropagation();
};

const show = ev => {
  ev.stopPropagation();
  const el = ev.currentTarget;
  const popover = document.querySelector(`#${el.dataset.popover}`);
  popover.classList.add('show');
  theEle = el;
  thePop = popover;
  arrange();
};

const hide = () => {
  document.querySelectorAll('.popover').forEach(pop => {
    pop.classList.remove('show');
  });
  theEle = null;
  thePop = null;
};

const arrange = () => {
  if (!theEle || !thePop) return;
  const rect = theEle.getBoundingClientRect();
  const centerX = rect.x + rect.width / 2;
  const centerY = rect.y + rect.height / 2;
  if (!thePop.dataset.maxWidth) thePop.style.maxWidth = 'none';
  if (!thePop.dataset.maxHeight) thePop.style.maxHeight = 'none';
  if (centerX < innerWidth / 2) {
    thePop.style.left = `${rect.left}px`;
    thePop.style.right = 'auto';
    if (!thePop.dataset.maxWidth) thePop.style.maxWidth = `${innerWidth - rect.left - 16}px`;
    if (centerY < innerHeight / 2) {
      thePop.style.top = `${rect.bottom + 4}px`;
      thePop.style.bottom = 'auto';
      if (!thePop.dataset.maxHeight) thePop.style.maxHeight = `${innerHeight - (rect.bottom + 4) - 16}px`;
    } else {
      thePop.style.top = 'auto';
      thePop.style.bottom = `${innerHeight - rect.top + 4}px`;
      if (!thePop.dataset.maxHeight) thePop.style.maxHeight = `${rect.top - 16}px`;
    }
  } else {
    thePop.style.left = 'auto';
    thePop.style.right = `${innerWidth - rect.right}px`;
    if (!thePop.dataset.maxWidth) thePop.style.maxWidth = `${rect.right - 16}px`;
    if (centerY < innerHeight / 2) {
      thePop.style.top = `${rect.bottom + 4}px`;
      thePop.style.bottom = 'auto';
      if (!thePop.dataset.maxHeight) thePop.style.maxHeight = `${innerHeight - (rect.bottom + 4) - 16}px`;
    } else {
      thePop.style.top = 'auto';
      thePop.style.bottom = `${innerHeight - rect.top + 4}px`;
      if (!thePop.dataset.maxHeight) thePop.style.maxHeight = `${rect.top - 16}px`;
    }
  }
};

const tecla = ev => {
  if (ev.key != 'Escape') return;
  hide();
};

const observaPop = pop => {
  pop.timer = null;
  const observer = new MutationObserver(() => {
    clearTimeout(pop.timer);
    pop.timer = setTimeout(arrange, 25);
  });
  observer.observe(pop, {childList: true, subtree: true});
  return observer;
};

let theEle;
let thePop;

const observer = new MutationObserver(registra);

document.body.addEventListener('click', hide);
window.addEventListener('resize', arrange);
window.addEventListener('scroll', arrange);
window.addEventListener('keydown', tecla);
observer.observe(document.body, {attributes: true, childList: true, subtree: true});
registra();

export {hide};