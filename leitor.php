<?php


    require_once 'config.php';

    //     print_r($_POST['nome']);
    //     print_r('<br>');
    //     print_r($_POST['identificacao']);
    //     print_r('<br>');
    //     print_r($_POST['veiculo']);
    //     print_r('<br>');
    //

    // if(!$conexao){
    //     die("Erro de conexão:" . $conexao->connect_error);
    // }



    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nome = mb_strtoupper($_POST["nome"]);
        $identificacao = mb_strtoupper($_POST["identificacao"]);
        $veiculo = mb_strtoupper($_POST["veiculo"]);
        $placa = mb_strtoupper($_POST["placa"]);
        $rua = mb_strtoupper($_POST["rua"]);
        $numero = mb_strtoupper($_POST["numero"]);

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


        $result = mysqli_query($conexao, "INSERT INTO entrada_saida (idcadastro, nome, identificacao, veiculo, placa, rua, numero, sit_escola, sit_service, entrada,saida) VALUES (NULL, '$nome', '$>

        if (!$result) {
            return("Erro ao inserir dados: " . mysqli_error($conexao));
        }
          // Redirecionar para uma página diferente pq toda vez que eu apertava f5 duplicava a inserção
        header("Location: leitor.php");

                $result = $conexao->query($sql);

    }



?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SiSCAM - Leitor</title>
    <link rel="icon" type="image/svg+xml" href="/imagens/qrcode.svg">
    <link rel="stylesheet" href="/css/leitor.css" />
</head>

