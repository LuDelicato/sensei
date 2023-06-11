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

    function validateLogin($email, $password) {
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if (empty($password) || mb_strlen($password) > 1000) {
            return false;
        }

        // Sanitize inputs
        $adminModel = new Admin();
        $email = $adminModel->sanitize($email);
        $password = $adminModel->sanitize($password);

        return [
            'email' => $email,
            'password' => $password
        ];
    }

}
