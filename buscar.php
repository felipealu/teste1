<?php
$conn = mysqli_connect("localhost", "root", "root", "loja1");

if (isset($_POST["nome"]) && !empty($_POST["nome"])) {
    $nome = $_POST["nome"];

    // Verifica se foram digitadas pelo menos três letras
    if (strlen($nome) >= 3) {
        $query = "SELECT nome FROM usuarios WHERE nome LIKE '%$nome%'";

        $result = mysqli_query($conn, $query);

        $data = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = $row;
        }

        echo json_encode($data);
    } else {
        echo json_encode(array()); // Retorna um array vazio se não foram digitadas três letras
    }
} else {
    echo "Erro: O campo 'nome' não foi enviado.";
}
?>
