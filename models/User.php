<?php
require_once __DIR__ . '/../config/database.php';

class User {
    public static function findByUsername($username) {
        global $conn;
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function findById($id) {
        global $conn;
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public static function getAllEmployees($search = '') {
        global $conn;
        $sql = "SELECT * FROM users WHERE role = 'employee'";
        if (!empty($search)) {
            $sql .= " AND (username LIKE ? OR email LIKE ?)";
        }
        $stmt = $conn->prepare($sql);
        if (!empty($search)) {
            $searchParam = '%' . $search . '%';
            $stmt->bind_param("ss", $searchParam, $searchParam);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public static function create($data) {
        global $conn;
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $data['username'], $data['password'], $data['email'], $data['role']);
        return $stmt->execute();
    }

    public static function update($id, $data) {
        global $conn;
        $sql = "UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $data['username'], $data['email'], $data['role'], $id);
        return $stmt->execute();
    }

    public static function delete($id) {
        global $conn;
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
