import {saveState, loadState, updateChecks} from '../../core/templates/gaido/js/gaido.js';
import * as alerta from '../../core/templates/gaido/js/alerta.js';
import * as forms from '../../core/templates/gaido/js/forms.js';
import * as texto from '../../core/templates/gaido/js/texto.js';
import {dateToInputDatetime} from '../../core/templates/gaido/js/texto.js';
import {dias, horas} from '../../core/templates/gaido/js/time.js';

const update = () => {
  document.querySelector('#registro .banner').textContent = `${acao} ${type}`;
  document.querySelectorAll('#registro form').forEach(f => {
    f.classList.add('hidden');
  });
  form.classList.remove('hidden');
  switch (type) {
    case 'agua':
      updateAgua();
      break;
  }
};

const updateAgua = () => {
  const ml = document.querySelector('input[name="ml"]:checked').value;
  if (ml == 'outro') {
    form.elements['ml-custom'].required = true;
  } else {
    form.elements['ml-custom'].value = '';
    form.elements['ml-custom'].required = false;
  }
  document.querySelector('#c-ml').classList.toggle('oculto', ml != 'outro');
  console.log(form.elements['date'].value);
};

const novo = () => {
  form.elements['codigo'].value = '0';
  form.elements['date'].value = dateToInputDatetime(new Date());
  switch (type) {
    case 'agua':
      break;
  }
  acao = 'novo';
  location.hash = '#registro';
};

const ss = () => {
  saveState('registros', {
    'type': document.querySelector('input[name="type"]:checked').value,
  });
};

const ls = () => {
  const state = loadState('registros', 1000 * 60 * 5);
  if (state) {
    document.querySelector(`input[name="type"][value="${state.type}"]`).click();
  } else {
    document.querySelector(`input[name="type"][value="agua"]`).click();
  }
};

const load = async args => {
  try {
    lastArgs = args;
    const pars = new URLSearchParams({
      type: document.querySelector('input[name="type"]:checked').value,
      por_dia: args?.porDia ?? false,
      hl_mode: args?.hlMode ?? 'fluxo',
    });
    const res = await fetch('load.php?' + pars.toString());
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.erro, 10, 'OK');
      return;
    }
    document.querySelector('#resultado').innerHTML = '';
    if (type != pars.get('type')) return;
    switch (type) {
      case 'agua':
        renderAguas(r, pars);
        break;
      case 'hl':
        renderHls(r, pars);
        break;
      case 'barba':
        renderBarbas(r);
        break;
      case 'cabelo':
        renderCabelos(r);
        break;
      case 'outros':
        renderOutros(r);
        break;
    }
    document.querySelectorAll('#resultado button.delete').forEach(b => {
      b.addEventListener('click', remove);
    });
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const reload = () => {
  load(lastArgs).then();
};

const renderAguas = (r, pars) => {
  const porDia = pars.get('por_dia') == 'true';
  //language=html
  document.querySelector('#resultado').innerHTML = `
      <div id="controles-type" class="checks">
          <label>
              <input type="checkbox" name="por-dia" value="1">
              Por dia
          </label>
      </div>
      <div class="resultado">
          <table>
              <tbody></tbody>
          </table>
      </div>
  `;
  if (pars.get('por_dia') == 'true') {
    document.querySelector('#resultado input[name="por-dia"]').checked = true;
    updateChecks();
  }
  document.querySelector('#resultado input[name="por-dia"]').addEventListener('click', ev => {
    load({porDia: ev.currentTarget.checked}).then();
  });
  const t = document.querySelector('#resultado tbody');
  if (porDia) {
    r.forEach(l => {
      const tr = document.createElement('tr');
      tr.dataset.codigo = l.codigo;
      //language=html
      tr.innerHTML = `
          <td class="dia"></td>
          <td class="ml num"></td>
      `;
      tr.querySelector('.dia').textContent = texto.data(l.dia);
      tr.querySelector('.ml').textContent = l.ml;
      t.append(tr);
    });
  } else {
    r.forEach(l => {
      const tr = document.createElement('tr');
      tr.dataset.codigo = l.codigo;
      //language=html
      tr.innerHTML = `
          <td class="date"></td>
          <td class="ml num"></td>
          <td class="acoes">
              <button class="delete"></button>
          </td>
      `;
      tr.querySelector('.date').textContent = texto.dataHora(l.date);
      tr.querySelector('.ml').textContent = l.float;
      t.append(tr);
    });
  }
};

