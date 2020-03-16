<?php


namespace Framework\System\Interfaces;


interface Entity
{
    public function findOne(int $id);

    public function findAll(int $limit, int $offset);
    
    public function save();

    public function delete(int $id);


}