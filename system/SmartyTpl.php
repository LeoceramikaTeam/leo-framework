<?php


namespace Framework\System;


class SmartyTpl extends \Smarty
{
    function __construct()
    {

        // Class Constructor.
        // These automatically get set with each new instance.

        parent::__construct();

        $this->setTemplateDir(VIEWPATH);
        $this->setCompileDir(APPPATH.'templates_c');
        $this->setConfigDir(APPPATH.'config/');
        $this->setCacheDir(FCPATH.'cache/');

        $this->caching = \Smarty::CACHING_LIFETIME_CURRENT;
        $this->assign('app_name', 'FrameWork');
    }

}