const renderHls = (r, pars) => {
  const hlMode = pars.get('hl_mode');
  //language=html
  document.querySelector('#resultado').innerHTML = `
      <div id="controles-type" class="checks">
          <label>
              <input type="radio" name="hl-mode" value="fluxo">
              Fluxo
          </label>
          <label>
              <input type="radio" name="hl-mode" value="por-dia">
              Por dia
          </label>
          <label>
              <input type="radio" name="hl-mode" value="por-mes">
              Por mês
          </label>
      </div>
      <div class="resultado">
          <div class="horas"></div>
          <table>
              <tbody></tbody>
          </table>
      </div>
  `;
  const t = document.querySelector('#resultado tbody');
  document.querySelector(`#resultado input[name="hl-mode"][value="${hlMode}"]`).checked = true;
  document.querySelectorAll('#resultado input[name="hl-mode"]').forEach(b => {
    b.addEventListener('click', ev => load({hlMode: ev.currentTarget.value}).then());
  });
  updateChecks();
  switch (hlMode) {
    case 'fluxo':
      r.forEach(l => {
        const tr = document.createElement('tr');
        tr.dataset.codigo = l.codigo;
        //language=html
        tr.innerHTML = `
            <td class="date"></td>
            <td class="args"></td>
            <td class="horas num"></td>
            <td class="acoes">
                <button class="delete"></button>
            </td>
        `;
        tr.querySelector('.date').textContent = texto.dataHora(l.date);
        tr.querySelector('.args').textContent = l.args;
        tr.querySelector('.horas').textContent = l.horas;
        t.append(tr);
      });
      if (r.length == 0) return;
      const hours = horas(new Date(), new Date(r[0].date));
      const days = dias(new Date(), new Date(r[0].date))
      document.querySelector('#resultado .horas').textContent = `${hours}, ${days}d`;
      break;
    case 'por-dia':
      r.forEach(l => {
        const tr = document.createElement('tr');
        //language=html
        tr.innerHTML = `
            <td class="dia"></td>
            <td class="f num"></td>
        `;
        tr.querySelector('.dia').textContent = texto.data(l.dia);
        tr.querySelector('.f').textContent = l.f;
        t.append(tr);
      });
      break;
    case 'por-mes':
      r.forEach(l => {
        const tr = document.createElement('tr');
        //language=html
        tr.innerHTML = `
            <td class="mes"></td>
            <td class="f num"></td>
        `;
        tr.querySelector('.mes').textContent = `${l.mes}/${l.ano}`;
        tr.querySelector('.f').textContent = l.f;
        t.append(tr);
      });
      break;
  }
};

const renderBarbas = r => {
  //language=html
  document.querySelector('#resultado').innerHTML = `
      <div class="horas"></div>
      <table>
          <tbody></tbody>
      </table>
  `;
  const t = document.querySelector('#resultado tbody');
  r.forEach(l => {
    const tr = document.createElement('tr');
    tr.dataset.codigo = l.codigo;
    //language=html
    tr.innerHTML = `
        <td class="date"></td>
        <td class="horas num"></td>
        <td class="obs"></td>
        <td class="acoes">
            <button class="delete"></button>
        </td>
    `;
    tr.querySelector('.date').textContent = texto.dataHora(l.date);
    tr.querySelector('.horas').textContent = l.horas;
    tr.querySelector('.obs').textContent = l.text;
    t.append(tr);
  });
  if (r.length == 0) return;
  const days = dias(new Date(), new Date(r[0].date));
  const hours = horas(new Date(), new Date(r[0].date));
  document.querySelector('#resultado .horas').textContent = `${hours}, ${days}d`;
};

const renderCabelos = r => {
  //language=html
  document.querySelector('#resultado').innerHTML = `
      <div class="horas"></div>
      <table>
          <tbody></tbody>
      </table>
  `;
  const t = document.querySelector('#resultado tbody');
  r.forEach(l => {
    const tr = document.createElement('tr');
    tr.dataset.codigo = l.codigo;
    //language=html
    tr.innerHTML = `
        <td class="date"></td>
        <td class="horas num"></td>
        <td class="obs"></td>
        <td class="acoes">
            <button class="delete"></button>
        </td>
    `;
    tr.querySelector('.date').textContent = texto.dataHora(l.date);
    tr.querySelector('.horas').textContent = l.horas;
    tr.querySelector('.obs').textContent = l.text;
    t.append(tr);
  });
  if (r.length == 0) return;
  const hours = horas(new Date(), new Date(r[0].date));
  const days = dias(new Date(), new Date(r[0].date))
  document.querySelector('#resultado .horas').textContent = `${hours}, ${days}d`;
};

const renderOutros = r => {
  //language=html
  document.querySelector('#resultado').innerHTML = `
      <div class="horas"></div>
      <table>
          <tbody></tbody>
      </table>
  `;
  const t = document.querySelector('#resultado tbody');
  r.forEach(l => {
    const tr = document.createElement('tr');
    tr.dataset.codigo = l.codigo;
    //language=html
    tr.innerHTML = `
        <td class="date"></td>
        <td class="horas num"></td>
        <td class="obs"></td>
        <td class="acoes">
            <button class="delete"></button>
        </td>
    `;
    tr.querySelector('.date').textContent = texto.dataHora(l.date);
    tr.querySelector('.horas').textContent = l.horas;
    tr.querySelector('.obs').textContent = l.text;
    t.append(tr);
  });
  if (r.length == 0) return;
  document.querySelector('#resultado .horas').textContent = horas(new Date(), new Date(r[0].date));
};

const selectType = () => {
  type = document.querySelector(`input[name="type"]:checked`).value;
  form = document.querySelector(`#f-${type}`);
  ss();
  load().then();
};

const save = async () => {
  try {
    if (forms.reportaInvalidos(form)) return;
    await forms.post(form, 'save.php');
    history.back();
    load().then();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

const remove = async ev => {
  try {
    const tr = ev.currentTarget.parentNode.parentNode;
    const body = new URLSearchParams({
      codigo: tr.dataset.codigo,
    });
    const res = await fetch('remove.php', {method: 'post', body});
    const r = await res.json();
    if (r.erro) {
      alerta.abre(r.erro);
      return;
    }
    reload();
  } catch (e) {
    alerta.abre(e, 10, 'OK');
  }
};

let type;
let acao;
let form;
let lastArgs;
forms.registraAll();
window.addEventListener('popstate', update);
document.querySelectorAll('input[name="type"]').forEach(b => {
  b.addEventListener('click', selectType);
});
document.querySelector('button.novo').addEventListener('click', novo);
document.querySelectorAll('input[type="radio"]').forEach(b => {
  b.addEventListener('click', update);
});
document.querySelectorAll('#registro button.primario').forEach(b => {
  b.addEventListener('click', save);
});

if (location.hash) {
  history.back();
}
ls();
