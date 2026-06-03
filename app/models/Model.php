<?php
// base model - gives all models access to the db

abstract class Model {

    protected PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
}
