<?php

require_once __DIR__ . '/domain/usecases/usecase_user.php';
require_once __DIR__ . '/infrastructure/datastore/database.php';
require_once __DIR__ . '/infrastructure/datastore/repositories/mysql_user.php';

$connection = getConnection();

if ($connection === null) {
    throw new Exception("Database connection failed");
}

$userRepository = new UserRepository($connection);
$userUseCase = new UserUseCase($userRepository);

global $userRepository;
global $userUseCase;