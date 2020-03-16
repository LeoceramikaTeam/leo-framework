<?php


namespace Framework\Controller;

use Framework\System\EntryPoint;

/**
 * Class Base
 * @package Framework\Controller
 * @EntryPoint
 */

class Base extends EntryPoint
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @RouterAnno(methods={"GET","POST"}, path="/base", realPath="Base/base", params={"id":"integer","name":"string"})
     */
    public function base($params)
    {
        $id = $params['id'];
        $name = $params['name'];

        $data = [];
        $data['id'] = $id;
        $data['name'] = $name;
        $this->load->view('ololo', $data, 0);
    }

    /**
     * @RouterAnno(path="/basesuas", realPath="Base/base2", params={"id":"integer"})
     */
    public function base2($params)
    {
        $id = $params['id'];
        $this->load->view('ololo2', ['id'=>$id]);
    }

    /**
     * @RouterAnno(path="/ololowe4ka", realPath="Base/base22")
     */
    public function base22($params)
    {
        $id = $params['id'];
        $this->load->view('ololo3', ['id'=>$id]);
    }
}