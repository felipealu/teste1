// Adicione um evento de clique ao botão para gerar PDF
document.getElementById("gerar-pdf").addEventListener("click", function () {
  // Abra o modal para selecionar data
  document.getElementById("modal-data").style.display = "block";
});

// Adicione um evento de clique ao botão para gerar PDF no modal
document
  .getElementById("gerar-pdf-modal")
  .addEventListener("click", function () {
    // Obtenha a data selecionada
    var data = document.getElementById("data-relatorio").value;

    // Faça a requisição para gerar o PDF
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "pdf.php?data=" + data, true);
    xhr.responseType = "blob";
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Abra o PDF em uma nova janela
        var pdf = new Blob([xhr.response], { type: "application/pdf" });
        var url = URL.createObjectURL(pdf);
        window.open(url, "_blank");
      }
    };
    xhr.send();
  });

// Adicione um evento de clique ao botão para cancelar no modal
document
  .getElementById("cancelar-modal")
  .addEventListener("click", function () {
    // Feche o modal
    document.getElementById("modal-data").style.display = "none";
  });
