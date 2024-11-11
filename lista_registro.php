<?php
    session_start();
    include_once('config.php');
    // print_r($_SESSION);
   ;
    
    // $logado = $_SESSION['nome'];
    if(!empty($_GET['search']))
    {
        $data = $_GET['search'];
        $sql = "SELECT * FROM entrada_saida WHERE idcadastro LIKE '%$data%' or nome LIKE '%$data%' or identificacao LIKE '%$data%' or veiculo LIKE '%$data%' or placa LIKE '%$data%' or rua LIKE '%$data%' or numero LIKE '%$data%' or sit_escola LIKE '%$data%' or sit_service LIKE '%$data%' or entrada LIKE '%$data%' ORDER BY entrada DESC";
    }
    else
    {
        $sql = "SELECT * FROM entrada_saida ORDER BY entrada DESC";
    }
    $result = $conexao->query($sql);

    
?>

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/lista_registro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>ENTRADA/SAIDA</title>

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
            <a href="leitor.php" class="btn btn-danger me-5">Sair</a>
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
                    <th scope="col">idcadastro</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Identificação</th>
                    <th scope="col">Veículo</th>
                    <th scope="col">Placa</th>
                    <th scope="col">Rua</th>
                    <th scope="col">Número</th>
                    <th scope="col">Sit. Escolar</th>
                    <th scope="col">Sit. Serviços</th>
                    <th scope="col">Entrada</th>
                    <th scope="col">...</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while($user_data = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>".$user_data['idcadastro']."</td>";
                        echo "<td>".$user_data['nome']."</td>";
                        echo "<td>".$user_data['identificacao']."</td>";
                        echo "<td>".$user_data['veiculo']."</td>";
                        echo "<td>".$user_data['placa']."</td>";
                        echo "<td>".$user_data['rua']."</td>";
                        echo "<td>".$user_data['numero']."</td>";
                        echo "<td>".$user_data['sit_escola']."</td>";
                        echo "<td>".$user_data['sit_service']."</td>";
                        echo "<td>".date('d/m/Y H:i:s', strtotime($user_data['entrada']))." </td>";                      
                        echo "<td>
                        
                            <a class='btn btn-sm btn-danger' href='saida.php?id=$user_data[idcadastro]' title='Sair'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                                </svg>
                            </a>
                            </td>";
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
    window.location = 'lista_registro.php?search=' + search.value;
}
</script>

</html>