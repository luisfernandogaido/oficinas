const version = 10;

/**
 * @type {IDBDatabase}
 */
let db;

const conecta = async () => {
  return new Promise((resolve, reject) => {
    if (db) {
      resolve(db);
      return;
    }
    let request = window.indexedDB.open(window.APP, version);
    request.onerror = ev => {
      reject(ev.target);
    };
    request.onsuccess = ev => {
      db = ev.target.result;
      resolve(db);
    };
    request.onupgradeneeded = ev => {
      const db = ev.target.result;
      if (ev.target.result.objectStoreNames.contains('cache')) {
        db.deleteObjectStore('cache');
      }
      db.createObjectStore('cache');
    };
    request.onerror = ev => {
      reject(ev);
    };
  });
};

const set = async (k, v) => {
  await conecta();
  return new Promise((resolve, reject) => {
    const objectStore = db.transaction('cache', 'readwrite').objectStore('cache');
    const request = objectStore.put(v, k);
    request.onsuccess = () => {
      resolve(request.result);
    };
    request.onerror = ev => {
      reject(ev.target);
    };
  });
};

const get = async k => {
  await conecta();
  return new Promise((resolve, reject) => {
    const objectStore = db.transaction('cache', 'readonly').objectStore('cache');
    const request = objectStore.get(k);
    request.onsuccess = () => {
      resolve(request.result);
    };
    request.onerror = ev => {
      reject(ev.target);
    };
  });
};

const del = async k => {
  await conecta();
  return new Promise((resolve, reject) => {
    const objectStore = db.transaction('cache', 'readwrite').objectStore('cache');
    const request = objectStore.delete(k);
    request.onsuccess = () => {
      resolve(request.result);
    };
    request.onerror = ev => {
      reject(ev.target);
    };
  });
};

const count = async k => {
  await conecta();
  return new Promise((resolve, reject) => {
    const objectStore = db.transaction('cache', 'readonly').objectStore('cache');
    const request = objectStore.count();
    request.onsuccess = () => {
      resolve(request.result);
    };
    request.onerror = ev => {
      reject(ev.target);
    };
  });
};

const all = async () => {
  await conecta();
  return new Promise((resolve, reject) => {
    const objectStore = db.transaction('cache', 'readonly').objectStore('cache');
    const request = objectStore.getAllKeys();
    request.onsuccess = () => {
      resolve(request.result);
    };
    request.onerror = ev => {
      reject(ev.target);
    };
  });
};

export { set, get, del, count, all };