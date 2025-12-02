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

    $name = $body["name"] ?? null;
    $email = $body["email"] ?? null;
    $birth = $body["birthdate"] ?? null;
    $password = $body["password"] ?? null;

    if (!$name || !$email || !$birth || !$password) {
        echo json_encode([
            'success' => false,
            'message' => 'Campos obrigatórios faltando.'
        ]);
        exit;
    }

    try {
        $birthdate = new DateTime($birth);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Data de nascimento inválida.'
        ]);
        exit;
    }

    $result = $userUseCase->register($name, $email, $birthdate, $password);

    if ($result !== null) {
        echo json_encode([
            'success' => false,
            'message' => $result
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Registro bem sucedido!'
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
            'message' => 'Campos obrigatórios faltando'
        ]);
        exit;
    }

    $birth = DateTime::createFromFormat('Y-m-d', $body['birthdate']);
    if (!$birth) {
        echo json_encode([
            'success' => false,
            'message' => 'Formato de data inválido (Y-m-d)'
        ]);
        exit;
    }

    $user = new User(
        0,
        $body['username'],
        "123",
        $body['email'],
        $birth
    );

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
        'message' => 'Usuário criado com sucesso!'
    ]);
    exit;
}

if ($method == 'PUT' && $route == 'users') {
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body || empty($body['id']) || empty($body['username']) || empty($body['email']) || empty($body['birthdate'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid JSON body'
        ]);
        exit;
    }

    $birth = DateTime::createFromFormat('Y-m-d', $body['birthdate']);
    if (!$birth) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid birthdate format (expected Y-m-d)'
        ]);
        exit;
    }

    $user = new User(
        id: $body['id'],
        username: $body['username'],
        password: "123",
        email: $body['email'],
        birthdate: $birth
    );

    $result = $userUseCase->updateUser($user);

    if ($result !== null) {
        echo json_encode([
            'success' => false,
            'message' => $result
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Usuário atualizado com sucesso!'
    ]);
    exit;
}

if ($method == 'DELETE' && $route == 'users') {
    $body = json_decode(file_get_contents('php://input'), true);

    if (!$body || empty($body['id'])) {
        echo json_encode([
            'success' => false,
            'message' => 'ID inválido'
        ]);
        exit;
    }

    $user = $userUseCase->getUserById($body['id']);
    if (!$user) {
        echo json_encode([
            'success' => false,
            'message' => 'Usuário não encontrado'
        ]);
        exit;
    }

    $result = $userUseCase->removeUser($user);

    if ($result !== null) {
        echo json_encode(['success' => false, 'message' => $result]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'message' => 'Usuário removido com sucesso!'
    ]);
    exit;
}

http_response_code(404);
echo json_encode(['error' => 'Route not found']);