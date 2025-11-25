<?php

function getConnection(): ?PDO
{
    $host = "localhost";
    $user = "root";
    $password = "admin";
    $dbname = "projeto_php";

    try {
        return new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    } catch (PDOException $e) {
        echo "Erro de conexão: " . $e->getMessage();
    }

    return null;
}