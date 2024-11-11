<?php
    session_start();
    include_once('config.php');
    // print_r($_SESSION);
   ;
    
    // $logado = $_SESSION['nome'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM relatorio WHERE nome LIKE '%$data%' or identificacao LIKE '%$data%' or veiculo LIKE '%$data%' or placa LIKE '%$data%' or rua LIKE '%$data%' or numero LIKE '%$data%' or sit_escola LIKE '%$data%' or  sit_service LIKE '%$data%' or entrada LIKE '%$data%' or saida LIKE '%$data%' ORDER BY saida DESC";
    }
    else
    {
        $sql = "SELECT * FROM relatorio ORDER BY saida DESC";
    }
    $result = $conexao->query($sql);
    function apagarInformacoes($conexao) {
        $dataExpiracao = date('Y-m-d', strtotime('-90 days'));
      
        $query = "DELETE FROM relatorio WHERE saida < '$dataExpiracao'";
        
        // Verificar se a conexão com o banco de dados foi estabelecida corretamente
        if ($conexao) {
          // Executar a consulta
          $result = $conexao->query($query);
          
          // Verificar se a consulta foi bem-sucedida
          if ($result) {
            if ($conexao->affected_rows > 0) {
              echo "Informações apagadas com sucesso!";
            }
          } else {
            echo "Erro ao executar a consulta: " . $conexao->error;
          }
        } else {
          echo "Erro: Conexão com o banco de dados não foi estabelecida.";
        }
      }
      
      // Chamar a função e passar a variável $conexao como parâmetro
      apagarInformacoes($conexao);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="relatorio.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>RELATORIO</title>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">ENTRADA/SAIDA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="d-flex">
            <a href="leitor.php" id="btn-sair" class="btn btn-danger me-5">Sair</a>
            <!-- Botão para gerar PDF -->
            <button id="gerar-pdf">Gerar PDF</button>

            <!-- Modal para selecionar data -->
            <div id="modal-data" style="display: none;">
                <h2>Selecione a data do relatório</h2>
                <input type="date" id="data-relatorio" />
                <button id="gerar-pdf-modal">Gerar PDF</button>
                <button id="cancelar-modal">Cancelar</button>
            </div>
        </div>
    </nav>
    <br>

    <br>
    <div class="box-search">
        <input type="search" class="form-control w-25" placeholder="Pesquisar" id="pesquisar">
        <button onclick="searchData()" class="btn btn-primary" id="btn-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search"
                viewBox="0 0 16 16">
                <path
                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
        </button>
    </div>
    <div class="m-5">
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Identificação</th>
                    <th scope="col">Veículo</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Rua</th>
                    <th scope="col">Número</th>
                    <th scope="col">Cadastro Escolar</th>
                    <th scope="col">Cadastro Serviço</th>
                    <th scope="col">Entrada</th>
                    <th scope="col">Saida</th>
                    <th scope="col">...</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($user_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";                        
                        echo "<td>".$user_data['nome']."</td>";
                        echo "<td>".$user_data['identificacao']."</td>";
                        echo "<td>".$user_data['veiculo']."</td>";
                        echo "<td>".$user_data['placa']."</td>";
                        echo "<td>".$user_data['rua']."</td>";
                        echo "<td>".$user_data['numero']."</td>";
                        echo "<td>".$user_data['sit_escola']."</td>";
                        echo "<td>".$user_data['sit_service']."</td>";
                        echo "<td>".date('d/m/Y H:i:s', strtotime($user_data['entrada']))." </td>";                      
                        echo "<td>".date('d/m/Y H:i:s', strtotime($user_data['saida']))." </td>";                      
                        
                        
                            
                        echo "</tr>";
                    }
                    ?>
            </tbody>
        </table>
    </div>
</body>


<script>
var search = document.getElementById('pesquisar');

search.addEventListener("keydown", function(event) {
    if (event.key === "Enter") {
        searchData();
    }
});

function searchData() {
    window.location = 'relatorio.php?search=' + search.value;
}
</script>


<script src="relatorio.js"></script>

</html>
