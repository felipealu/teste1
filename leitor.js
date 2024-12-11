// Variáveis da câmera, canvas e campos do formulário
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const outputData = document.getElementById("outputData");
const context = canvas.getContext("2d");

// Campos do formulário
const searchField = document.getElementById("nome");
const identificacaoField = document.getElementById("identificacao");
const veiculoField = document.getElementById("veiculo");
const placaField = document.getElementById("placa");
const celularField = document.getElementById("celular");
const sitEscolaField = document.getElementById("sit_escola");
const sitServiceField = document.getElementById("sit_service");

// Função para inicializar a câmera traseira
async function startRearCamera() {
  try {
    const devices = await navigator.mediaDevices.enumerateDevices();
    const videoDevices = devices.filter((device) => device.kind === "videoinput");

    if (videoDevices.length === 0) {
      throw new Error("Nenhuma câmera foi encontrada no dispositivo.");
    }

    let rearCamera = videoDevices.find((device) =>
      device.label.toLowerCase().includes("back") ||
      device.label.toLowerCase().includes("traseira")
    );

    if (!rearCamera) {
      console.warn("Câmera traseira não encontrada. Usando dispositivo alternativo.");
      rearCamera = videoDevices[videoDevices.length - 1];
    }

    console.log("Usando a câmera:", rearCamera.label);

    const constraints = {
      video: {
        deviceId: { exact: rearCamera.deviceId },
      },
    };

    const stream = await navigator.mediaDevices.getUserMedia(constraints);
    video.srcObject = stream;
    video.play();
  } catch (error) {
    console.error("Erro ao acessar a câmera:", error);
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

        // Preenche os campos do formulário com os dados do QR Code
        searchField.value = qrData.name || "";
        identificacaoField.value = qrData.identification || "";
        veiculoField.value = qrData.vehicle || "";
        placaField.value = qrData.placa || "";
        celularField.value = qrData.celular || "";

        if (qrData.sit_escola === "1") {
          sitEscolaField.checked = true;
        } else {
          sitEscolaField.checked = false;
        }

        if (qrData.sit_service === "1") {
          sitServiceField.checked = true;
        } else {
          sitServiceField.checked = false;
        }

        outputData.innerText = "Dados preenchidos automaticamente.";

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
