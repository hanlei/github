<?php

/*
* Component 引入类
*
* @copyright (c) 2012 Atom Projects More info http://Atom.com
* @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
* @author xuanyan <yxuan@myspace.cn>
*
*/

class Component
{
    private static $config = array();
    private static $classMap = array();

    public static function init($config)
    {
        self::$config = $config;
    }

    public static function get($name)
    {
        if (!isset(self::$classMap[$name])) {
            if (!isset(self::$config[$name])) {
                throw new Exception("cant load ".$name);
            }
            $config = self::$config[$name];
            if (!isset($config['__init__'])) {
                throw new Exception("must set __init__ attr");
            }
            $initArray = $config['__init__'];

            if (isset($initArray['autoload'])) {
                require_once $initArray['autoload'];
            }
            $initArray = $initArray['class'];
            if (is_array($initArray)) {
                $class = array_shift($initArray);
                if (stripos($class, '::')) {
                    list($class, $method) = explode('::', $class);
                    self::$classMap[$name] = call_user_func_array(array($class, $method), $initArray);
                } else {
                    self::$classMap[$name] = call_user_func_array(array(new ReflectionClass($class), 'newInstance'), $initArray);
                }
            } else {
                self::$classMap[$name] = new $initArray();
            }
            unset($config['__init__']);
            foreach ($config as $key => $val) {
                if (is_numeric($key) && is_array($val)) {
                    $fun = array_shift($val);
                    call_user_func_array(array(self::$classMap[$name], $fun), $val);
                } else {
                    self::$classMap[$name]->$key = $val;
                }
            }
        }

        return self::$classMap[$name];
    }
}


?>