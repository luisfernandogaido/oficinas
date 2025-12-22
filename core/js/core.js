'use strict';

function query_string (objeto) {
  let qs = [];
  for (let chave in objeto) {
    qs.push(chave + '=' + encodeURIComponent(objeto[chave]));
  }
  return qs.join('&');
}

function ajax (url, campos, callback, metodo, progresso) {

  function query_string_elementos (elementos) {
    let dic = {};
    for (let i = 0; i < elementos.length; i++) {
      if (elementos[i].type != 'checkbox' && elementos[i].type != 'radio' || elementos[i].checked) {
        dic[elementos[i].name || elementos[i].id] = elementos[i].value;
      }
    }
    return query_string(dic);
  }

  let map_met = {
    'GET': 'GET',
    'get': 'GET',
    'G': 'GET',
    'g': 'GET',
    'POST': 'POST',
    'post': 'POST',
    'P': 'POST',
    'p': 'POST',
  };
  if (typeof map_met[metodo] != 'undefined') {
    metodo = map_met[metodo];
  } else {
    metodo = 'GET';
  }
  if (campos instanceof HTMLElement) {
    if (campos.tagName == 'FORM') {
      campos = query_string_elementos(campos.elements);
    } else {
      campos = query_string_elementos(campos.querySelectorAll('input, select, textarea'));
    }
  } else if (campos instanceof FormData) {
    metodo = 'POST';
  } else if (typeof campos == 'string') {
    //não faça nada, query_string supostamente está pronta. Exemplo: a=1&b=2.
  } else if (campos instanceof Object) {
    campos = query_string(campos);
  }
  let xhr = new XMLHttpRequest();
  if (metodo == 'POST') {
    xhr.open(metodo, url, true);
    if (!(campos instanceof FormData)) {
      xhr.setRequestHeader('content-type', 'application/x-www-form-urlencoded; charset=utf-8');
    }
  } else {
    if (campos) {
      url = url + '?' + campos;
    }
    xhr.open(metodo, url, true);
    xhr.setRequestHeader('content-type', 'text/html; charset=utf-8');
  }
  xhr.setRequestHeader('Cache-Control', 'no-store, no-cache, must-revalidate');
  xhr.onload = function () {
    if (callback) {
      callback(this.responseText, this.status);
    }
  };
  if (progresso) {
    if (xhr.upload) {
      xhr.upload.onprogress = function (e) {
        progresso(e);
      };
    }
  }
  xhr.send(campos);
  return xhr;
}

function xhr (url, dados, callback, metodo, headers) {
  let XHR = new XMLHttpRequest();
  if (callback !== undefined) {
    XHR.onload = function () {
      callback(XHR.responseText);
    };
  }
  if (metodo === undefined) {
    metodo = 'GET';
  }
  if (metodo == 'GET' && dados !== null) {
    url = url + '?' + encodeValues(dados);
  }
  let lowerHeaders = {};
  for (let k in headers) {
    if (headers.hasOwnProperty(k)) {
      lowerHeaders[k.toLowerCase()] = headers[k];
    }
  }
  headers = lowerHeaders;
  if (headers['content-type'] === undefined && metodo != 'GET' && metodo != 'DELETE') {
    headers['content-type'] = 'application/json; charset=utf8';
  } else if (headers['content-type'].indexOf('application/x-www-form-urlencoded') != -1) {
    metodo = 'POST';
    dados = encodeValues(dados);
  }
  if (headers['content-type'] !== undefined && headers['content-type'].indexOf('application/json') != -1) {
    if (typeof dados == 'object') {
      dados = JSON.stringify(dados);
    }
  }
  XHR.open(metodo, url, true);
  for (let k in headers) {
    if (headers.hasOwnProperty(k)) {
      let partesK = k.split('-');
      for (let i = 0; i < partesK.length; i++) {
        partesK[i] = partesK[i].charAt(0).toUpperCase() + partesK[i].slice(1);
      }
      XHR.setRequestHeader(partesK.join('-'), headers[k]);
    }
  }
  XHR.send(dados);
}

function delrow (linha) {
  linha.parentNode.parentNode.deleteRow(linha.rowIndex);
}

function xy (elemento) {
  let y = 0, x = 0;
  if (elemento.offsetParent) {
    do {
      y += elemento.offsetTop;
      x += elemento.offsetLeft;
    } while (elemento = elemento.offsetParent);
  }
  return { 'x': x, 'y': y };
}

function client_rect (el) {
  return el.getBoundingClientRect();
}

