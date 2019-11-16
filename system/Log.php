<?php


namespace Framework\System;


use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Handler\FirePHPHandler;
use Monolog\Formatter\LineFormatter;

class Log extends Logger
{
    private $formatter;

    public function __construct()
    {
        parent::__construct('Logger');
        $dateFormat = "H:i j/n/Y";
        $output = "%level_name% >> %datetime% > %message% %context% %extra%\n";
        $this->formatter = new LineFormatter($output, $dateFormat);

    }

    public function write_log($level, $message, $data)
    {
        $stream = new StreamHandler(APPPATH.config_item('log_folder').'/my_app.log', $level);
        $stream->setFormatter($this->formatter);
        $stream->setLevel($level);
        $this->pushHandler($stream);
        $this->pushHandler(new FirePHPHandler());
        $this->setTimezone(new \DateTimeZone( '+0200' ));
        $this->info($message);
    }
}