const nomeCompleto = document.querySelector("#nome");
const ident = document.querySelector("#identificacao");
const loco = document.querySelector("#veiculo");
const placaID = document.querySelector("#placa");
const cel = document.querySelector("#celular");
const sitEscola = document.querySelector("#sit_escola");
const sitService = document.querySelector("#sit_service");
const qrcode = document.querySelector("#qrcode");
const gerarButton = document.querySelector("#gerar");

//eventos de mecanismos de crianção do QRCode
document.addEventListener("keypress", (e) => {
  if (e.key === "Enter") {
    genQRCode();
  }
});

// Adiciona evento de input ao campo cel para adicionar o código de país (+55) automaticamente
cel.addEventListener("input", function() {
  const phoneNumber = cel.value.replace(/^\+55/, "");
  cel.value = "+55" + phoneNumber.replace(/\D+/g, "");
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

function sendwhatsapp() {
  const phonenumber = cel.value && /^\d+$/.test(cel.value) ? "+55" + cel.value : "";

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
        const whatsappUrl = `https://wa.me/${phonenumber}?text=${mensagem}${shortenedUrl}`;
        window.open(whatsappUrl, "_blank").focus();
      });
  };
}
