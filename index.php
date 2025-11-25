<?php
require_once __DIR__ . '/infrastructure/datastore/database.php';
require_once __DIR__ . '/infrastructure/datastore/repositories/mysql_user.php';

$connection = getConnection();

if ($connection == null) {
    return null;
}

$userRepository = new UserRepository($connection);
