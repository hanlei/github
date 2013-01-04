<?php

/*
* 单例基类
*
* @copyright (c) 2012 Atom Projects More info http://Atom.com
* @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
* @author xuanyan <yxuan@myspace.cn>
*
*/


class Singleton
{
    protected static $instance = array();

    public static function getInstance()
    {
        $class = get_called_class();
        if (!isset(self::$instance[$class])) {
            self::$instance[$class] = new static();
        }

        return self::$instance[$class];
        // if (!static::$instance instanceof static) {
        //     
        //     echo get_called_class();
        //     echo '<br>';
        //     static::$instance = new static();
        // }
        // 
        // return static::$instance;
    }
}