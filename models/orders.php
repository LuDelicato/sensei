<?php
require_once("base.php");

class Orders extends Base
{

    public $requiresAuth = false;
    public function get() {

        $query = $this->db->prepare("
            SELECT
                orders.order_id,
                orders.user_id,
                users.name,
                orders.order_date,
                orders.payment_date
            FROM
                orders
            INNER JOIN
                users USING(user_id)
            WHERE
                orders.user_id = ?
            ORDER BY
                order_date DESC
        ");

        $query->execute([ $this->authUser["user_id"] ]);

        return $query->fetchAll();
    }

    public function getItem($id) {

        $query = $this->db->prepare("
            SELECT
                orders.order_id,
                orders.user_id,
                users.name,
                users.email,
                users.address,
                users.city,
                users.postal_code,
                users.country,
                orders.order_date,
                orders.payment_date
            FROM
                orders
            INNER JOIN
                users USING(user_id)
            WHERE
                orders.order_id = ? AND
                orders.user_id = ?
        ");

        $query->execute([ $id, $this->authUser["user_id"] ]);

        $order = $query->fetch();

        if(!empty($order)) {

            $query = $this->db->prepare("
                SELECT
                    orderdetails.product_id,
                    products.name,
                    orderdetails.quantity,
                    orderdetails.price_each
                FROM
                    orderdetails
                INNER JOIN
                    products USING(product_id)
                WHERE
                    orderdetails.order_id = ?
            ");

            $query->execute([ $id ]);

            $order["products"] = $query->fetchAll();
        }

        return $order;
    }

    public function create($data) {

        $data["user_id"] = $this->authUser["user_id"];

        $order_id = $this->createHeader( $data["user_id"] );

        foreach($data["products"] as $product) {
            $this->createDetail($order_id, $product);
        }

        $data["order_id"] = $order_id;

        return $data;
    }

    public function createHeader($user_id) {

        $query = $this->db->prepare("
            INSERT INTO orders
            (user_id)
            VALUES(?)
        ");

        $query->execute([ $user_id ]);

        return $this->db->lastInsertId();
    }

    public function createDetail($order_id, $product) {

        $query = $this->db->prepare("
            INSERT INTO orderdetails
            (order_id, product_id, quantity, price_each)
            VALUES(?, ?, ?, ?)
        ");

        return $query->execute([
            $order_id,
            $product["product_id"],
            $product["quantity"],
            $product["price"]
        ]);
    }

    public function delete($id) {

        $order = $this->getItem( $id );
        if(empty($order)) {
            http_response_code(403);
            die('{"message":"Forbidden, you are not the owner of this order"}');
        }

        $query = $this->db->prepare("
            DELETE FROM orderdetails
            WHERE order_id = ?
              AND order_id IN(
                SELECT order_id
                FROM orders
                WHERE order_id = ?
                  AND user_id = ?
              )
        ");

        $query->execute([
            $id,
            $id,
            $this->authUser["user_id"]
        ]);

        $query = $this->db->prepare("
            DELETE FROM orders
            WHERE order_id = ?
              AND user_id = ?
        ");

        $query->execute([
            $id,
            $this->authUser["user_id"]
        ]);

        return true;
    }

    public function getUserOrders($user_id)
    {
        $query = $this->db->prepare("
        SELECT
            u.user_id,
            o.order_id,
            o.order_date,
            p.name AS product_name,
            o.payment_date,
            od.quantity,
            od.price_each,
            (od.quantity * od.price_each) AS subtotal
        FROM
            users u
        INNER JOIN
            orders o ON u.user_id = o.user_id
        INNER JOIN
            orderdetails od ON o.order_id = od.order_id
        INNER JOIN
            products p ON od.product_id = p.product_id
        WHERE
            u.user_id = ?
        ORDER BY
            o.order_date DESC
    ");

        $query->execute([$user_id]);

        return $query->fetchAll();
    }
}
