<?php
require_once("base.php");

if (!class_exists('Users')) {
    class Users extends Base
    {
        public function getUserFromEmail($email)
        {

            $query = $this->db->prepare("
                SELECT user_id, name, password, isActive, isAdmin
                FROM users
                WHERE email = ?
            ");

            $query->execute([
                $email
            ]);

            return $query->fetch();
        }

        public function create($data)
        {
            $query = $this->db->prepare("
            INSERT INTO users
            (name, email, password, address, city, postal_code, country)
            VALUES(?, ?, ?, ?, ?, ?, ?)
        ");

            $result = $query->execute([
                $data["name"],
                $data["email"],
                password_hash($data["password"], PASSWORD_DEFAULT),
                $data["address"],
                $data["city"],
                $data["postal_code"],
                $data["country"]
            ]);

            return $this->db->lastInsertId();
        }

        public function getUserById($user_id)
        {
            $query = $this->db->prepare("
                SELECT user_id, name, email, address, city, postal_code, country, isAdmin, isActive
                FROM users
                WHERE user_id = ?
            ");

            $query->execute([
                $user_id
            ]);

            return $query->fetch();
        }

        public function updateUser($data)
        {
            $query = $this->db->prepare("
            UPDATE users
            SET name = ?,
                email = ?,
                address = ?,
                city = ?,
                postal_code = ?,
                country = ?
            WHERE user_id = ?
            ");

            $query->execute([
                $data['name'],
                $data['email'],
                $data['address'],
                $data['city'],
                $data['postal_code'],
                $data['country'],
                $data['user_id']
            ]);

            return $query->rowCount() > 0;
        }
        public function updatePassword($data)
        {
            $query = $this->db->prepare("
            UPDATE users
            SET password = ?
            WHERE user_id = ?
        ");

            $hashedPassword = password_hash($data["password"], PASSWORD_DEFAULT);

            return $query->execute([
                $hashedPassword,
                $data["user_id"]
            ]);
        }

        public function get() {
            $query = $this->db->prepare("
            SELECT user_id, name, email, address, city, postal_code, country, created_at, isAdmin, isActive
            FROM users
            ");

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        public function getCountryCodes()
        {
            $query = $this->db->prepare("
                SELECT code
                FROM countries
            ");

            $query->execute();

            return $query->fetchAll(PDO::FETCH_COLUMN);
        }

        public function updateUserStatus($data)
        {
            $query = $this->db->prepare("
            UPDATE users
            SET isActive = ?
            WHERE user_id = ?
        ");

            $query->execute([$data['status'], $data['user_id']]);

            return $query->rowCount() > 0;
        }

        public function getUsersCount()
        {
            $query = $this->db->prepare("SELECT COUNT(*) as count FROM users");
            $query->execute();
            $result = $query->fetch();

            return $result['count'];
        }

    }
}