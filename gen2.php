<?php
    
    //     print_r($_POST['nome']);
    //     print_r('<br>');
    //     print_r($_POST['identificacao']);
    //     print_r('<br>');
    //     print_r($_POST['veiculo']);
    //     print_r('<br>');
    //
    require_once 'config.php';
    // if(!$conexao){
    //     die("Erro de conexão:" . $conexao->connect_error);
    // }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = $_POST["nome"];
        $identificacao = $_POST["identificacao"];
        $veiculo = $_POST["veiculo"];
        $placa = $_POST["placa"];
    
        if (!empty($_POST["celular"]) && is_numeric($_POST["celular"])) {
            $celular = $_POST["celular"];
        } else {
            $celular = 0; // ou algum outro valor padrão
        }
    
        if (isset($_POST['sit_escola']) && $_POST['sit_escola'] !== '') {
            $sit_escola = 1;
        } else {
            $sit_escola = 0;
        }
        
        if (isset($_POST['sit_service']) && $_POST['sit_service'] !== '') {
            $sit_service = 1;
        } else {
            $sit_service = 0;
        }
        
        $result = mysqli_query($conexao, "INSERT INTO usuarios (idcadastro, nome, identificacao, veiculo, placa, celular, sit_escola, sit_service) VALUES (NULL, '$nome', '$identificacao', '$veiculo', '$placa', '$celular', '$sit_escola', '$sit_service')");
    
        // if (!$result) {
        //     die("Erro ao inserir dados: " . mysqli_error($conexao));
        // }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gerador de QR Code com Cadastro</title>
    <link rel="stylesheet" href="/css/gerador.css" />
</head>

<body>
    <div>
        <button id="irparaleitor">Ir para o Leitor</button>
    </div>
    <h1>Leitor de QR Code e Cadastro</h1>

    <!-- Formulário de Cadastro -->
    <div id="cadastro">
        <h3>Cadastro</h3>
        <form action="gen2.php" method="POST">
            <label for="nome" class="label-animado">Nome:</label>
            <input type="text" id="nome" name="nome" style="text-transform: uppercase;" /><br /><br />

            <label for="identificacao" class="label-animado">Identificação:</label>
            <input type="text" id="identificacao" name="identificacao" style="text-transform: uppercase;" /><br /><br />

            <label for="veiculo" class="label-animado">Veículo:</label>
            <input type="text" id="veiculo" name="veiculo" style="text-transform: uppercase;" /><br /><br />

            <label for="placa" class="label-animado">Placa:</label>
            <input type="text" id="placa" name="placa" style="text-transform: uppercase;" /><br /><br />

            <label for="celular" class="label-animado">Celular:</label> <br />
            <input type="text" id="celular" name="celular" style="text-transform: uppercase;" /><br /><br />

            <div id='check'>
                <label for="sit_escola">Cadastro Escola:</label> <br />
                <input type="checkbox" id="sit_escola" name="sit_escola" value="1" /><br /><br />

                <label for="sit_escola">Cadastro Escola:</label> <br />
                <input type="checkbox" id="sit_service" name="sit_service" value="1" /><br /><br />

            </div>

            <input type="hidden" name="token" value="<?php echo uniqid(); ?>">
            <button type="submit" id="register" nome="submit">Cadastrar</button>
        </form>
        <div class="qrcodeContent">
            <img id="qrcode" src="" alt="QR Code" />
        </div>
        <div id="buttongerar">
            <button id="gerar">Gerar</button>
            <br>
        </div>
    </div>


    <script src=" https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/dist/qrcode.min.js"></script>
    <script src="/js/apigen2.js"></script>
    <script src="/js/arraygen2.js"></script>

    <script>
    // Função para redirecionar para a página do gerador
    document.getElementById("irparaleitor")
        .addEventListener("click", function() {
            // Substitua pelo nome do seu arquivo
            window.location.href = "leitor.php";
        });
    </script>


    <script>
    document.getElementById("submit").addEventListener("click", function() {
        const nome = document.getElementById("nome").value;
        const identificacao = document.getElementById("identificacao").value;
        const veiculo = document.getElementById("veiculo").value;
        const placa = document.getElementById("placa").value;
        const celular = document.getElementById("celular").value;
        const sit_escola = document.getElementById("sit_escola").value;
        const sit_service = document.getElementById("sit_service").value;


        // Verifica se os campos estão preenchidos
        if (nome && identificacao && veiculo && placa && celular && sit_escola && sit_service) {
            // Faz uma requisição AJAX para o PHP
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "gen2.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    // Exibe a resposta do PHP
                    console.log(this.responseText);
                }
            };
            xhr.send("$nome=" + encodeURIComponent(nome) + "&identificacao=" + encodeURIComponent(
                    identificacao) + "&veiculo=" + encodeURIComponent(veiculo) + "&placa=" +
                encodeURIComponent(placa) + "&celular=" +
                encodeURIComponent(celular) + "&sit_escola=" +
                encodeURIComponent(sit_escola) + "&sit_service=" + encodeURIComponent(sit_service));
        }
    });
    </script>

    <script>
    // Adicione esse código JavaScript ao seu arquivo JavaScript
    document.querySelectorAll('.label-animado').forEach(function(label) {
        label.nextElementSibling.addEventListener('focus', function() {
            label.classList.add('focado');
        });
        label.nextElementSibling.addEventListener('blur', function() {
            label.classList.remove('focado');
        });
    });
    </script>

</body>

</html>