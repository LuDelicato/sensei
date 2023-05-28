<?php
require_once("base.php");

class Products extends Base
{
    public $requiresAuth = false;

    public function get($category_id = null)
    {

        $query = $this->db->prepare("
            SELECT
                products.product_id,
                products.name,
                products.photo AS image,
                products.price,
                categories.name AS category
            FROM
                products
            INNER JOIN
                categories USING(category_id)
            WHERE
                ? IS NULL OR products.category_id = ?
        ");

        $query->execute([
            $category_id,
            $category_id
        ]);

        return $query->fetchAll();
    }

    public function getItem($id)
    {

        $query = $this->db->prepare("
            SELECT
                product_id, name, description, price,
                stock, photo AS image, photo, category_id
            FROM products
            WHERE product_id = ?
        ");

        $query->execute([
            $id
        ]);

        return $query->fetch();

    }

    public function create($data)
    {

        $query = $this->db->prepare("
            INSERT INTO products
            (name, description, price, stock, photo, category_id)
            VALUES(?, ?, ?, ?, ?, ?)
        ");

        $photo = $this->handleUploadedImage($data["photo"]);

        $query->execute([
            $data["name"],
            $data["description"],
            $data["price"],
            $data["stock"],
            $photo,
            $data["category_id"]
        ]);

        $data["product_id"] = $this->db->lastInsertId();

        return $data;
    }

    public function handleUploadedImage($image)
    {
        $extract = base64_decode($image);

        if (empty($extract)) {
            return "";
        }

        $filename = bin2hex(random_bytes(16)) . ".jpg";

        file_put_contents("images/" . $filename, $extract);

        return $filename;
    }

}

