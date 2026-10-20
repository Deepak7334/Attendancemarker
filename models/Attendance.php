<?php

class Attendance
{
    private mysqli $conn;

    // ✅ DB connection via constructor
    public function __construct(mysqli $conn)
    {
        $this->conn = $conn;
    }

    /* =========================
       ADMIN: GET ALL ATTENDANCE
    ========================== */
    public static function getAll(): array
    {
        global $conn;
        $stmt = $conn->prepare(
            "SELECT a.*, u.username
             FROM attendance a
             JOIN users u ON a.user_id = u.id
             ORDER BY a.date DESC"
        );
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       EMPLOYEE: GET USER ATTENDANCE
    ========================== */
    public function getByUser(int $userId): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM attendance WHERE user_id = ? ORDER BY date DESC"
        );
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       EMPLOYEE: GET USER ATTENDANCE BY DATE RANGE
    ========================== */
    public function getByUserAndDateRange(int $userId, string $startDate, string $endDate): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM attendance WHERE user_id = ? AND date BETWEEN ? AND ? ORDER BY date DESC"
        );
        $stmt->bind_param("iss", $userId, $startDate, $endDate);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       EMPLOYEE: GET USER ATTENDANCE FROM DATE
    ========================== */
    public function getByUserFromDate(int $userId, string $startDate): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM attendance WHERE user_id = ? AND date >= ? ORDER BY date DESC"
        );
        $stmt->bind_param("is", $userId, $startDate);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       EMPLOYEE: GET USER ATTENDANCE TO DATE
    ========================== */
    public function getByUserToDate(int $userId, string $endDate): array
    {
        $stmt = $this->conn->prepare(
            "SELECT * FROM attendance WHERE user_id = ? AND date <= ? ORDER BY date DESC"
        );
        $stmt->bind_param("is", $userId, $endDate);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /* =========================
       GET TODAY ATTENDANCE
    ========================== */
    public function getToday(int $userId): ?array
    {
        $today = date('Y-m-d');

        $stmt = $this->conn->prepare(
            "SELECT * FROM attendance WHERE user_id = ? AND date = ?"
        );
        $stmt->bind_param("is", $userId, $today);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc() ?: null;
    }

    /* =========================
       MARK ATTENDANCE
    ========================== */
    public function mark(int $userId): bool
    {
        $date = date('Y-m-d');
        $time = date('H:i:s');

        $stmt = $this->conn->prepare(
            "SELECT id FROM attendance WHERE user_id = ? AND date = ?"
        );
        $stmt->bind_param("is", $userId, $date);
        $stmt->execute();
        $record = $stmt->get_result()->fetch_assoc();

        if ($record) {
            // Checkout
            $stmt = $this->conn->prepare(
                "UPDATE attendance SET check_out = ? WHERE id = ?"
            );
            $stmt->bind_param("si", $time, $record['id']);
            return $stmt->execute();
        }

        // Check-in
        $stmt = $this->conn->prepare(
            "INSERT INTO attendance (user_id, date, check_in) VALUES (?, ?, ?)"
        );
        $stmt->bind_param("iss", $userId, $date, $time);
        return $stmt->execute();
    }

    /* =========================
       ADMIN: MARK ATTENDANCE BY STATUS
    ========================== */
    public function markByAdmin(int $userId, string $date, string $status): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO attendance (user_id, date, status)
             VALUES (?, ?, ?)
             ON DUPLICATE KEY UPDATE status = VALUES(status)"
        );
        $stmt->bind_param("iss", $userId, $date, $status);
        return $stmt->execute();
    }

    /* =========================
       GET EMPLOYEES WITH ATTENDANCE FOR DATE
    ========================== */
    public function getEmployeesWithAttendance(string $date): array
    {
        $stmt = $this->conn->prepare(
            "SELECT u.id, u.username, u.email, a.status
             FROM users u
             LEFT JOIN attendance a
                 ON u.id = a.user_id AND a.date = ?
             WHERE u.role = 'employee'
             ORDER BY u.id ASC"
        );
        $stmt->bind_param("s", $date);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
 }
