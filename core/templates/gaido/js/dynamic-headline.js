import { sleep } from './gaido.js'

class DynamicHeadLine {

  running = false

  constructor (ctn) {
    this.ctn = ctn
    this.ctn.classList.add('dynamic-headline')
  }

  async setPhrase (phrase) {
    await this.destroyPhrase()
    this.ctn.innerHTML = ''
    phrase.split(' ').forEach(w => {
      const span = document.createElement('span')
      span.textContent = w + ' '
      this.ctn.append(span)
    })
    this.ctn.querySelectorAll('span').forEach((span) => {
      span.style.top = `${this.ctn.offsetHeight}px`
    })
    for (let span of this.ctn.querySelectorAll('span')) {
      span.style.top = `0`
      await sleep(250)
    }
  }

  async destroyPhrase () {
    for (let span of this.ctn.querySelectorAll('span')) {
      span.style.top = `-${span.offsetTop}px`
      await sleep(250)
    }
  }

  async run (phrases, pauseTime) {
    this.running = true
    while (true) {
      for (let phrase of phrases) {
        if (!this.running) return
        await this.setPhrase(phrase)
        await sleep(pauseTime)
      }
    }
  }

  stop () {
    this.running = false
  }

}

export {
  DynamicHeadLine,
}