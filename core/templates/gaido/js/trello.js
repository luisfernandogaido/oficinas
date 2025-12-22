class Trello {

  constructor (key, token) {

    /**
     *
     * @private
     */
    this._key = key;

    /**
     *
     * @private
     */
    this._token = token;
    this.host = `https://api.trello.com/1`;
    this._qs = `key=${key}&token=${token}`;
  }

  /**
   *
   * @param {string} path
   * @private
   */
  async _get (path) {
    const res = await fetch(`${this.host}${path}?${this._qs}`);
    return await res.json();
  }

  /**
   *
   * @param {string} id
   */
  async card (id) {
    return await this._get(`/cards/${id}`);
  }

  /**
   *
   * @param {string} idCard
   */
  async cardBoard (idCard) {
    return await this._get(`/cards/${idCard}/board`);
  }

}

export { Trello };