<body>
    <div>
        <button id="irparagerar">Ir para o Gerador</button>
    </div>
    <h1>Leitor de QR Code e Cadastro</h1>
    <video id="video" width="200" height="200" autoplay></video>
    <canvas id="canvas" hidden></canvas>
    <p id="outputData">Aponte para um QR code</p>

    <!-- Modal -->
    <!-- Modal -->
    <div id="modal" style="display: none;">
        <div class="modal-content">
            <span id="alerta"></span>
        </div>
    </div>

    <!-- Formulário de Cadastro -->

    <div id="cadastro">
        <h3>Cadastro</h3>
        <form action=" " method="POST">
            <label for="nome" class="label-animado">Nome:</label>
            <input type="text" id="nome" name="nome" style="text-transform: uppercase;" required /><br /><br />
            <div id="sugestoes"></div>

            <label for="identificacao" class="label-animado">Identificação:</label>
            <input type="text" id="identificacao" name="identificacao" style="text-transform: uppercase;" required /><br /><br />

            <label for="veiculo" class="label-animado">Veículo:</label>
            <input type="text" id="veiculo" name="veiculo" style="text-transform: uppercase;"  /><br /><br />

            <label for="placa" class="label-animado">Placa:</label>
            <input type="text" id="placa" name="placa" style="text-transform: uppercase;" /><br /><br />

            <label for="rua">Escolha uma Rua:</label>

            <select id="rua" name="rua" required>
                <option value="">Selecione a Rua...</option>
                <option value="RUA CEL AQUILES PEDENEIRAS">RUA CEL AQUILES PEDENEIRAS</option>
                <option value="RUA CEL AMAURY">RUA CEL AMAURY</option>
                <option value="RUA CEL ANDRADE NEVES">RUA CEL ANDRADE NEVES</option>
                <option value="RUA CEL CASTRO JUNIOR">RUA CEL CASTRO JUNIOR</option>
                <option value="RUA CEL ESPIRIDAO ROSAS">RUA CEL ESPIRIDAO ROSAS</option>
                <option value="RUA CEL FIUZA DE CASTRO">RUA CEL FIUZA DE CASTRO</option>
                <option value="RUA CEL HASTIMPHILO DE MOURA">RUA CEL HASTIMPHILO DE MOURA</option>
                <option value="RUA CEL LINDOLPHO SERRA">RUA CEL LINDOLPHO SERRA</option>
                <option value="RUA CEL MARTINS PEREIRA">RUA CEL MARTINS PEREIRA</option>
                <option value="RUA CEL PEDRO IVO">RUA CEL PEDRO IVO</option>
                <option value="RUA CEL SILIO PORTELA">RUA CEL SILIO PORTELA</option>
                <option value="RUA CEL UCHOA">RUA CEL UCHOA</option>
                <option value="RUA DA INDUSTRIA">RUA DA INDUSTRIA</option>
                <option value="RUA DUQUE DE CAXIAS">RUA DUQUE DE CAXIAS</option>
                <option value="RUA GEN ALTAIR">RUA GEN ALTAIR</option>
                <option value="RUA GEN OCTAVIO">RUA GEN OCTAVIO</option>
                <option value="RUA GEN PARGAS RODRIGUES">RUA GEN PARGAS RODRIGUES</option>
                <option value="RUA GEN PONDE">RUA GEN PONDE</option>
                <option value="RUA GEN WEDMAN">RUA GEN WEDMAN</option>
                <option value="RUA MAJ DOUTOR AZEVEDO">RUA MAJ DOUTOR AZEVEDO</option>
                <option value="RUA MESTRE CAMARGO">RUA MESTRE CAMARGO</option>
                <option value="RUA MESTRE JORGE">RUA MESTRE JORGE</option>
                <option value="RUA MESTRE NUNO">RUA MESTRE NUNO</option>
                <option value="RUA MESTRE SADOCK DE SA">RUA MESTRE SADOCK DE SA</option>
                <option value="RUA PARANA">RUA PARANA</option>
                <option value="RUA SAMUEL DA SILVA CALDAS">RUA SAMUEL DA SILVA CALDAS</option>
                <option value="IGREJA">IGREJA</option>
                <option value="GREMIO">GREMIO</option>
                <option value="PNRS">PNRS</option>
                <option value="ESCOLA">ESCOLA</option>


            </select><br /><br />

            <label for="numero" class="label-animado">Número:</label>
            <input type="text" id="numero" name="numero" style="text-transform: uppercase;" required /><br /><br />
            <div id="check">
                <label for="sit_escola">Cadastro Escolar:</label> <br />
                <input type="checkbox" id="sit_escola" name="sit_escola" value="true" /><br /><br />

                <label for="sit_service">Cadastro Serviços:</label>
                <input type="checkbox" id="sit_service" name="sit_service" value="1" /><br /><br />
            </div>

            <br><br><a id="saida" href="lista_registro.php"> Saida</a> <br /><br />
            <a id="relatorio" href="relatorio.php"> Relatório</a> <br /><br />
            <a id="servicebutton" href="service.php"> Serviços</a> <br /><br />


            <input type="hidden" name="token" value="<?php echo uniqid(); ?>" />
            <button type="submit" id="register" nome="submit">Registrar Entrada</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
    <script src="leitor.js"></script>
    <script src="alerta.js"></script>


    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
        const checkboxSituacao = document.getElementById("sit_escola");
        const inputRua = document.getElementById("rua");

        // Adiciona um event listener para detectar mudanças no checkbox
        checkboxSituacao.addEventListener("change", function() {
            if (checkboxSituacao.checked) {
                // Se o checkbox estiver marcado, preenche o campo "Rua" com "ESCOLA"
                inputRua.value = "ESCOLA";
            } else {
                // Se não estiver marcado, limpa o campo "Rua"
                inputRua.value = "";
            }
        });
    });
    </script>

    <script>
    // Função para redirecionar para a página do gerador
    document
        .getElementById("irparagerar")
        .addEventListener("click", function() {
            // Substitua pelo nome do seu arquivo
            window.location.href = "gen2.php";
        });
    </script>

    <script>
    // cadastra individualmente cada informação ao apertar "cadastrar"
    document.getElementById("submit").addEventListener("click", function() {
        const nome = document.getElementById("nome").value;
        const identificacao = document.getElementById("identificacao").value;
        const veiculo = document.getElementById("veiculo").value;
        const placa = document.getElementById("placa").value;
        const options = document.getElementById("rua").value;
        const numero = document.getElementById("numero").value;
        const sit_escola = document.getElementById("sit_escola").value;
        const sit_service = document.getElementById("sit_service").value;

        // Verifica se os campos estão preenchidos
        if (
            nome &&
            identificacao &&
            veiculo &&
            placa &&
            rua &&
            numero &&
            sit_escola &&
            sit_service
        ) {
            // Faz uma requisição AJAX para o PHP
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "leitor.php");
            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );
            xhr.onreadystatechange = function() {
                if (this.readyState === 4 && this.status === 200) {
                    // Exibe a resposta do PHP
                    console.log(this.responseText);
                }
            };
            xhr.send(
                "$nome=" +
                encodeURIComponent(nome) +
                "&identificacao=" +
                encodeURIComponent(identificacao) +
                "&veiculo=" +
                encodeURIComponent(veiculo) +
                "&placa=" +
                encodeURIComponent(placa) +
                "&rua=" +
                encodeURIComponent(options) +
                "&numero=" +
                encodeURIComponent(numero) +
                "&sit_escola=" +
                encodeURIComponent(sit_escola) +
                "&sit_service=" +
                encodeURIComponent(sit_service)
            );
        }
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Sugestões e autocomplete dos outros campos
        const inputNome = document.getElementById("nome");
        const inputIdentificacao = document.getElementById("identificacao");
        const inputVeiculo = document.getElementById("veiculo");
        const inputPlaca = document.getElementById("placa");
        const checkboxSituacao = document.getElementById("sit_escola");
        const checkboxService = document.getElementById("sit_service");
        const sugestoes = document.getElementById("sugestoes");

        inputNome.addEventListener("keyup", function() {
            const nome = inputNome.value.trim();
            if (nome !== "") {
                fetch("buscar.php", {
                        method: "POST",
                        body: new URLSearchParams({
                            nome: nome,
                        }),
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        const sugestoesHtml = data
                            .map((sugestao) => {
                                return `<li>${sugestao.nome}</li>`;
                            })
                            .join("");
                        sugestoes.innerHTML = `<ul>${sugestoesHtml}</ul>`;
                        sugestoes.style.display = "block";
                    })
                    .catch((error) => console.error(error));
            } else {
                sugestoes.style.display = "none";
            }
        });

        sugestoes.addEventListener("click", function(event) {
            if (event.target.tagName === "LI") {
                const nomeSelecionado = event.target.textContent;
                inputNome.value = nomeSelecionado;
                sugestoes.style.display = "none";
                buscarInformacoes(nomeSelecionado);
                const checkboxService = document.getElementById("sit_service");
                checkboxService.checked = true; // ou false, dependendo da informação correspondente
            }
        });

        function buscarInformacoes(nome) {
            fetch("buscar-informacoes.php", {
                    method: "POST",
                    body: new URLSearchParams({
                        nome: nome,
                    }),
                })
                .then((response) => response.json())
                .then((data) => {
                    console.log(data); // Verifique se os dados estão sendo lidos corretamente
                    inputIdentificacao.value = data.identificacao;
                    inputVeiculo.value = data.veiculo;
                    inputPlaca.value = data.placa;
                    checkboxSituacao.checked = data.sit_escola === "1" ? true :
                        false; // code para consguir o valor do checkbox
                    checkboxService.checked = data.sit_service === "1" ? true :
                        false;
                })
                .catch((error) => console.error(error));
        }
    });
    </script>


    <script>
    // Oculta as sugestões
    window.addEventListener("load", function() {
        const sugestoes = document.getElementById("sugestoes");
        sugestoes.style.display = "none";
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


