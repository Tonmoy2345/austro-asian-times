<?php
// user model - login and account queries

class User extends Model {

    public function findByUsername(string $username): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, username, email, password, role, created_at
             FROM users
             WHERE username = :username
             LIMIT 1"
        );
        $stmt->execute([':username' => $username]);
        return $stmt->fetch();
    }

    public function findById(int $id): array|false {
        $stmt = $this->db->prepare(
            "SELECT id, username, email, role, created_at
             FROM users
             WHERE id = :id
             LIMIT 1"
        );
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function getAll(): array {
        $stmt = $this->db->query(
            "SELECT id, username, email, role, created_at FROM users ORDER BY role, username"
        );
        return $stmt->fetchAll();
    }

    public function create(string $username, string $email, string $password, string $role = 'journalist'): bool {
        $hashed = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare(
            "INSERT INTO users (username, email, password, role)
             VALUES (:username, :email, :password, :role)"
        );
        return $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $hashed,
            ':role'     => $role,
        ]);
    }

    public function verifyPassword(string $plain, string $hashed): bool {
        return password_verify($plain, $hashed);
    }

    public function usernameExists(string $username): bool {
        $stmt = $this->db->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        return (bool) $stmt->fetch();
    }
}
