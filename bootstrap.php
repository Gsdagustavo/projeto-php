<?php

require_once __DIR__ . '/domain/usecases/usecase_user.php';
require_once __DIR__ . '/infrastructure/datastore/database.php';
require_once __DIR__ . '/infrastructure/datastore/repositories/mysql_user.php';

$connection = null;

try {
    $connection = getConnection();
} catch (PDOException $e) {
    error_log("failed to connect to database: " . $e->getMessage());
    http_response_code(500);
    exit();
}

if ($connection === null) {
    error_log("failed to connect to database");
    http_response_code(500);
    exit();
}

$userRepository = new UserRepository($connection);
$userUseCase = new UserUseCase($userRepository);

global $userRepository;
global $userUseCase;