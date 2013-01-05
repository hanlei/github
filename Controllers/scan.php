<?php

class Action extends ActionAbstract
{
    function index()
    {
		$remote = getDir(ROOT_PATH);
		$local = $remote;
		$local['hanlei']='hanlei';

		$diff = diff_files($local,$remote);
		print_r($diff);
		
		$file = '/Users/hanlei/Desktop/a9d3fd1f4134970a93aa37b795cad1c8a6865d48.jpg'
    }

}