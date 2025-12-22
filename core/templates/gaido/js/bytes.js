class Bytes {

  constructor(bytes) {
    this.bytes = bytes;
  }

  formata() {
    if (this.bytes < 1024) {
      return `${this.bytes} B`;
    }
    if (this.bytes < 1024 * 1024) {
      return `${(this.bytes / 1024).toFixed(1)} KB`;
    }
    if (this.bytes < 1024 * 1024 * 1024) {
      return `${(this.bytes / 1024 / 1024).toFixed(1)} MB`;
    }
    if (this.bytes < 1024 * 1024 * 1024 * 1024) {
      return `${(this.bytes / 1024 / 1024 / 1024).toFixed(1)} GB`;
    }
    if (this.bytes < 1024 * 1024 * 1024 * 1024 * 1024) {
      return `${(this.bytes / 1024 / 1024 / 1024 / 1024).toFixed(1)} TB`;
    }
  }
}

export {Bytes};