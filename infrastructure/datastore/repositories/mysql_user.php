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
        $sql = "INSERT INTO users (name, password) VALUES (:name, :password)";
        $stmt = $this->connection->prepare($sql);

        $name = $user->getName();
        $password = $user->getPassword();

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":password", $password);

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
        $sql = "UPDATE users SET name = :name, password = :password WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $name = $user->getName();
        $password = $user->getPassword();
        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public function getUserByName(string $name): ?User
    {
        $sql = "SELECT * FROM users WHERE name = :name";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":name", $name);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return new User($data['id'], $data['name'], $data['password']);
    }

    public function checkUserPassword(int $userID, string $password): bool
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $userID);
        $stmt->execute();
        $result = $stmt->fetch();

        $pass = $result->getPasssword();

        return $pass === $password;
    }

    public function getUserById(int $userID): ?User
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $userID);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        return new User($data['id'], $data['name'], $data['password']);
    }
}