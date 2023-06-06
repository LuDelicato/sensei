<?php

require_once("base.php");

if (!class_exists('Products')) {

class Products extends Base
{

    public function get()
    {
        $query = $this->db->prepare("
         SELECT
            product_id,
            name,
            photo,
            price,
            stock, 
            description, 
            category_id
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
            stock, photo, category_id
        FROM products
        WHERE product_id = ?
    ");

        $query->execute([$id]);

        return $query->fetch();
    }
    public function getByCategory($category_id)
    {
        $query = $this->db->prepare("
            SELECT
                product_id,
                name,
                photo,
                price
            FROM
                products
            WHERE
                category_id = ?
        ");

        $query->execute([$category_id]);

        return $query->fetchAll();
    }

    public function create($data)
    {
        $query = $this->db->prepare("
        INSERT INTO products
        (name, description, price, stock, photo, category_id)
        VALUES (?, ?, ?, ?, ?, ?)
    ");

        $photo = $this->handleUploadedImage($_FILES["photo"]);

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
        if (empty($image['tmp_name'])) {
            return "";
        }

        $extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;

        $destination = "images/" . $filename;

        if (move_uploaded_file($image['tmp_name'], $destination)) {
            return $filename;
        } else {
            return "";
        }
    }
    public function update($data)
    {
        // check if new img is uploaded
        if (isset($_FILES['photo']) && !empty($_FILES['photo']['tmp_name'])) {
            $photo = $this->handleUploadedImage($_FILES['photo']);

            $query = $this->db->prepare("
            UPDATE products
            SET
                name = ?,
                description = ?,
                price = ?,
                stock = ?,
                category_id = ?,
                photo = ?
            WHERE product_id = ?
        ");

            $query->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['category_id'],
                $photo,
                $data['product_id']
            ]);
        } else {
// img = 0, remove it from query
            $query = $this->db->prepare("
            UPDATE products
            SET
                name = ?,
                description = ?,
                price = ?,
                stock = ?,
                category_id = ?
            WHERE product_id = ?
        ");
            $query->execute([
                $data['name'],
                $data['description'],
                $data['price'],
                $data['stock'],
                $data['category_id'],
                $data['product_id']
            ]);
        }
    }
    public function delete($id)
    {
        $query = $this->db->prepare("
            DELETE FROM products
            WHERE product_id = ?
        ");

        return $query->execute([$id]);
    }
    public function getProductWithinStock($data)
    {
        $query = $this->db->prepare("
            SELECT product_id, name, price, stock
            FROM products
            WHERE product_id = ?
              AND stock >= ?
        ");

        $query->execute([
            $data["product_id"],
            $data["quantity"]
        ]);

        return $query->fetch();
    }
    public function updateProductStock($product)
    {
        $query = $this->db->prepare("
            UPDATE products
            SET stock = stock - ?
            WHERE product_id = ?
        ");

        return $query->execute([
            $product["quantity"],
            $product["product_id"]
        ]);
    }
}
}