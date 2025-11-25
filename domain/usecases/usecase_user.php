<?php

const internalError = "Erro interno no servidor. Tente novamente mais tarde";

class UserUseCase
{
    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(string $name, string $password): ?string
    {
        $user = $this->userRepository->getUserByName($name);
        if (!$user) {
            return "Usuário inválido";
        }

        if ($password != $user->getPassword()) {
            return "Senha inválida";
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
            return "Credenciais inválidas";
        }

        return null;
    }

    private function validateUser(User $user): ?string
    {
        $user->setName(trim($user->getName()));
        $user->setPassword(trim($user->getPassword()));

        if ($user->getName() == "" || strlen($user->getName()) == 0) {
            return "Nome inválido";
        }

        if ($user->getPassword() == "" || strlen($user->getPassword()) == 0) {
            return "Nome inválido";
        }

        return null;
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
