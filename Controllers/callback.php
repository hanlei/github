<?php

class Action extends ActionAbstract
{
    function index()
    {
		echo 'oks';
		//https://github.com/login/oauth/authorize?client_id=cdaf3997df7900e348ae
		//$url, $params = array(), $method = 'GET', $file = false, $opt = array()
		$url = 'https://github.com/login/oauth/access_token';
		$params = array('client_id'=>'cdaf3997df7900e348ae','client_secret'=>'65de549e38f267bf162c4878705e97a7c218c0f1',
					'code'=>_GET('code'),
					'redirect_uri'=>'http://github.ap01.aws.af.cm/?url=callback/user'
					);
		$return = Http::request($url, $params, 'POST');
		echo $return;
		$data = json_decode($return,true);
		print_r($data);
		//http://github.ap01.aws.af.cm/?code=138d9c69dc08ea494a2d&url=callback
		
    }

}