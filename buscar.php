<?php
$conn = mysqli_connect("localhost", "root", "root", "loja1");

$nome = $_POST["nome"];

$query = "SELECT nome FROM usuarios WHERE nome LIKE '%$nome%'";

$result = mysqli_query($conn, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = $row;
}

echo json_encode($data);
?>