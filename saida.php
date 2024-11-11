<?php
require_once 'config.php';

$id = $_GET['id'];



// Defina a saída
mysqli_query($conexao, "UPDATE entrada_saida SET saida = NOW() WHERE idcadastro = '$id'");

// Envie os dados para a tabela relatorio
$result = mysqli_query($conexao, "SELECT * FROM entrada_saida WHERE idcadastro = '$id'");
$data = mysqli_fetch_assoc($result);

// Insira os dados na tabela relatorio
mysqli_query($conexao, "INSERT INTO relatorio (nome, identificacao, veiculo, placa, rua, numero, sit_escola, sit_service, entrada, saida) VALUES ('$data[nome]', ' $data[identificacao]', '$data[veiculo]', '$data[placa]', '$data[rua]', '$data[numero]', '$data[sit_escola]', '$data[sit_service]', '$data[entrada]', '$data[saida]')");

// Exclua a tabela entrada_saida
mysqli_query($conexao, "DELETE FROM entrada_saida WHERE idcadastro = '$id'");

// Redirecione o usuário para a página de lista de registros
header('Location: lista_registro.php');
exit;

?>