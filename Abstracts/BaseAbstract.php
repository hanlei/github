<?php

abstract class BaseAbstract
{
    function __get($name)
    {
        return Component::get($name);
    }

    function __construct()
    {
        //todo 添加smarty
		//Component::init();
    }
}
