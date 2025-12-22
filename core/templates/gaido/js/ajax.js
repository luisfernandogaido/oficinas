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

export { ajax, query_string };