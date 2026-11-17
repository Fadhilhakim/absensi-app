<?php
namespace App\Model;

use PDO;
use App\Model\Database;

class AdminModel {
    private $conn;
    private $table = 'admin';

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    /**
     * Get all admins
     */
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT id, username, role FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get admin by ID
     */
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, role FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Get admin by username (for login)
     */
    public function getByUsername($username) {
        $stmt = $this->conn->prepare("SELECT id, username, password, role FROM {$this->table} WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Check if username exists
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT 1 FROM {$this->table} WHERE username = :username";
        if ($excludeId) {
            $sql .= " AND id != :id";
        }
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        if ($excludeId) {
            $stmt->bindParam(':id', $excludeId, PDO::PARAM_INT);
        }
        $stmt->execute();
        return (bool)$stmt->fetchColumn();
    }

    /**
     * Create new admin
     */
    public function create($username, $password, $role = 'Admin') {
        // Check if username already exists
        if ($this->usernameExists($username)) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, password, role) VALUES (:username, :password, :role)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $role);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Admin berhasil ditambahkan', 'id' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'message' => 'Gagal menambahkan admin'];
    }

    /**
     * Update admin
     */
    public function update($id, $username, $role, $password = null) {
        // Check if new username exists (excluding current id)
        if ($this->usernameExists($username, $id)) {
            return ['success' => false, 'message' => 'Username sudah digunakan'];
        }

        if ($password) {
            // Update with password
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET username = :username, password = :password, role = :role WHERE id = :id");
            $stmt->bindParam(':password', $hashedPassword);
        } else {
            // Update without password
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET username = :username, role = :role WHERE id = :id");
        }
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Admin berhasil diperbarui'];
        }
        return ['success' => false, 'message' => 'Gagal memperbarui admin'];
    }

    /**
     * Delete admin
     */
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Admin berhasil dihapus'];
        }
        return ['success' => false, 'message' => 'Gagal menghapus admin'];
    }

    /**
     * Verify login credentials
     */
    public function verifyLogin($username, $password) {
        $admin = $this->getByUsername($username);
        
        if (!$admin) {
            return ['success' => false, 'message' => 'Username tidak ditemukan'];
        }

        if (!password_verify($password, $admin['password'])) {
            return ['success' => false, 'message' => 'Password salah'];
        }

        // Return safe data (without password)
        return [
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id' => $admin['id'],
                'username' => $admin['username'],
                'role' => $admin['role']
            ]
        ];
    }
}
?>
