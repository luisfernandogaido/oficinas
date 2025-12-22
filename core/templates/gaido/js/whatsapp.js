const whatsAppLinkOpen = (telefone, mensagem) => {
  let url = whatsAppLink(telefone, mensagem)
  console.log(url)
  window.open(url)
}

const whatsAppLinkMobile = (telefone, mensagem) => {
  const pars = new URLSearchParams({
    text: mensagem,
  })
  return `https://wa.me/${telefone}?` + pars.toString()
}

const whatsAppLink = (telefone, mensagem) => {
  let url
  if (matchMedia('(min-width: 1024px)').matches) {
    const pars = new URLSearchParams({
      phone: telefone,
      text: mensagem,
    })
    url = `https://web.whatsapp.com/send?` + pars.toString()
  } else {
    const pars = new URLSearchParams({
      text: mensagem,
    })
    url = `https://wa.me/${telefone}?` + pars.toString()
  }
  return url
}

const whatOpen = (telefone, mensagem) => {
  let tel = telefone
  if (!tel.startsWith('55')) tel = `55${telefone}`
  const textoCodificado = encodeURIComponent(mensagem)
  const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)
  if (isMobile) return `whatsapp://send?phone=${telefone}&text=${textoCodificado}`
  return `https://web.whatsapp.com/send?phone=${telefone}&text=${textoCodificado}`
}

const isMobile = () => /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)

const getUrl = (telefone, mensagem) => {
  const encodedText = encodeURIComponent(mensagem)
  if (isMobile()) return `whatsapp://send?phone=${telefone}&text=${encodedText}`
  return `https://web.whatsapp.com/send?phone=${telefone}&text=${encodedText}`
}

const send = (telefone, mensagem = '') => {
  const url = getUrl(telefone, mensagem)
  if (isMobile()) {
    window.location.href = url
  } else {
    window.open(url, '_blank')
  }
}

class Sender {
  win = null

  constructor () {
    if (!isMobile()) {
      this.win = window.open('', '_blank')
      const content = 'width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0'
      //language=html
      this.win.document.write(`
          <html>
          <head>
              <title>Processando...</title>
              <meta name="viewport" content="${content}">
          </head>
          <style>
              body {
                  background: #f3f4f6;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  height: 100vh;
                  font-family: sans-serif;
                  color: #555;
              }

              body > div {
                  text-align: center;
              }
          </style>
          <body>
          <div>
              <h3>Gerando link do WhatsApp...</h3>
              <p>Por favor, aguarde.</p>
          </div>
          </body>
          </html>
      `)
    }
  }

  send (telefone, mensagem) {
    const url = getUrl(telefone, mensagem)
    if (isMobile()) {
      window.location.href = url
    } else {
      this.win.location.href = url
    }
  }

  close () {
    if (this.win) this.win.close()
  }
}

export {
  send,
  Sender,
  whatsAppLink,
  whatsAppLinkMobile,
  whatsAppLinkOpen,
  whatOpen,
}