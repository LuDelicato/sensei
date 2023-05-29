<?php
require_once("base.php");

class Products extends Base
{
    public $requiresAuth = false;

    public function get()
    {

        $query = $this->db->prepare("
             SELECT
                product_id,
                name,
                photo AS image,
                price
            FROM
                products
    ");
        $query->execute();

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



    public function update($data)
    {

    if (!empty($data["photo"])) {
        $photo = $this->handleUploadedImage($data["photo"]);
    } else {
        $existingProduct = $this->getItem(($data["id"]));
        $photo = $existingProduct["photo"];

    }
    $query = $this->db->prepare("
            UPDATE
                products
            SET
                name = ?,
                description = ?,
                price = ?,
                stock = ?,
                category_id = ?,
                photo = ?
            WHERE
                product_id = ?
        ");

    $query->execute([
        $data["name"],
        $data["description"],
        $data["price"],
        $data["stock"],
        $data["category_id"],
        $photo,
        $data["id"]
    ]);

    return $data;
  }

    public function delete($id)
    {

        $query = $this->db->prepare("
            DELETE FROM products
            WHERE product_id = ?
        ");

        return $query->execute([$id]);
    }
}