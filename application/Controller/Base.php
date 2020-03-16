<?php


namespace Framework\Controller;


use Framework\System\EntryPoint;

class Base extends EntryPoint
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @RouterAnno(path="/base", realPath="Base/base", params={"id":"integer","name":"string"})
     */
    public function base($id = NULL)
    {
        $this->load->view('ololo', ['id'=>$id]);
    }
}