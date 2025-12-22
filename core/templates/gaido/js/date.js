/**
 * Convert a date object to a formatted date string.
 *
 * @param {Date} date - The date to be converted.
 * @returns {string} - The formatted date string in the format: 'YYYY-MM-DD'.
 */
const dateToDateString = date => {
  const dia = date.getDate().toString().padStart(2, '0')
  const mes = (date.getMonth() + 1).toString().padStart(2, '0')
  const ano = date.getFullYear()
  return `${ano}-${mes}-${dia}`
}

/**
 * Converts a string representation of a date to a Date object.
 *
 * @param {string} dateString - The string representation of the date.
 * @returns {Date} The Date object representing the given date string.
 */
const dateStringToDate = dateString => {
  if (dateString.includes('T') && dateString.includes('.')) {
    return new Date(dateString)
  }
  if (dateString.length == 19) {
    dateString = dateString + '-03:00'
  } else if (dateString.length != 25 && dateString.length != 20) {
    dateString = dateString + 'T00:00:00-03:00'
  }
  return new Date(dateString)
}

/**
 *
 * @param {Date|string} data Se string, passe YYYY-MM-DD HH:MM:SS sempre, mesmo que só for usar data.
 * @returns string
 */
const previsao = data => {
  if (typeof data === 'string') {
    data = new Date(data)
  }
  const diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado']
  const dia = String(data.getDate()).padStart(2, '0')
  const mes = String(data.getMonth() + 1).padStart(2, '0')
  const hora = String(data.getHours()).padStart(2, '0')
  const minuto = String(data.getMinutes()).padStart(2, '0')
  return `${diasSemana[data.getDay()]}, ${dia}/${mes} às ${hora}:${minuto}`
}

export {
  dateToDateString,
  dateStringToDate,
  previsao,
}