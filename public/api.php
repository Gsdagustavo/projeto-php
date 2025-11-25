<?php

global $userUseCase;
require_once __DIR__ . '/../bootstrap.php';

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

if ($method == 'GET' && $route == 'users') {
    $users = $userUseCase->getAllUsers();
    echo json_encode($users);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Route not found']);