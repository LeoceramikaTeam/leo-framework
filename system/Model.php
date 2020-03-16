<?php

namespace Framework\System;
require_once BASEPATH.'Interfaces'.DIRECTORY_SEPARATOR.'Entity.php';

use Framework\System\Interfaces\Entity;



class Model implements Entity
{
    private $table;

    private $primary_key;

    public function __construct($table)
    {
        $this->table = $table;

    }

    public function findOne(int $id)
    {
        return \ORM::for_table($this->table)->find_one($id);
    }

    public function findAll(int $limit = 0, int $offset = 0)
    {
        // TODO: Implement findAll() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function delete(int $id)
    {
        // TODO: Implement delete() method.
    }

    private function findPrimaryKey ()
    {
        return 'id';
    }
}