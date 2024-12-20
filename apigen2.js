const nomeCompleto = document.querySelector("#nome");
const ident = document.querySelector("#identificacao");
const loco = document.querySelector("#veiculo");
const placaID = document.querySelector("#placa");
const cel = document.querySelector("#celular");
const sitEscola = document.querySelector("#sit_escola");
const sitService = document.querySelector("#sit_service");
const qrcode = document.querySelector("#qrcode");
const gerarButton = document.querySelector("#gerar");

// Adicione um evento de input para manipular o valor do campo de celular
cel.addEventListener("input", () => {
  const valor = cel.value;
  if (!valor.startsWith("+55")) {
    cel.value = `+55${valor.replace(/[^0-9]/g, "")}`;
  } else {
    cel.value = `+55${valor.replace(/^\+55/, "").replace(/[^0-9]/g, "")}`;
  }
});

//eventos de mecanismos de crianção do QRCode
document.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    genQRCode();
  }
});

gerarButton.addEventListener("click", () => {
  console.log("Botão de gerar clicado");
  genQRCode();
  sendwhatsapp();
});

sitEscola.addEventListener("change", () => {
  const sitEscolaValue = sitEscola.checked ? "1" : "0";
  sitEscola.value = sitEscolaValue;
});

sitService.addEventListener("change", () => {
  const sitServiceValue = sitService.checked ? "1" : "0";
  sitService.value = sitServiceValue;
});

// API para crianção do QRCode
function genQRCode() {
  console.log("Função genQRCode chamada");
  const sitEscolaValue = sitEscola.checked ? "1" : "0";
  const sitServiceValue = sitService.checked ? "1" : "0";
  const data = {
    name: nomeCompleto.value,
    identification: ident.value,
    vehicle: loco.value,
    placa: placaID.value,
    celular: cel.value,
    sit_escola: sitEscolaValue,
    sit_service: sitServiceValue,
  };
  console.log("Objeto data criado:", data);
  const jsonString = JSON.stringify(data);
  console.log("String JSON criada:", jsonString);
  qrcode.src = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(
    jsonString
  )}`;
  console.log("QRCode gerado:", qrcode.src);
}

// ...

function sendwhatsapp() {
  const phonenumber = cel.value;

  // Pegar a imagem
  const imagem = document.getElementById("qrcode");

  // Adicionar evento load à imagem
  imagem.onload = function () {
    // Pegar o conteúdo do objeto data
    const sitEscolaValue = sitEscola.checked ? "1" : "0";
    const sitServiceValue = sitService.checked ? "1" : "0";
    const data = {
      name: nomeCompleto.value,
      identification: ident.value,
      vehicle: loco.value,
      placa: placaID.value,
      celular: cel.value,
      sit_escola: sitEscolaValue,
      sit_service: sitServiceValue,
    };

    // Converter o objeto data em uma string
    const dataString = JSON.stringify(data);

    // Adicionar o conteúdo do objeto data ao QR Code
    const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?data=${encodeURIComponent(
      dataString
    )}&size=150x150&background=${encodeURIComponent(
      "https://tecnodefesa.com.br/wp-content/uploads/2020/02/AGR.jpg"
    )}`;

    // Encurtar o link usando o TinyURL
    const tinyUrl = `https://tinyurl.com/api-create.php?url=${qrCodeUrl}`;

    // Enviar o link encurtado para o WhatsApp
    const shortenedUrl = fetch(tinyUrl)
      .then((response) => response.text())
      .then((shortenedUrl) => {
        const mensagem = "Segue o QR Code para escaneamento: ";
        const textoInstrucoes = `
**Importante: Utilização Segura do Link do QR Code**

* Apresente sua identificação (CPF, RG ou outro documento de identificação) junto com o QR Code.
* Acesse o link do QR Code utilizando um dispositivo seguro e conectado à internet.
* Escaneie o QR Code utilizando um leitor de QR Code seguro.
* Siga as instruções para concluir o processo de cadastro.

**Lembre-se**

* Apresente a identificação junto com o QR Code para garantir a segurança do processo de cadastro.
* Acesse o link do QR Code utilizando um dispositivo seguro e conectado à internet.
`;

        const mensagemCompleta = mensagem + textoInstrucoes + shortenedUrl;
        const whatsappUrl = `https://wa.me/${phonenumber}?text=${mensagemCompleta}`;

        // Enviar o link do QR Code com o texto de instruções para o WhatsApp
        window.open(whatsappUrl, "_blank").focus();
      });
  };
}
