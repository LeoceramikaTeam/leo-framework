<?php


namespace Framework\System;


class EntryPoint
{
    private static $instance;

    public function __construct()
    {
        self::$instance =& $this;

        $this->load =& load_class('Loader');
        $this->load->initialize();
        foreach (is_loaded() as $var => $class)
        {
            if($class == 'EntryPoint') continue;
            if($class == 'Loader') continue;
            $this->$var =& load_class($class);
        }
        log_message(500, 'Controller Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * @return EntryPoint
     */
    public static function &get_instance()
    {
        return self::$instance;
    }

    public function get_class($className)
    {
        $class = strtolower($className);
        $this->$class = load_class($className, 'application/Classes', '', 'Framework\\Classes\\');
    }
}