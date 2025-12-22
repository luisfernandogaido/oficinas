const observer = new MutationObserver(lista => {
  registra();
});

const registra = () => {
  document.querySelectorAll('[data-title]').forEach(el => {
    if (el.tooltip) return;
    const tt = document.createElement('div');
    tt.className = 'tooltip';

    //language=html
    tt.innerHTML = `
        <div class="arrow"></div>
        <div class="content"></div>
    `;

    document.body.append(tt);
    el.tooltip = tt;
    el.addEventListener('mouseover', sobre);
    el.addEventListener('mouseout', sai);
  });
};

const sobre = ev => {
  const el = ev.currentTarget;
  const tt = el.tooltip;
  tt.querySelector('.content').textContent = el.dataset.title;
  const rect = el.getBoundingClientRect();
  el.tooltip.classList.add('show');
  if (rect.y < innerHeight / 2) {
    tt.querySelector('.arrow').classList.remove('down');
    tt.querySelector('.arrow').classList.add('up');
    tt.style.top = `${rect.y + rect.height + 2}px`;
    tt.style.left = `${rect.x + (rect.width - tt.offsetWidth) / 2}px`;
  } else {
    tt.querySelector('.arrow').classList.add('down');
    tt.querySelector('.arrow').classList.remove('up');
    tt.style.top = `${rect.y - tt.offsetHeight - 2}px`;
    tt.style.left = `${rect.x + (rect.width - tt.offsetWidth) / 2}px`;
  }
  if (!window.matchMedia('(min-width: 768px)').matches) {
    tt.style.left = '1rem';
    tt.style.right = '1rem';
    tt.querySelector('.arrow').classList.remove('up');
    tt.querySelector('.arrow').classList.remove('down');
  }
  const rectTt = tt.getBoundingClientRect();
  if(rectTt.x < 0){
    tt.style.left = '1rem';
  }
};

const sai = ev => {
  const el = ev.currentTarget;
  el.tooltip.classList.remove('show');
};

observer.observe(document.body, { attributes: true, childList: true, subtree: true });
registra();

window.addEventListener('scroll', () => {
  const ttShowed = document.querySelector('.tooltip.show');
  if (!ttShowed) return;
  ttShowed.classList.remove('show');
});