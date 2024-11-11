<?php

    $dbHost = 'LocalHost';
    $dbUsername = 'root';
    $dbPassword = 'root';
    $dbName = 'loja1';

    $conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

    // if($conexao->connect_error)
    // {
    //     echo "Erro";
    // }  
    // else
    // {
    //     echo "Conexão efetuada com sucesso";
    // } 

?>