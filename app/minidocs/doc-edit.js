import {abre, fecha} from '../../core/templates/gaido/js/alerta.js';
import {autoHeight} from '../../core/templates/gaido/js/forms.js';
import {Upload} from '../../core/templates/gaido/js/upload.js';

const update = () => {
  registerEvents();
};

const inputName = () => {
  clearTimeout(timer);
  timer = setTimeout(rename, 750);
};

const rename = async () => {
  try {
    const body = new URLSearchParams({
      hash: hash, name: document.querySelector('#name').value,
    });
    const res = await fetch('rename.php', {method: 'post', body});
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    console.log('salvo');
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const exclui = async () => {
  try {
    const body = new URLSearchParams({
      hash: hash,
    });
    abre('Deletando...');
    const res = await fetch('delete.php', {method: 'post', body});
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    history.back();
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const focaName = () => {
  if (document.querySelector('#name').value != 'Documento sem título') return;
  document.querySelector('#name').select();
};

const addText = async text => {
  try {
    let idSel = '';
    const sel = document.querySelector('#minidocs .card.sel');
    if (sel) idSel = sel.dataset.id;
    const body = new URLSearchParams({hash, text, idSel});
    const res = await fetch('add-text.php', {method: 'post', body});
    const html = await res.text();
    if (sel) {
      sel.insertAdjacentHTML('beforebegin', html);
    } else {
      document.querySelector('#minidocs').insertAdjacentHTML('beforeend', html);
    }
    update();
    const textarea = document.querySelector('#minidocs textarea[data-focar="1"]');
    if (!textarea) return;
    textarea.dataset.focar = '0';
    textarea.focus();
    textarea.scrollIntoView({'block': 'center'});
    return textarea.closest('.card.text');
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const addEmptyText = async () => {
  const div = await addText('');
};

const saveText = async ev => {
  try {
    ev.stopPropagation();
    const card = ev.currentTarget.closest('.card.text');
    const text = card.querySelector('textarea').value;
    const body = new URLSearchParams({
      id: card.dataset.id, hash, text,
    });
    const res = await fetch('save-text.php', {method: 'post', body});
    const html = await res.text();
    card.outerHTML = html;
    update();
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const editText = ev => {
  ev.stopPropagation();
  const card = ev.currentTarget.closest('.card.text');
  card.classList.add('edit');
  card.classList.remove('sel');
  update();
  card.querySelector('textarea').focus();
  card.querySelector('textarea').scrollIntoView({'block': 'center'});
};

const cancelText = ev => {
  ev.stopPropagation();
  const card = ev.currentTarget.closest('.card.text');
  card.classList.remove('edit');
  update();
};

const typeText = ev => {
  if (ev.key != 'Enter' || !ev.ctrlKey) return;
  saveText(ev).then();
};

const diliti = async ev => {
  try {
    ev.stopPropagation();
    const card = ev.currentTarget.closest('.card');
    const body = new URLSearchParams({
      id: card.dataset.id, hash, hash_file: card.dataset.hashFile ?? '',
    });
    card.remove();
    const res = await fetch('diliti.php', {method: 'post', body});
    const r = await res.json();
    if (r.erro) {
      abre(r.erro, 10, 'OK');
      return;
    }
    update();
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const seleciona = ev => {
  const card = ev.currentTarget;
  if (card.classList.contains('edit')) {
    card.classList.remove('sel');
    return;
  }
  if (card.classList.contains('sel')) {
    card.classList.remove('sel');
    return;
  }
  document.querySelectorAll('#minidocs .card.sel').forEach(el => el.classList.remove('sel'));
  card.classList.add('sel');
};

const cancelGlobalPaste = ev => {
  ev.stopPropagation();
};

const registerEvents = () => {
  document.querySelectorAll('#minidocs .card').forEach(el => {
    el.addEventListener('click', seleciona);
  });
  document.querySelectorAll('#minidocs .card.text textarea').forEach(el => {
    el.addEventListener('input', autoHeight);
    autoHeight(el);
  });
  document.querySelectorAll('#minidocs .card.text textarea').forEach(el => {
    el.addEventListener('keydown', typeText);
    el.addEventListener('paste', cancelGlobalPaste);
    autoHeight(el);
  });
  document.querySelectorAll('#minidocs .card :is(input,textarea)').forEach(el => {
    el.addEventListener('paste', cancelGlobalPaste);
  });
  document.querySelectorAll('#minidocs .card.text .form-text button.salvar').forEach(el => {
    el.addEventListener('click', saveText);
  });
  document.querySelectorAll('#minidocs .card.text .form-text button.cancelar').forEach(el => {
    el.addEventListener('click', cancelText);
  });
  document.querySelectorAll('#minidocs .card.text .actions button.edit').forEach(el => {
    el.addEventListener('click', editText);
  });
  document.querySelectorAll('#minidocs .card .actions button.delete').forEach(el => {
    el.addEventListener('click', diliti);
  });
  document.querySelectorAll('#minidocs .card .actions button.copy').forEach(el => {
    el.addEventListener('click', copy);
  });
  document.querySelectorAll('#minidocs .card.file .actions button.edit').forEach(el => {
    el.addEventListener('click', editFile);
  });
  document.querySelectorAll('#minidocs .card.file button.salvar').forEach(el => {
    el.addEventListener('click', saveFile);
  });
  document.querySelectorAll('#minidocs .card.file button.cancelar').forEach(el => {
    el.addEventListener('click', cancelEditFile);
  });
  document.querySelectorAll('#minidocs form').forEach(el => {
    el.addEventListener('submit', preventSubmit);
  });
};

const copy = ev => {
  ev.stopPropagation();
  const card = ev.currentTarget.closest('.card');
  const text = card.querySelector('textarea').value;
  navigator.clipboard.writeText(text).then(() => {
    const copied = card.querySelector('.copied');
    copied.classList.add('done');
    setTimeout(() => {
      copied.classList.remove('done');
    }, 375);
  });
};

const windowPaste = async ev => {
  const dataTransfer = ev.clipboardData;
  if (dataTransfer.types.includes('text/plain')) {
    const text = dataTransfer.getData('text/plain');
    const div = await addText(text);
    div.querySelector('button.salvar').click();
    return;
  }
  if (dataTransfer.types.includes('Files')) {
    await uploadFiles(dataTransfer.files);
  }
};

const paste = async () => {
  const text = await navigator.clipboard.readText();
  const div = await addText(text);
  div.querySelector('button.salvar').click();
};

const enviarArquivos = () => {
  document.querySelector('#arquivos').value = '';
  document.querySelector('#arquivos').click();
};

const enviaArquivos = async () => {
  try {
    await uploadFiles(document.querySelector('#arquivos').files);
  } catch (e) {
    abre(e, 10, 'OK');
  }
};

const progressoUpload = (percents, loaded, total) => {
  document.querySelector('#upload-progress label').textContent = `${Math.round(percents)}%`;
  document.querySelector('#upload-progress div').style.width = `${percents}%`;
};

const uploadFiles = async files => {
  try {
    if (uploading) return;
    let idSel = '';
    const sel = document.querySelector('#minidocs .card.sel');
    if (sel) idSel = sel.dataset.id;
    const up = document.querySelector('#upload-progress');
    uploading = true;
    document.querySelector('#upload-progress div').style.width = '0';
    up.classList.remove('hidden');
    const html = await upload.send(files, {hash, idSel});
    uploading = false;
    up.classList.add('hidden');
    if (sel) {
      sel.insertAdjacentHTML('beforebegin', html);
    } else {
      document.querySelector('#minidocs').insertAdjacentHTML('beforeend', html);
    }
    update();
  } catch (e) {
    abre(e, 10, 'OK');
    uploading = false;
    up.classList.add('hidden');
  }
};

const editFile = ev => {
  ev.stopPropagation();
  const card = ev.currentTarget.closest('.card.file');
  card.classList.add('edit');
  card.classList.remove('sel');
  update();
};

const saveFile = async ev => {
  try {
    ev.stopPropagation();
    ev.preventDefault();
    const card = ev.currentTarget.closest('.card.file');
    const body = new URLSearchParams({
      id: card.dataset.id,
      hash,
      name: card.querySelector('form input.name').value,
      description: card.querySelector('form textarea.description').value,
      tags: card.querySelector('form textarea.tags').value,
    });
    abre('Salvando...');
    const res = await fetch('save-file.php', {method: 'post', body});
    const html = await res.text();
    card.outerHTML = html;
    update();
    fecha();
  } catch (e) {
    console.log(e);
    abre(e, 10, 'OK');
  }
};

const cancelEditFile = ev => {
  ev.stopPropagation();
  const card = ev.currentTarget.closest('.card.file');
  card.classList.remove('edit');
  update();
};

const preventSubmit = ev =>{
  ev.preventDefault();
}

let timer;
const upload = new Upload('upload.php', progressoUpload);
let uploading = false;

document.querySelector('#name').addEventListener('input', inputName);
document.querySelector('#name').addEventListener('focus', focaName);
document.querySelector('#name').addEventListener('paste', cancelGlobalPaste);
document.querySelector('button.delete-red').addEventListener('click', exclui);
document.querySelector('#actions button.article').addEventListener('click', addEmptyText);
document.querySelector('#actions button.paste').addEventListener('click', paste);
document.querySelector('#actions button.attach').addEventListener('click', enviarArquivos);
document.querySelector('#arquivos').addEventListener('change', enviaArquivos);
window.addEventListener('paste', windowPaste);
update();