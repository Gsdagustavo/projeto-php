<?php

class UserRepository
{

    public PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function addUser(User $user): int
    {
        $sql = "INSERT INTO users (username, email, birth_date) VALUES (:username, :email, :birth_date)";
        $stmt = $this->connection->prepare($sql);

        $name = $user->getUsername();
        $email = $user->getEmail();
        $birthDate = $user->getBirthdate();

        $stmt->bindParam(":username", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":birth_date", $birthDate);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function removeUser(int $id): int
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function updateUser(User $user): int
    {
        $sql = "UPDATE users SET username = :username, email = :email, birth_date = :birth_date WHERE id = :id";
        $stmt = $this->connection->prepare($sql);

        $name = $user->getUsername();
        $email = $user->getEmail();
        $birthDate = $user->getBirthdate();

        $stmt->bindParam(":username", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":birth_date", $birthDate);

        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getAllUsers(): array
    {
        $sql = "SELECT * FROM users";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getUserByName(string $name): ?User
    {
        $sql = "SELECT (id, username, email, birth_date) FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":username", $name);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return new User($data['id'], $data['username'], $data['email'], $data['birth_date']);
    }


    public function getUserById(int $userID): ?User
    {
        $sql = "SELECT (id, username, email, birth_date) FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $userID);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        return new User($data['id'], $data['username'], $data['email'], $data['birth_date']);
    }
}