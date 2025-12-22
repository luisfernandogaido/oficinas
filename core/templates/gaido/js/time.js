/**
 *
 * @param {string} a
 * @param {string} b
 * @returns {string}
 */
const addTime = (a, b) => {
  if (!valTime(a)) throw `time ${a} inválido`;
  if (!valTime(b)) throw `time ${b} inválido`;
  const signA = a.includes('-') ? -1 : 1;
  const signB = b.includes('-') ? -1 : 1;
  a = a.replace('+', '').replace('-', '');
  b = b.replace('+', '').replace('-', '');
  const sumSec = signA * timeToSec(a) + signB * timeToSec(b);
  return secToTime(sumSec);
};

/**
 *
 * @param {string} time
 */
const valTime = time => {
  return /^[+-]?\d{2,}:\d{2}:\d{2}$/.test(time);
};

/**
 *
 * @param {string} time
 * @returns {number}
 */
const timeToSec = time => {
  const [horas, minutos, segundos] = time.split(':').map(p => parseFloat(p));
  return 3600 * horas + 60 * minutos + segundos;
};

const secToTime = sec => {
  let resto;
  const sign = sec < 0 ? '-' : '';
  sec = Math.abs(sec);
  const horas = Math.floor(sec / 3600);
  resto = sec % 3600;
  const minutos = Math.floor(resto / 60);
  const segundos = resto % 60;
  const h = horas.toString().padStart(2, '0');
  const m = minutos.toString().padStart(2, '0');
  const s = segundos.toString().padStart(2, '0');
  return `${sign}${h}:${m}:${s}`;
};

/**
 * Retorna o tempo entre duas datas no formato "0h00m".
 *
 * @param {Date} t
 * @param {Date} t0
 * @return {string}
 */
const horas = (t, t0) => {
  const seconds = Math.round((t.getTime() - t0.getTime()) / 1000);
  const hours = Math.floor(seconds / 3600);
  const minutes = Math.floor(seconds % 3600 / 60).toString().padStart(2, '0');
  return `${hours}h${minutes}m`;
};

/**
 * Retorna o tempo entre duas datas em dias.
 *
 * @param {Date} t
 * @param {Date} t0
 * @return {string}
 */

const dias = (t, t0) => {
  const seconds = Math.round((t.getTime() - t0.getTime()) / 1000);
  const days = seconds / 86400;
  return days.toFixed(2);
};

export {addTime, secToTime, horas, dias};