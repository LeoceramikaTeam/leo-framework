<?php


namespace Framework\Controller;


use Framework\Classes\User;
use Framework\System\EntryPoint;

class Home extends EntryPoint
{
    public function __construct()
    {

        parent::__construct();
        $this->get_class('User');
    }

    public function base()
    {
        $this->load->view('home');

    }
}