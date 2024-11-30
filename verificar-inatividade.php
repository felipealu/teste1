<?php
require_once 'config.php';

header('Content-Type: application/json');

// Consulta para encontrar registros com `sit_service = 1` e mais de 5 segundos desde a criação
$query = "SELECT * FROM entrada_saida WHERE sit_service = 1 AND TIMESTAMPDIFF(SECOND, created_at, NOW()) > 1200";
$result = mysqli_query($conexao, $query);

$registros = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $registros[] = $row;
    }
}

// Retorna os registros como JSON
echo json_encode($registros);
?>