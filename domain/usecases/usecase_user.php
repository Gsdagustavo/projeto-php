<?php

const internalError = "Erro interno no servidor. Tente novamente mais tarde";

class UserUseCase
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $email, string $password): ?string
    {
        $user = $this->userRepository->getUserByEmail($email);
        if (!$user) {
            return 'Credenciais inválidas';
        }

        if ($password != $user->getPassword()) {
            return 'Credenciais inválidas';
        }

        return null;
    }

    public function register(string $name, string $email, DateTime $birthdate, string $password): ?string
    {
        $existing = $this->userRepository->getUserByEmail($name);
        if ($existing) {
            return 'Usuario já existente';
        }

        $user = new User(0, $name, $password, $email, $birthdate);
        return $this->addUser($user);
    }

    public function addUser(User $user): ?string
    {
        $validation = $this->validateUser($user);
        if ($validation !== null) {
            return $validation;
        }

        $existing = $this->userRepository->getUserByEmail($user->getEmail());
        if ($existing) {
            return "Usuario ja existente";
        }

        $rowsAffected = $this->userRepository->addUser($user);
        if ($rowsAffected != 1) {
            return "Usuário com informações inválidas";
        }

        return null;
    }

    private function validateUser(User $user): ?string
    {
        $user->setUsername(trim($user->getUsername()));
        $user->setEmail(trim($user->getEmail()));
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
