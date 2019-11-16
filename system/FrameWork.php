<?php

if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/constants.php'))
{
    require_once APPPATH.'config/'.ENVIRONMENT.'/constants.php';
}

if (file_exists(APPPATH.'config/constants.php'))
{
    require_once APPPATH.'config/constants.php';
}

require_once BASEPATH.'core.php';
require_once BASEPATH.'SmartyTpl.php';
require_once BASEPATH.'Model.php';

load_class('EntryPoint');
log_message(500, 'App started');
//DB::config('localhost', 'test', 'root', 'Ghbdtngh1', 'mysql');
ORM::configure('mysql:host=localhost;dbname=test');
ORM::configure('username', 'root');
ORM::configure('password', 'Ghbdtngh1');

require BASEPATH.'routes.php';

