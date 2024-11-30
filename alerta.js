setInterval(() => {
  fetch("verificar-inatividade.php")
    .then((response) => response.json())
    .then((data) => {
      if (data && data.length > 0) {
        const modal = document.getElementById("modal");
        const alerta = document.getElementById("alerta");
        alerta.textContent =
          "Há cadastro tipo Serviços com mais de 20 minutos de permanência na Vila!!!";
        modal.style.display = "block";
      } else {
        const modal = document.getElementById("modal");
        modal.style.display = "none";
      }
    })
    .catch((error) => console.error("Erro ao verificar inatividade:", error));
}, 60000); // Verifica a cada 1 minuto (60000 milissegundos)
