// Variáveis da câmera, canvas e campos do formulário
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const outputData = document.getElementById("outputData");
const context = canvas.getContext("2d");
// Campos do formulário de cadastro
const searchField = document.getElementById("nome");
const identificacaoField = document.getElementById("identificacao");
const veiculoField = document.getElementById("veiculo");
const placaField = document.getElementById("placa");
const celularField = document.getElementById("celular");
const sitEscolaField = document.getElementById("sit_escola");
const sitServiceField = document.getElementById("sit_service");
// Arrays para armazenar as informações lidas
const nomes = [];
const identificacoes = [];
const veiculos = [];
const placas = [];
const sitescola = [];
const siteservice = [];


// Função para inicializar a câmera traseira
async function startRearCamera() {
  try {
    // Obtém a lista de dispositivos de mídia
    const devices = await navigator.mediaDevices.enumerateDevices();

    // Filtra dispositivos de vídeo
    const videoDevices = devices.filter((device) => device.kind === "videoinput");

    if (videoDevices.length === 0) {
      throw new Error("Nenhuma câmera foi encontrada no dispositivo.");
    }

    // Procura pela câmera traseira
    let rearCamera = videoDevices.find((device) =>
      device.label.toLowerCase().includes("back") || // Rótulo da câmera
      device.label.toLowerCase().includes("traseira")
    );

    // Caso não encontre, usa o último dispositivo listado como fallback
    if (!rearCamera) {
      console.warn("Câmera traseira não encontrada. Usando dispositivo alternativo.");
      rearCamera = videoDevices[videoDevices.length - 1];
    }

    console.log("Usando a câmera:", rearCamera.label);

    // Define as configurações do dispositivo
    const constraints = {
      video: {
        deviceId: { exact: rearCamera.deviceId },
      },
    };

    // Acessa a câmera com as configurações
    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    video.srcObject = stream;
    video.play();
  } catch (error) {
    console.error("Erro ao acessar a câmera:", error);

    // Fallback para qualquer câmera disponível
    alert("Não foi possível usar a câmera traseira. Usando outra câmera disponível.");
    try {
      const fallbackStream = await navigator.mediaDevices.getUserMedia({ video: true });
      video.srcObject = fallbackStream;
      video.play();
    } catch (fallbackError) {
      console.error("Erro ao acessar qualquer câmera:", fallbackError);
    }
  }
}

// Função para processar o vídeo e detectar o QR Code
function tick() {
  if (video.readyState === video.HAVE_ENOUGH_DATA) {
    canvas.hidden = false;
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height);

    if (code) {
      try {
        const qrData = JSON.parse(code.data);

        outputData.innerText = `QR Code lido: ${code.data}`;
        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
        drawLine(code.location.bottomRightCorner, code.location.bottomLeftCorner, "#FF3B58");
        drawLine(code.location.bottomLeftCorner, code.location.topLeftCorner, "#FF3B58");
      } catch (error) {
        outputData.innerText = "QR Code inválido.";
      }
    } else {
      outputData.innerText = "Aponte para um QR Code.";
    }
    // Verifica se as informações já foram armazenadas
    if (
      !nomes.includes(qrData.name) &&
      !identificacoes.includes(qrData.identification) &&
      !veiculos.includes(qrData.vehicle) &&
      !placas.includes(qrData.placa) &&
      !sitescola.includes(qrData.sit_escola) &&
      !siteservice.includes(qrData.sit_service)
    ) {
    // Armazena os novos dados nos arrays
      nomes.push(qrData.name);
      identificacoes.push(qrData.identification);
      veiculos.push(qrData.vehicle);
      placas.push(qrData.placa);
      sitescola.push(qrData.sit_escola);
      siteservice.push(qrData.sit_service);

    // Preenche os campos do formulário com os dados do QR code
      searchField.value = qrData.name || "";
      identificacaoField.value = qrData.identification || "";
      veiculoField.value = qrData.vehicle || "";
      placaField.value = qrData.placa || "";
      sitEscolaField.value = qrData.sit_escola || "";
      sitServiceField.value = qrData.sit_service || "";

    // Atualiza a interface para mostrar os dados lidos
      outputData.innerText = "Dados preenchidos automaticamente.";
    } else {
      outputData.innerText = "Código já lido.";
    }

  }
  requestAnimationFrame(tick);
}

// Função para desenhar uma linha
function drawLine(begin, end, color) {
  context.beginPath();
  context.moveTo(begin.x, begin.y);
  context.lineTo(end.x, end.y);
  context.lineWidth = 4;
  context.strokeStyle = color;
  context.stroke();
}

// Inicia a câmera traseira
startRearCamera();
requestAnimationFrame(tick);
