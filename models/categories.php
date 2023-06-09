<?php
require_once("base.php");

if (!class_exists('Categories')) {
class Categories extends Base
{
    public function get() {
        $query = $this->db->prepare("
        SELECT category_id, name
        FROM categories
        ");

        $query->execute();

        return $query->fetchAll();
    }
    public function getItem($id) {
        $query = $this->db->prepare("
        SELECT
            category_id,
            name
        FROM
            categories
        WHERE
            category_id = ?
    ");

        $query->execute([$id]);

        return $query->fetch();
    }

    public function create($data) {
        $query = $this->db->prepare("
        INSERT INTO categories
        (name)
        VALUES (?)
        ");

        $query->execute([
            $data["name"]
        ]);

        $data["category_id"] = $this->db->lastInsertId();

        return $data;
    }

    public function update($data) {
        $query = $this->db->prepare("
        UPDATE
            categories
        SET
            name = ?
        WHERE
            category_id = ?
        ");

        $query->execute([
            $data["name"],
            $data["category_id"]
        ]);

        return $data;
    }

    public function delete($id) {
        $query = $this->db->prepare("
            DELETE FROM categories
            WHERE category_id = ?
        ");

        return $query->execute([$id]);
        }

        public function getCategory($id) {
            $query = $this->db->prepare("
            SELECT category_id, name
            FROM categories
            WHERE category_id = ?
        ");

            $query->execute([$id]);

            return $query->fetch();
        }
    }
}