import * as forms from '../../core/templates/gaido/js/forms.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';

const salva = async () => {
  if (forms.reportaInvalidos(f)) {
    alerta.abre('Corrija o formulário.', 10, 'OK');
    return;
  }
  try {
    alerta.abre('Salvando...');
    await forms.post(f, 'salva.php');
    alerta.abre('Salvo', 1.5);
    history.back();
  } catch (e) {
    alerta.abre(e, 10);
  }
};

const opcoesSenha = () => {
  if (document.getElementById('codigo').value == '0') {
    forms.mostra(senha);
    senha.required = true;
    senha.name = 'senha';
    forms.mostra(senha2);
    senha2.required = true;
    senha2.name = 'senha2';
    bSenha.parentNode.classList.add('oculto');
    return;
  }
  bSenha.parentNode.classList.remove('oculto');
  senha.value = '';
  senha2.value = '';
  forms.setValidade(senha, '');
  forms.setValidade(senha2, '');
  if (bSenha.textContent == 'Trocar senha') {
    forms.oculta(senha);
    senha.required = false;
    senha.name = '';
    forms.oculta(senha2);
    senha2.required = false;
    senha2.name = '';
    return;
  }
  forms.mostra(senha);
  senha.required = true;
  senha.name = 'senha';
  senha2.name = 'senha2';
  senha2.required = true;
  forms.mostra(senha2);
};

const toogleSenha = () => {
  bSenha.textContent = bSenha.textContent == 'Trocar senha' ? 'Manter senha' : 'Trocar senha';
  opcoesSenha();
};

const validaSenha = () => {
  if (!senha.value || !senha2.value) return;
  if (senha.value != senha2.value) {
    forms.setValidade(senha, 'Senhas não coincidem');
    forms.setValidade(senha2, 'Senhas não coincidem');
    return;
  }
  forms.setValidade(senha, '');
  forms.setValidade(senha2, '');
};

const f = document.querySelector('form');
const bSenha = document.getElementById('b-senha');
const senha = document.getElementById('senha');
const senha2 = document.getElementById('senha2');

forms.registra(f);
bSenha.addEventListener('click', toogleSenha);
document.getElementById('b-salvar').addEventListener('click', salva);
senha.addEventListener('blur', validaSenha);
senha2.addEventListener('blur', validaSenha);

opcoesSenha();