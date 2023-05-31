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

    public function get() {

        $query = $this->db->prepare("");
    }


}