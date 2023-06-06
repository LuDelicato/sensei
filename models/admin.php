<?php
require("base.php");

class Admin extends Base
{
    public function login($data)
    {

        $email = $data["email"] ?? null;

        $query = $this->db->prepare("
            SELECT email, password, isAdmin
            FROM users
            WHERE email = ?
        ");

        $query->execute([$email]);

        $admin = $query->fetch();

        if (!empty($admin) && password_verify($data["password"], $admin["password"]) && $admin["isAdmin"] == 1) {
            return $admin;
        }

        return [];
    }
}
