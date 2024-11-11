<?php
//difinir o tempo de saida // ele conta por segundos 
$periodoauto = 10; 

//diferenciar as condicoes para saida do pessoal da escola
$resultauto = mysqli_query($conexao, "SELECT * FROM entrada_saida WHERE sit_escola = 1");

// Loop pelos cadastros
while ($data = mysqli_fetch_assoc($resultauto)) {
    // Verifique se o cadastro já tem saída marcada
    if (empty($data['saida'])) {
        // Calcule a data e hora de saída
        $saida = strtotime($data['entrada']) + $periodoaauto;

        // Marque a saída automaticamente
        mysqli_query($conexao, "UPDATE entrada_saida SET saida = FROM_UNIXTIME($saida) WHERE idcadastro = '$data[idcadastro]'");
    }
}
?>