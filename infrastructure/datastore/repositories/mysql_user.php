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
        $sql = "INSERT INTO users (username, password, email, birth_date) VALUES (:username, :password, :email, :birth_date)";
        $stmt = $this->connection->prepare($sql);

        $name = $user->getUsername();
        $password = $user->getPassword();
        $email = $user->getEmail();
        $birthDate = $user->getBirthdate()->format('Y-m-d');

        $stmt->bindParam(":username", $name);
        $stmt->bindParam(":password", $password);
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
        $sql = "UPDATE users 
            SET username = :username, email = :email, birth_date = :birth_date 
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);

        $id = $user->getId();
        $name = $user->getUsername();
        $email = $user->getEmail();
        $birthDate = $user->getBirthdate()->format('Y-m-d');

        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":username", $name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":birth_date", $birthDate);

        $stmt->execute();
        return $stmt->rowCount();
    }


    public function getAllUsers(): array
    {
        $sql = "SELECT id, username, email, birth_date FROM users";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUsersByName(string $name): array
    {
        $sql = "SELECT id, username, password, email, birth_date 
            FROM users 
            WHERE username LIKE :username";

        $stmt = $this->connection->prepare($sql);

        $like = "%$name%";
        $stmt->bindParam(":username", $like);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUserByEmail(string $email): ?User
    {
        $sql = "SELECT id, username, password, email, birth_date FROM users WHERE email = :email";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User($data['id'], $data['username'], $data['password'], $data['email'], new DateTime($data['birth_date']));
    }

    public function getUserById(int $userID): ?User
    {
        $sql = "SELECT id, username, password, email, birth_date 
            FROM users 
            WHERE id = :id";

        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(":id", $userID);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new User(
            $data['id'],
            $data['username'],
            $data['password'],
            $data['email'],
            new DateTime($data['birth_date'])
        );
    }

}