let historico = {
  disabled: false,
  onload: null,
  hash: function () {
    let hash = location.hash.replace('#', '');
    let arr_hash = hash.split('&');
    let obj_hash = {};
    let campo;
    for (let i = 0; i < arr_hash.length; i++) {
      if (arr_hash[i]) {
        campo = arr_hash[i].split('=');
        obj_hash[campo[0]] = decodeURIComponent(campo[1] || '');
      }
    }
    return obj_hash;
  },
  load: function (funcao) {
    window.addEventListener('load', function () {
      historico.onload = true;
      historico.disabled = true;
      setTimeout(function () {
        funcao(historico.hash());
      }, 0);
      historico.disabled = false;
    });
    window.addEventListener('popstate', function () {
      historico.onload = false;
      historico.disabled = true;
      setTimeout(function () {
        funcao(historico.hash());
      }, 0);
      historico.disabled = false;
    });
  },
  push: function (dados) {
    let hash;
    if (!this.disabled) {
      let arr_hash = [];
      for (let k in dados) {
        arr_hash.push(k + '=' + encodeURIComponent(dados[k]));
      }
      if (arr_hash.length) {
        hash = '#' + arr_hash.join('&');
      } else {
        hash = '';
      }
      if (hash != location.hash) {
        history.pushState(null, null, location.pathname + location.search + hash);
      }
    }
  },
};

function is_touch () {
  return 'ontouchstart' in window        // works on most browsers
    || navigator.maxTouchPoints;       // works on IE10/11 and Surface
}

function is_visible (el) {
  return (el.offsetWidth > 0 && el.offsetHeight > 0);
}

function is_inviewport (el) {
  let rect = el.getBoundingClientRect();
  let A = rect.width * rect.height;
  return A && rect.top >= 0 && rect.bottom <= window.innerHeight && rect.left >= 0 && rect.right <= window.innerWidth;
}

function is_scrollable (el) {
  return el.scrollHeight > el.offsetHeight || el.scrollWidth > el.offsetWidth;
}

function is_data (strdata) {

  function e_data (dia, mes, ano) {
    dia = parseFloat(dia);
    mes = parseFloat(mes) - 1;
    ano = parseFloat(ano);
    let data = new Date(ano, mes, dia, 12, 0, 0);
    let d = data.getDate();
    let m = data.getMonth();
    let a = data.getFullYear();
    return (d == dia && m == mes && a == ano);
  }

  let partes = strdata.split('/');
  if (partes.length == 3) {
    return e_data(partes[0], partes[1], partes[2]);
  }
  return false;
}

function cria_data (strdata) {
  if (!is_data(strdata)) {
    throw 'Formato de data inválido para criação.';
  }
  let d = new Date();
  let partes = strdata.split('/');
  d.setDate(parseFloat(partes[0]));
  d.setMonth(parseFloat(partes[1]) - 1);
  d.setFullYear(parseFloat(partes[2]));
  d.setHours(0);
  d.setMinutes(0);
  d.setSeconds(0);
  d.setMilliseconds(0);
  return d;
}

function date_diff (d1, d2) {
  let diferenca = d1.getTime() - d2.getTime();
  return Math.floor(diferenca / 86400000);
}

function gera_token () {
  return Math.random().toString(16).substr(2);
}

function enter (e, funcao) {
  if (e.keyCode == 13) {
    e.preventDefault();
    funcao();
  }
}

function isEmpty (obj) {
  return Object.keys(obj).length === 0 && obj.constructor === Object;
}

function mergeOptions (obj1, obj2) {
  let obj3 = {};
  for (let attrname in obj1) {
    obj3[attrname] = obj1[attrname];
  }
  for (let attrname in obj2) {
    obj3[attrname] = obj2[attrname];
  }
  return obj3;
}

let QUERY_SELECTOR_CAMPOS = 'input[type=text], input[type=hidden], input[type=email], input[type=password], ' +
  'input[type=checkbox], input[type=radio], select, textarea, input[type=tel], input[type=range]';

let api = {};
api.cep = function (cep, retorno) {
  ajax(window.SITE + 'core/api/cep.php', { 'cep': cep }, function (json) {
    try {
      retorno(JSON.parse(json));
    } catch (e) {
      retorno(null);
    }
  });
};

