<?php

#Serve.php
/*
Servidor: 127.0.0.1:3306
Banco de dados: u139216883_Users
Tabela: User

function conectar() {
    $host = "srv813.hstgr.io";
    $port = 3306;
    $db = "u139216883_Users";
    $user = "u139216883_Thiago";
    $pass = "qpccK?vg7]xr5cy";

    $conexao = mysqli_connect($host, $user, $pass, $db, $port);
    if (!$conexao) {
        die("Conexão falhou: " . mysqli_connect_error());
    }
    return $conexao;
}
*/
function conectar() {
    $servername = "srv813.hstgr.io"; 
    $database = "u139216883_Users";
    $username = "u139216883_Thiago";
    $password = "qpccK?vg7]xr5cy";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $database, 3306); 

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    // Retorna a conexão aberta
    return $conn;
}

?>