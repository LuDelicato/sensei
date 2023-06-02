<?php
require_once("base.php");

class Users extends Base
{
    public function getUserFromEmail($email)
    {

        $query = $this->db->prepare("
            SELECT user_id, name, password
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
            SELECT user_id, name, email, address, city, postal_code, country
            FROM users
            WHERE user_id = ?
        ");

        $query->execute([
            $user_id
        ]);

        return $query->fetch();
    }

    public function getUserProfile($user_id)
    {
        $query = $this->db->prepare("
            SELECT name, email, password, address, city, postal_code, country
            FROM users
            WHERE user_id = ?
        ");

        $query->execute([$user_id]);

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
            postal_code = ?
        WHERE user_id = ?
    ");

        $query->execute([
            $data['name'],
            $data['email'],
            $data['address'],
            $data['city'],
            $data['postal_code'],
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
}
