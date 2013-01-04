<?php

class Action extends ActionAbstract
{
    function index()
    {
		//$url, $params = array(), $method = 'GET', $file = false, $opt = array()
		$url = 'https://github.com/login/oauth/authorize';
		$params = array('client_id' => 'cdaf3997df7900e348ae',
						'redirect_uri'=>'http://github.ap01.aws.af.cm/?url=callback');
		$return = Http::request($url, $params, 'GET');
		echo $return;
		echo 'okkkkk';
		//http://github.ap01.aws.af.cm/?url=index/index&client_id=cdaf3997df7900e348ae
		//http://github.ap01.aws.af.cm/?code=138d9c69dc08ea494a2d&url=callback
//https://github.com/login?return_to=%2Flogin%2Foauth%2Fauthorize%3Fclient_id%3Dcdaf3997df7900e348ae%26redirect_uri%3Dhttp%253A%252F%252Fgithub.ap01.aws.af.cm%252F%253Furl%253Dcallback
    }

}