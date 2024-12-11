// Variáveis da câmera, canvas e campos do formulário
const video = document.getElementById("video");
const canvas = document.getElementById("canvas");
const outputData = document.getElementById("outputData");
const context = canvas.getContext("2d");

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