let DataHora = {
  hoje: function () {
    let d = new Date();
    let dia = d.getDate();
    let mes = d.getMonth() + 1;
    let ano = d.getFullYear();
    if (dia < 10) {
      dia = '0' + dia;
    }
    if (mes < 10) {
      mes = '0' + mes;
    }
    return dia + '/' + mes + '/' + ano;
  },
  diaSemana: function (data) {
    let dias = [
      'dom',
      'seg',
      'ter',
      'qua',
      'qui',
      'sex',
      'sáb',
    ];
    let partes = data.split('/');
    let d = new Date();
    d.setDate(parseFloat(partes[0]));
    d.setMonth(parseFloat(partes[1]) - 1);
    d.setFullYear(parseFloat(partes[2]));
    d.setHours(0);
    d.setMinutes(0);
    d.setSeconds(0);
    d.setMilliseconds(0);
    return dias[d.getDay()];
  },
  cria: function (strData) {
    if (!strData) {
      strData = DataHora.hoje();
    }
    if (!is_data(strData)) {
      throw 'Formato de data inválido para criação.';
    }
    let d = new Date();
    let partes = strData.split('/');
    d.setDate(parseFloat(partes[0]));
    d.setMonth(parseFloat(partes[1]) - 1);
    d.setFullYear(parseFloat(partes[2]));
    d.setHours(0);
    d.setMinutes(0);
    d.setSeconds(0);
    d.setMilliseconds(0);
    return d;
  },
};
let Numeros = {
  formata: function (numero, casas, separador) {
    let retorno, negativo = false;
    if (numero === '' || numero === null) {
      return null;
    }
    numero = parseFloat(numero);
    if (numero < 0) {
      negativo = true;
      numero = Math.abs(numero);
    }
    if (isNaN(numero)) {
      throw 'Número precisa ser real para ser formatado.';
    }
    if (casas === undefined) {
      casas = 2;
    }
    let partes = numero.toFixed(casas).split('.');
    let parteInteira = partes[0];
    let parteDecimal = partes[1];
    if (separador) {
      let trincas = [];
      while (parteInteira.length > 3) {
        trincas.push(parteInteira.substr(parteInteira.length - 3));
        parteInteira = parteInteira.substring(0, parteInteira.length - 3);
      }
      if (parteInteira.length) {
        trincas.push(parteInteira);
      }
      trincas.reverse();
      retorno = trincas.join('.');
    } else {
      retorno = parteInteira;
    }
    if (casas) {
      retorno += ',' + parteDecimal;
    }
    if (negativo) {
      retorno = '-' + retorno;
    }
    return retorno;

  },
  moeda: function (numero) {
    return Numeros.formata(numero, 2, true);
  },
  real: function (strnumero) {
    strnumero = strnumero.toString();
    if (strnumero.indexOf('.') != -1 && strnumero.indexOf(',') != -1) {
      strnumero = strnumero.replace(/\./g, '').replace(',', '.');
    } else if (strnumero.indexOf(',') != -1) {
      strnumero = strnumero.replace(',', '.');
    }
    if (isNaN(strnumero)) {
      throw 'Número com formato incorreto para ser convertido em real.';
    }
    return parseFloat(strnumero);
  },
  eInteiro: function (numero) {
    return !isNaN(numero) &&
      parseInt(Number(numero)) == numero && !isNaN(parseInt(numero, 10));
  },
  abreviacao: function (numero) {
    if (numero >= 1000000000000) {
      return (numero / 1000000000000).toFixed(1) + ' Tri';
    }
    if (numero >= 1000000000) {
      return (numero / 1000000000).toFixed(1) + ' Bi';
    }
    if (numero >= 1000000) {
      return (numero / 1000000).toFixed(1) + ' Mi';
    }
    if (numero >= 1000) {
      return (numero / 1000).toFixed(1) + ' K';
    }
    return numero.toFixed(2);
  },
};

function getget () {
  let search = location.search.replace('?', '');
  let pares = search.split('&'), par;
  let get = {};
  for (let i = 0; i < pares.length; i++) {
    par = pares[i].split('=');
    get[par[0]] = decodeURIComponent(par[1]);
  }
  return get;
}

function encodeValues (objeto) {
  let qs = [];
  for (let chave in objeto) {
    qs.push(chave + '=' + encodeURIComponent(objeto[chave]));
  }
  return qs.join('&');
}

function parseQuery (query) {
  if (!query) {
    query = location.search.substr(1);
    if (!query) {
      return {};
    }
  }
  let pares = query.split('&'),
    par;
  let values = {};
  for (let i = 0; i < pares.length; i++) {
    par = pares[i].split('=');
    values[par[0]] = decodeURIComponent(par[1]);
  }
  return values;
}

async function getDB () {
  return new Promise((resolve, reject) => {
    let req = indexedDB.open(window.APP, parseInt(CSSJSV));
    req.onupgradeneeded = event => {
      try {
        let db = event.target.result;
        if (db.objectStoreNames.contains('pesquisas')) {
          db.deleteObjectStore('pesquisas');
        }
        db.createObjectStore('pesquisas');
      } catch (e) {
        reject(e);
      }
    };
    req.onsuccess = event => {
      resolve(event.target.result);
    };
    req.onerror = err => {
      reject(err);
    };
  });
}