<?php


namespace Framework\System;


class Loader
{
    public function __construct()
    {

    }

    public function view($file, $data)
    {
        $smarty = new SmartyTpl();
        $data = (Array) $data;
        if(count($data) > 0) {
            foreach($data as $key => $value) {
                $smarty->assign($key, $value);
            }
        }
        $smarty->display($file.'.tpl');

    }


    public function initialize()
    {
        $this->__autoloader();
    }

    protected function __autoloader()
    {
        if (file_exists(APPPATH.'config/autoload.php'))
        {
            include(APPPATH.'config/autoload.php');
        }

        if (file_exists(APPPATH.'config/'.ENVIRONMENT.'/autoload.php'))
        {
            include(APPPATH.'config/'.ENVIRONMENT.'/autoload.php');
        }

        if ( ! isset($autoload))
        {
            return;
        }

        if (isset($autoload['classes']) && count($autoload['classes']) > 0)
        {
            foreach($autoload['classes'] as $class) {
                load_class($class, 'Classes', NULL, 'Framework\Classes\\');
            }

        }

        // Autoload models
        if (isset($autoload['model']))
        {
            $this->model($autoload['model']);
        }
    }

    public function classes($classNames = array())
    {
        foreach ($classNames as $class) {
            load_class($class, 'Classes', NULL, 'Framework\Classes\\');
        }
    }
}