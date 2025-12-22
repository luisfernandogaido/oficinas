function posRelMouse (ev) {

  /**
   * @type {HTMLElement}
   */
  const rect = ev.currentTarget.getBoundingClientRect();
  return {
    x: ev.clientX - rect.left,
    y: ev.clientY - rect.top,
  };
}

function criaElemento (tag) {
  return document.createElementNS('http://www.w3.org/2000/svg', tag);
}

/**Converte coordenadas em pixels para a escala vetorial utilizada no svg.
 *
 * @param {SVGAElement} g
 * @param {number} x
 * @param {number} y
 */
function pixVec (g, x, y) {
  let vb = g.getAttribute('viewBox').split(' ');
  const rect = g.getBoundingClientRect();
  const w = rect.width;
  const h = rect.height;
  const vpw = vb[2];
  const vph = vb[3];
  return {
    x: vpw * x / w,
    y: vph * y / h,
  };
}

/**
 * Permite que um SVG seja escalável e movível, semelhante ao Google Maps.
 * @param {SVGAElement} g
 */
function zoomable (g) {
  let vb = g.getAttribute('viewBox').split(' ');
  g.dataset.x = vb[0];
  g.dataset.y = vb[1];
  g.dataset.w = vb[2];
  g.dataset.h = vb[3];
  g.addEventListener('mousedown', ev => {
    const g = ev.currentTarget;
    const pr = posRelMouse(ev);
    g.posIni = pr;
    g.vbIni = g.getAttribute('viewBox').split(' ');
  }, false);
  g.addEventListener('mouseup', ev => {
    const g = ev.currentTarget;
    delete g.posIni;
    delete g.vbIni;
    g.classList.remove('move');
  });
  g.addEventListener('mousemove', ev => {
    const g = ev.currentTarget;
    if (!g.posIni) {
      return;
    }
    g.classList.add('move');
    const pr = posRelMouse(ev);
    const dx = pr.x - g.posIni.x;
    const dy = pr.y - g.posIni.y;
    let vb = g.getAttribute('viewBox').split(' ');
    const d = pixVec(g, dx, dy);
    vb[0] = (parseFloat(g.vbIni[0]) - parseFloat(d.x)).toString();
    vb[1] = (parseFloat(g.vbIni[1]) - parseFloat(d.y)).toString();
    g.setAttribute('viewBox', vb.join(' '));
  });
}

export {
  criaElemento,
  pixVec,
  zoomable,
};