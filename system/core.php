<?php

function &load_class($class, $directory = 'system', $param = NULL, $namespaces = NAMESPACES)
{
    static $_classes = array();

    // Does the class exist? If so, we're done...
    if (isset($_classes[$class]))
    {
        return $_classes[$class];
    }

    $name = FALSE;

    // Look for the class first in the local application/libraries folder
    // then in the native system/libraries folder
    foreach (array(APPPATH, FCPATH) as $path)
    {

        if (file_exists($path.$directory.DIRECTORY_SEPARATOR.$class.'.php'))
        {

            $name = $class;

            if (class_exists($name, FALSE) === FALSE)
            {
                require_once $path.$directory.DIRECTORY_SEPARATOR.$class.'.php';


                //require_once('E:\work\oserver\OSPanel\domains\frame.work\system\Log.php');

            }

            break;
        }
    }
    // Is the request a class extension? If so we load it too
    if (file_exists(APPPATH.$directory.DIRECTORY_SEPARATOR.config_item('subclass_prefix').$class.'.php'))
    {
        $name = config_item('subclass_prefix').$class;

        if (class_exists($name, FALSE) === FALSE)
        {
            require_once APPPATH.$directory.DIRECTORY_SEPARATOR.$name.'.php';
        }
    }

    // Did we find the class?
    if ($name === FALSE)
    {
        // Note: We use exit() rather than show_error() in order to avoid a
        // self-referencing loop with the Exceptions class
        set_status_header(503);
        echo 'Unable to locate the specified class: '.$class.'.php';
        exit(5); // EXIT_UNK_CLASS
    } else {
        $name = $namespaces.$name;
    }

    // Keep track of what we just loaded
    is_loaded($class);

    $_classes[$class] = isset($param)
        ? new $name($param)
        : new $name();
    return $_classes[$class];

}

function &is_loaded($class = '')
{
    static $_is_loaded = array();

    if ($class !== '')
    {
        $_is_loaded[strtolower($class)] = $class;
    }

    return $_is_loaded;
}

function log_message($level, $message, $return=FALSE)
{
    static $_log;

    if ($_log === NULL)
    {
        // references cannot be directly assigned to static variables, so we use an array
        $_log[0] =& load_class('Log', 'system');
    }

    $_log[0]->write_log($level, $message, $return);
}

function &get_instance()
{
    return \Framework\System\EntryPoint::get_instance();
}


function &get_config(Array $replace = array())
{
    static $config;

    if (empty($config))
    {
        $file_path = APPPATH.'config/config.php';
        $found = FALSE;
        if (file_exists($file_path))
        {
            $found = TRUE;
            require($file_path);
        }

        // Is the config file in the environment folder?
        if (file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
        {
            require($file_path);
        }
        elseif ( ! $found)
        {
            set_status_header(503);
            echo 'The configuration file does not exist.';
            exit(3); // EXIT_CONFIG
        }

        // Does the $config array exist in the file?
        if ( ! isset($config) OR ! is_array($config))
        {
            set_status_header(503);
            echo 'Your config file does not appear to be formatted correctly.';
            exit(3); // EXIT_CONFIG
        }
    }

    // Are any values being dynamically added or replaced?
    foreach ($replace as $key => $val)
    {
        $config[$key] = $val;
    }

    return $config;
}

function config_item($item)
{
    static $_config;

    if (empty($_config))
    {
        // references cannot be directly assigned to static variables, so we use an array
        $_config[0] =& get_config();
    }

    return isset($_config[0][$item]) ? $_config[0][$item] : NULL;
}


function set_status_header($code = 200, $text = '')
{

    if (empty($code) OR ! is_numeric($code))
    {
        show_error(500, 'Status codes must be numeric');
    }

    if (empty($text))
    {
        is_int($code) OR $code = (int) $code;
        $stati = array(
            100	=> 'Continue',
            101	=> 'Switching Protocols',
            103	=> 'Early Hints',

            200	=> 'OK',
            201	=> 'Created',
            202	=> 'Accepted',
            203	=> 'Non-Authoritative Information',
            204	=> 'No Content',
            205	=> 'Reset Content',
            206	=> 'Partial Content',
            207	=> 'Multi-Status',

            300	=> 'Multiple Choices',
            301	=> 'Moved Permanently',
            302	=> 'Found',
            303	=> 'See Other',
            304	=> 'Not Modified',
            305	=> 'Use Proxy',
            307	=> 'Temporary Redirect',
            308	=> 'Permanent Redirect',

            400	=> 'Bad Request',
            401	=> 'Unauthorized',
            402	=> 'Payment Required',
            403	=> 'Forbidden',
            404	=> 'Not Found',
            405	=> 'Method Not Allowed',
            406	=> 'Not Acceptable',
            407	=> 'Proxy Authentication Required',
            408	=> 'Request Timeout',
            409	=> 'Conflict',
            410	=> 'Gone',
            411	=> 'Length Required',
            412	=> 'Precondition Failed',
            413	=> 'Request Entity Too Large',
            414	=> 'Request-URI Too Long',
            415	=> 'Unsupported Media Type',
            416	=> 'Requested Range Not Satisfiable',
            417	=> 'Expectation Failed',
            421	=> 'Misdirected Request',
            422	=> 'Unprocessable Entity',
            426	=> 'Upgrade Required',
            428	=> 'Precondition Required',
            429	=> 'Too Many Requests',
            431	=> 'Request Header Fields Too Large',
            451	=> 'Unavailable For Legal Reasons',

            500	=> 'Internal Server Error',
            501	=> 'Not Implemented',
            502	=> 'Bad Gateway',
            503	=> 'Service Unavailable',
            504	=> 'Gateway Timeout',
            505	=> 'HTTP Version Not Supported',
            511	=> 'Network Authentication Required',
        );

        if (isset($stati[$code]))
        {
            $text = $stati[$code];
        }
        else
        {
            log_message(500, 'No status text available. Please check your status code number or supply your own message text.');
        }
    }

    if (strpos(PHP_SAPI, 'cgi') === 0)
    {
        header('Status: '.$code.' '.$text, TRUE);
        return;
    }

    $server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
        ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
    header($server_protocol.' '.$code.' '.$text, TRUE, $code);
}