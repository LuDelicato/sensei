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
            $sanitized = $this->sanitize($data);

            $photo = $this->handleUploadedImage($_FILES["photo"]);

            $sql = "
                INSERT INTO products 
                    (name, description, price, stock, photo, category_id)
                VALUES (?, ?, ?, ?, ?, ?)";

            $query = $this->db->prepare($sql);

            $query->execute([
                $sanitized["name"],
                $sanitized["description"],
                $sanitized["price"],
                $sanitized["stock"],
                $photo,
                $sanitized["category_id"]
            ]);

            $sanitized["product_id"] = $this->db->lastInsertId();

            return $sanitized;
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
            $data = $this->sanitize($data);

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
            ");

            $query->execute([$data["product_id"]]);
            $product = $query->fetch();

            if ($product && $product["stock"] >= $data["quantity"]) {
                return $product;
            }

            return null;
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
        public function validate($data)
        {
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $stock = $data['stock'] ?? '';
            $photo = $_FILES['photo'] ?? null;
            $errors = [];

            if (empty($name) || strlen($name) < 3 || strlen($name) > 100) {
                $errors[] = "Product name must be between 3 and 100 characters.";
            }

            if (empty($description) || strlen($description) < 10 || strlen($description) > 1000) {
                $errors[] = "Product description must be between 10 and 1000 characters.";
            }

            if (!is_numeric($price) || $price < 0 || $price > 9999999.99) {
                $errors[] = "Product price must be a numeric value between 0 and 9999999.99.";
            }

            if (!is_numeric($stock) || $stock < 1 || $stock > 500) {
                $errors[] = "Product stock must be a numeric value between 1 and 500.";
            }

            if ($photo && $photo['error'] === UPLOAD_ERR_OK) {
                $allowedExtensions = ['gif', 'jpg', 'webp'];
                $extension = pathinfo($photo['name'], PATHINFO_EXTENSION);

                if (!in_array(strtolower($extension), $allowedExtensions)) {
                    $errors[] = "Invalid image file format. Allowed formats: GIF, JPG, WebP.";
                }
            }

            return $errors;
        }

        public function getProductCount()
        {
            $query = $this->db->prepare("
                SELECT COUNT(*) as count FROM products
                ");
            $query->execute();
            $result = $query->fetch();

            return $result['count'];
        }

        public function getProductBelowStock()
        {
            $query = $this->db->prepare("
                SELECT p.product_id, p.name, p.price, p.stock, c.name AS category_name
                FROM products p
                INNER JOIN categories c ON p.category_id = c.category_id
                WHERE p.stock <= 10
        ");

            $query->execute();

            return $query->fetchAll();
        }

    }
}