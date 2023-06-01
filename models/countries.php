<?php
require_once("base.php");

class Countries extends Base
{
    public function get() {

        $query = $this->db->prepare("
            SELECT code, country
            FROM countries
        ");

        $query->execute();

        return $query->fetchAll( PDO::FETCH_ASSOC );
    }
}
