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

load_class('EntryPoint');

require BASEPATH.'routes.php';

