<?php

abstract class ActionAbstract extends BaseAbstract
{
    protected $controller = null;
    protected  $actionRedirect = array();

    function __construct($controller)
    {
        parent::__construct();

        session_start();

        if (empty($_SESSION['user'])) {
            $this->user = array();
        } else {
            $this->user = $_SESSION['user'];
        }
        $this->controller = $controller;
        //$this->smarty->assign('controller', $controller);
        //$this->smarty->assign('this', $this);
    }

    public function isAjax()
    {
        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

	public function OutPut($data)
    {
        $out = array(
            'success' => true
        );
        if (is_array($data)) {
            $out['data'] = $data;
        } else {
            $out['success'] = false;
            $out['error'] = $data;
        }
        echo json_encode($out);
        exit;
    }

    function __call($key, $parm = array())
    {
        if (in_array($key, array_keys($this->actionRedirect))){
            $action = $this->actionRedirect[$key];
            $this->$action($parm);
            // call_user_func_array(array(get_class($this), $this->actionRedirect[$key]), $parm);
        }
    }

    function redirect($url = '')
    {
        redirect($url);
    }

}