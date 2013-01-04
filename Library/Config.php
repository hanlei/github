<?php

/*
* Config 服务化临时类
*
* @copyright (c) 2012 Atom Projects More info http://Atom.com
* @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
* @author xuanyan <yxuan@myspace.cn>
*
*/

class Config extends Singleton
{
    private static $apiUrl = '';
    private $_config = array();

    function __construct()
    {
        if (strpos($_SERVER['HTTP_HOST'], 'atom.do') !== false) {
            self::$apiUrl = 'http://ah-atom01.atomuni.com:10000/index.php?token=%s';
        } else {
            self::$apiUrl = 'http://192.168.1.200:10000/index.php?token=%s';
        }

        if (file_exists(ROOT_PATH . '/Config.php' )) {
            $this->_config = require_once ROOT_PATH . '/Config.php';
            return ;
        }
        $file = ROOT_PATH . '/tmp/' . $_SERVER['HTTP_HOST'] . '.php';
        if (!file_exists($file) || (time() - filemtime($file)) > 3600 ) {
            $token = Cipher::encryptForUrl($_SERVER['HTTP_HOST']);
            //echo sprintf(self::$apiUrl, $token);exit;
            $this->_config = json_decode(Cipher::decrypt(file_get_contents(sprintf(self::$apiUrl, $token))), true);
            if (empty($this->_config)) {
                throw new Exception("cant load config");
            }
            if (!file_exists(ROOT_PATH . '/tmp')) {
                mkdir(ROOT_PATH . '/tmp');
            }
            file_put_contents($file, "<?php\n return " . var_export($this->_config, true) . ';');
        } else {
            //echo file_get_contents($file);exit;
            $this->_config = require_once $file;
        }

        
        //print_r($this->_config);exit;
        // @todo 本地缓存
    }
    /**
     * 获取config
     *
     * @param string config key 值 
     * @return array
     * @author xuanyan
     */
    public function get($type)
    {
        return isset($this->_config[$type]) ? $this->_config[$type] : array();
    }
}