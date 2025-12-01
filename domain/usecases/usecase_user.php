<?php

const internalError = "Erro interno no servidor. Tente novamente mais tarde";

const name = "Gsdagustavo";
const password = "123";

class UserUseCase
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $name, string $password): ?string
    {
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $valid = $this->userRepository->login($name, $password);
        if (!$valid) {
            return 'Credenciais inválidas';
        }

        return null;
    }

    public function register(string $name, string $email, string $birthdate, string $password): ?string
    {
        $user = new User(0, $name, $email, DateTime::createFromFormat('Y-m-d', $birthdate));
        $validation = $this->validateUser($user);
        if ($validation != null) {
            return $validation;
        }

        $existing = $this->userRepository->getUserByName($name);
        if ($existing != null) {
            return 'Um usuário com esse nome já existe';
        }

        $rows = $this->userRepository->addUser($user);
        if ($rows != 1) {
            return 'Erro interno no servidor. Tente novamente mais tarde';
        }

        return null;
    }

    private function validateUser(User $user): ?string
    {
        $user->setUsername(trim($user->getUsername()));
        $user->setEmail(trim($user->getEmail()));
        $user->setPassword(trim($user->getPassword()));
        if ($user->getUsername() == "" || strlen($user->getUsername()) == 0) {
            return 'Nome inválido';
        }

        if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL) === false) {
            return 'Email inválido';
        }

        $minTime = new DateTime('1900-01-01');
        $maxTime = new DateTime('now');
        if ($user->getBirthdate() <= $minTime || $user->getBirthdate() >= $maxTime) {
            return 'Data de nascimento inválida';
        }

        if (strlen($user->getPassword()) < 3) {
            return 'Senha inválida';
        }

        return null;
    }

    public function addUser(User $user): ?string
    {
        $validation = $this->validateUser($user);
        if ($validation !== null) {
            return $validation;
        }

        $rowsAffected = $this->userRepository->addUser($user);
        if ($rowsAffected != 1) {
            return "Usuario com informacoes inválidas";
        }

        return null;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->getAllUsers();
    }

    public function updateUser(User $user): ?string
    {
        $validation = $this->validateUser($user);
        if ($validation !== null) {
            return $validation;
        }

        $rowsAffected = $this->userRepository->updateUser($user);
        if ($rowsAffected != 1) {
            return internalError;
        }

        return null;
    }

    public function removeUser(User $user): ?string
    {
        $existing = $this->userRepository->getUserById($user->getId());
        if ($existing == null) {
            return "Usuário inválido";
        }

        $rowsAffected = $this->userRepository->removeUser($user->getId());
        if ($rowsAffected != 1) {
            return internalError;
        }

        return null;
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->getUserById($id);
    }
}
