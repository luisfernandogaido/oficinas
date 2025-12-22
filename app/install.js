if ('serviceWorker' in navigator) {
  navigator.serviceWorker.register('./sw.js');
}

/**
 * @type {Event}
 */
let deferredPrompt;
window.addEventListener('beforeinstallprompt', ev => {
  // ev.preventDefault();
  deferredPrompt = ev;
  document.querySelectorAll('button.instalar').forEach(b => {
    b.classList.remove('hidden');
  });
});

document.querySelectorAll('button.instalar').forEach(b => {
  b.addEventListener('click', ev => {
    ev.preventDefault();
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
  });
});