const version = 'v2';
console.log(version);
self.addEventListener('install', ev => {
  console.log('Service Worker instalado');
});
self.addEventListener('activate', ev => {
  console.log('Service Worker ativado');
});
self.addEventListener('fetch', ev => {

});
