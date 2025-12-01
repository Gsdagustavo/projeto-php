<?php

global $userUseCase;
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../domain/entities/user.php';

$method = $_SERVER['REQUEST_METHOD'];
$route = $_GET['route'] ?? null;

header('Content-Type: application/json');

if ($method == 'POST' && $route == 'login') {
    $body = json_decode(file_get_contents('php://input'), true);

    $result = $userUseCase->login($body['username'], $body['password']);
    if ($result !== null) {
        echo json_encode([
            'success' => false,
            'message' => $result
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Login bem sucedido!'
    ]);

    exit;
}

if ($method == 'POST' && $route == 'register') {
    $body = json_decode(file_get_contents('php://input'), true);

    $result = $userUseCase->register($body['username'], $body['email'], $body['birthdate'], $body['password']);
    if ($result !== null) {
        echo json_encode([
            'success' => false,
            'message' => $result
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Registro bem sucedido!',
    ]);

    exit;
}

if ($method == 'GET' && $route == 'users') {
    $users = $userUseCase->getAllUsers();
    echo json_encode($users);
    exit;
}

if ($method == 'POST' && $route == 'users') {
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body
        || empty($body['username'])
        || empty($body['email'])
        || empty($body['birthdate'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON body'
        ]);
        exit;
    }

    // Parse date as DateTime
    $birth = DateTime::createFromFormat('Y-m-d', $body['birthdate']);
    if (!$birth) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid birthdate format (expected Y-m-d)'
        ]);
        exit;
    }

    // Create domain entity
    $user = new User(
        id: 0,
        username: $body['username'],
        email: $body['email'],
        birthdate: $birth
    );

    // Call use case
    $result = $userUseCase->addUser($user);

    if ($result !== null) {
        echo json_encode([
            'success' => false,
            'message' => $result
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'User created successfully!'
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Route not found']);