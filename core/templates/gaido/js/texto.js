const e = s => {
  if (s == null) return ''
  const simbols = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '\'': '&#39;',
    '"': '&quot;',
  }
  return String(s).replace(/[&<>'"]/g, c => simbols[c])
}

/**
 *
 * @param {number} valor
 * @returns {string}
 */
function moeda (valor) {
  if (valor == null || isNaN(valor)) {
    return ''
  }
  return valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

/**
 *
 * @param {number} valor
 * @param {number} casas
 * @returns {string}
 */
function numero (valor, casas) {
  if (valor == null) {
    return ''
  }
  return valor.toLocaleString(undefined, { minimumFractionDigits: casas, maximumFractionDigits: casas })
}

const real = str => {
  str = str.replaceAll('.', '').replaceAll(',', '.')
  return parseFloat(str)
}

function data (data) {
  if (data == null || data == '') {
    return ''
  }
  if (data.includes('T') && data.includes('.')) {
    return (new Date(data)).toLocaleDateString()
  }
  if (data.length == 19) {
    data = data + '-03:00'
  } else if (data.length != 25 && data.length != 20) {
    data = data + 'T00:00:00-03:00'
  }
  return (new Date(data)).toLocaleDateString()
}

function dataHora (data) {
  if (data == null || data == '') {
    return ''
  }
  if (data.includes('T') && data.includes('.')) {
    return (new Date(data)).toLocaleDateString() + ' ' + (new Date(data)).toLocaleTimeString()
  }
  if (data.length == 19) {
    data = data + '-03:00'
  } else if (data.length != 25 && data.length != 20) {
    data = data + 'T00:00:00-03:00'
  }
  data = data.replace(' ', 'T')
  return (new Date(data)).toLocaleString('pt-BR', { timezone: 'America/Sao_Paulo' }).replace(',', '')
}

function removeAcentos (s) {
  return s.normalize('NFD').replace(/[\u0300-\u036f]/g, '')
}

function termosIndice (s) {
  return s.split(' ').map(t => removeAcentos(t).trim().toLocaleLowerCase()).filter(t => t)
}

function indice (s) {
  return termosIndice(s).join(' ')
}

function contemTermos (s, termos) {
  if (!termos.length) {
    return true
  }
  for (let termo of termos) {
    if (!s.includes(termo)) return false
  }
  return true
}

function resultados (n) {
  switch (n) {
    case 0:
      return 'Nenhum resultado'
    case 1:
      return 'Um resultado'
    default:
      return `${n} resultados`
  }
}

/**
 *
 * @param {Date} d
 * @returns {string}
 */
function since (d) {
  const agora = new Date()
  const delta = agora.getTime() - d.getTime()
  if (delta < 60000) {
    return 'agora'
  }
  if (delta < 3600000) {
    const minutos = Math.floor(delta / 60000)
    if (minutos == 1) {
      return 'há um minuto'
    }
    return `há ${minutos} minutos`
  }
  if (delta < 86400000) {
    const horas = Math.floor(delta / 3600000)
    if (horas == 1) {
      return 'há uma hora'
    }
    return `há ${horas} horas`
  }
  if (delta < 604800000) {
    const dias = Math.floor(delta / 86400000)
    if (dias == 1) {
      return 'há um dia'
    }
    return `há ${dias} dias`
  }
  if (delta < 31536000000) {
    const semanas = Math.floor(delta / 604800000)
    if (semanas == 1) {
      return 'há uma semana'
    }
    return `há ${semanas} semanas`
  }
  const anos = Math.floor(delta / 31536000000)
  if (anos == 1) {
    return 'há um ano'
  }
  return `há ${anos} anos`
}

const timeDelta = miliseconds => {
  let signal = ''
  if (miliseconds < 0) {
    signal = '-'
  }
  miliseconds = Math.abs(miliseconds)
  const padTwo = number => number.toString().padStart(2, '0')
  const seconds = Math.floor(miliseconds / 1000)
  if (seconds < 60) {
    return `${padTwo(seconds)}s`
  }
  if (seconds < 3600) {
    const m = Math.floor(seconds / 60)
    const s = seconds % 60
    return `${padTwo(m)}m${padTwo(s)}s`
  }
  const h = Math.floor(seconds / 3600)
  const m = Math.floor((seconds - h * 3600) / 60)
  const s = seconds - h * 3600 - m * 60
  return `${signal}${padTwo(h)}h${padTwo(m)}m${padTwo(s)}s`
}

/**
 *
 * @param {Date} d
 * @param {Date} d0
 */
const timeDiff = (d, d0) => {
  return timeDelta(d - d0)
}

/**
 * Transforma nomes.
 * Primeira letra em maiúscula e as demais em minúsculas.
 * Remove espaços extras.
 * Palavras com uma ou duas letras em minúsculas.
 * @param {string} s
 * @returns {string}
 */
const maiusculas = s => {
  const words = s.split(' ').filter(w => w != '')
  const wordsModified = words.map(w => {
    if (w.length <= 2) return w.toLowerCase()
    return w.charAt(0).toUpperCase() + w.slice(1).toLowerCase()
  })
  return wordsModified.join(' ')
}

const slug = s => {
  return s.toLowerCase().
    replace(/[àÀáÁâÂãäÄÅåª]+/g, 'a').
    replace(/[èÈéÉêÊëË]+/g, 'e').
    replace(/[ìÌíÍîÎïÏ]+/g, 'i').
    replace(/[òÒóÓôÔõÕöÖº]+/g, 'o').
    replace(/[ùÙúÚûÛüÜ]+/g, 'u').
    replace(/[ýÝÿŸ]+/g, 'y').
    replace(/[ñÑ]+/g, 'n').
    replace(/[çÇ]+/g, 'c').
    replace(/[ß]+/g, 'ss').
    replace(/[Ææ]+/g, 'ae').
    replace(/[Øøœ]+/g, 'oe').
    replace(/[%]+/g, 'pct').
    replace(/\s+/g, '-').
    replace(/[^\w\-]+/g, '').
    replace(/--+/g, '-').
    replace(/^-+/, '').
    replace(/-+$/, '')
}

const normaliza = s => removeAcentos(s).trim().toLowerCase()

/**
 * @param {Date} d
 * @return {String}
 */
const dateToInputDate = d => {
  const dia = d.getDate().toString().padStart(2, '0')
  const mes = (d.getMonth() + 1).toString().padStart(2, '0')
  const ano = d.getFullYear()
  return `${ano}-${mes}-${dia}`
}

/**
 * @param {Date} d
 * @return {String}
 */
const dateToInputDatetime = d => {
  const dia = d.getDate().toString().padStart(2, '0')
  const mes = (d.getMonth() + 1).toString().padStart(2, '0')
  const ano = d.getFullYear()
  const data = `${ano}-${mes}-${dia}`
  let hora = `${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`
  return `${data}T${hora}`
}

const includesAll = (text, terms) => {
  text = normaliza(text)
  const arr = normaliza(terms).split(' ')
  for (let term of arr) {
    if (!text.includes(term)) return false
  }
  return true
}

export {
  e,
  moeda,
  numero,
  real,
  data,
  dataHora,
  removeAcentos,
  termosIndice,
  indice,
  contemTermos,
  resultados,
  since,
  maiusculas,
  slug,
  normaliza,
  dateToInputDate,
  dateToInputDatetime,
  timeDelta,
  timeDiff,
  includesAll,
}