<?php

if (!class_exists('Base')) {
class Base
    {
        public PDO $db;

        public function __construct()
            {
                $this->db = new PDO(
                    "mysql:host=" . ENV["DB_HOST"] . ";dbname=" . ENV["DB_NAME"] . ";charset=utf8mb4",
                    ENV["DB_USER"],
                    ENV["DB_PASSWORD"],
                    [
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            }

        public function sanitize($input)
        {
            if (is_array($input)) {
                foreach ($input as $key => $value) {
                    $input[$key] = $this->sanitize($value);
                }
            } else {
                $input = trim($input);
                $input = stripslashes($input);
                $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            }

            return $input;
        }
    }
}