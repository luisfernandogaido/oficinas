class Upload {

  /**
   * @property {string} url
   */
  url;

  /**
   * @property {Function|null}
   */
  onProgress;

  /**
   *
   * @param {string} url
   * @param {Function} [onprogress]
   */
  constructor(url, onprogress) {
    this.url = url;
    this.onProgress = onprogress;
  }

  /**
   *
   * @param {HTMLFormElement|FileList} formOrFiles
   * @param {Object.<string, string|number>} [data]
   * @returns {Promise<string>}
   */
  async send(formOrFiles, data = {}) {
    return new Promise((resolve, reject) => {
      const fd = this.#createFormData(formOrFiles, data);
      const xhr = new XMLHttpRequest();
      xhr.open('POST', this.url);
      if (this.onProgress) {
        xhr.upload.addEventListener('progress', ev => {
          if (!ev.lengthComputable) return;
          this.onProgress(100 * ev.loaded / ev.total, ev.loaded, ev.total);
        });
      }
      xhr.addEventListener('load', () => {
        if (xhr.status == 200) {
          resolve(xhr.responseText);
        } else {
          reject(xhr.status);
        }
      });
      xhr.addEventListener('error', () => {
        reject(xhr.status);
      });
      xhr.send(fd);
    });
  }

  /**
   *
   * @param {HTMLFormElement|FileList} formOrFiles
   * @param {Object.<string, string|number>} [data]
   * @returns {FormData}
   */
  #createFormData(formOrFiles, data) {
    const name = formOrFiles.constructor.name;
    if (!['FileList', 'HTMLFormElement'].includes(name)) throw 'formOrFiles não é HTMLFormElement nem FileList';
    let fd;
    if (name == 'FileList') {
      fd = new FormData();
      for (let file of formOrFiles) {
        fd.append('files[]', file);
      }
    } else {
      fd = new FormData(formOrFiles);
    }
    for (let [k, v] of Object.entries(data)) {
      fd.append(k, v);
    }
    return fd;
  }
}

export {
  Upload,
};