<?php
require_once("base.php");

class Categories extends Base
{

    public function get() {
        $query = $this->db->prepare("
            SELECT category_id, name
            FROM categories
            WHERE parent_id IS NULL OR parent_id = 0
        ");

        $query->execute();

        return $query->fetchAll();
    }

    public function getItem($id) {
        $query = $this->db->prepare("
        SELECT
            c1.category_id,
            c1.name,
            c1.parent_id,
            c2.name AS parent_name
        FROM
            categories AS c1
        LEFT JOIN
            categories AS c2 ON(c1.parent_id = c2.category_id)
        WHERE
            c1.category_id = ?
    ");

        $query->execute([$id]);

        return $query->fetch();
    }

    public function create($data) {
        $query = $this->db->prepare("
            INSERT INTO categories
            (name, parent_id)
            VALUES(?, ?)
        ");

        $query->execute([
            $data["name"],
            $data["parent_id"]
        ]);

        $data["category_id"] = $this->db->lastInsertId();

        return $data;
    }

    public function update($data) {

        $query = $this->db->prepare("
            UPDATE
                categories
            SET
                name = ?,
                parent_id = ?
            WHERE
                category_id = ?
        ");

        $query->execute([
            $data["name"],
            $data["parent_id"],
            $data["id"]
        ]);

        return $data;
    }

    public function delete($id) {

        $query = $this->db->prepare("
            DELETE FROM categories
            WHERE category_id = ?
        ");

        return $query->execute([ $id ]);
    }
}