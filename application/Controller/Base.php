<?php


namespace Framework\Controller;


use Framework\Classes\User;
use Framework\System\EntryPoint;

class Base extends EntryPoint
{
    public $user;
    public function __construct()
    {
        parent::__construct();
        $this->load->classes(['User']);
        $this->user = new User();
    }

    public function base($id = NULL)
    {
        $user = $this->user->getUserById($id);
        $this->load->view('user', $user->as_array());
    }
}