const pars = new URLSearchParams(location.search);
if (pars.has('registrar-conta')) {
  setTimeout(() => {
    location.href = 'usuarios/registrar.php?registrar-conta=1';
  }, 500);
}