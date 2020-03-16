<?php


namespace Framework\Classes;



use Framework\System\Model;

class User extends Model
{
    public static $ololo = 'user';

    public function __construct()
    {
        parent::__construct('users');
    }

    public function getUserById(int $id)
    {
        return $this->findOne($id);
    }
}