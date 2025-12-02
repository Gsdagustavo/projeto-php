<?php

function getConnection(): ?PDO
{
    $host = "localhost";
    $user = "root";
    $password = "root";
    $dbname = "projeto_php";

    try {
        return new PDO("mysql:host=$host;port=3306;dbname=$dbname", $user, $password);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage());
    }

    return null;
}