<?php

/*
* 框架环境初始化
*
* @copyright (c) 2012 Atom Projects More info http://Atom.com
* @license http://opensource.org/licenses/gpl-2.0.php The GNU General Public License
* @author xuanyan <yxuan@myspace.cn>
*
*/

defined('ROOT_PATH') || define('ROOT_PATH', getcwd());
define('LIB_PATH', dirname(__FILE__));

include_once LIB_PATH . '/Helper.php';

$loader = include_once LIB_PATH . '/vendor/autoload.php';

// if cant get date.timezone set the default timezone
if (!ini_get('date.timezone')) {
    date_default_timezone_set('Asia/Chongqing');
}

// handle errors
// register_shutdown_function(function() {
//     if ($error = error_get_last()) {
//         if (strpos($error['file'], 'Smarty') === false) {
//             throw new ErrorException($error['message'], 0, $error['type'], $error['file'], $error['line']);
//         }
//     }
// });

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    if (stripos($errfile, 'Smarty') === false && stripos($errstr, 'file_get_contents') === false) {
		if (stripos($errfile, 'compile') === false) {
	        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
		}
    }
});


// regist autoload
spl_autoload_register(function($classname){
    if (strpos($classname, 'Abstract')) {
        $file = ROOT_PATH . '/Abstracts/' . $classname . '.php';
    } elseif (strpos($classname, 'Model')) {
        $file = ROOT_PATH . '/Models/' . $classname . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {// 构造不存在的model对象
            eval('class ' . $classname . ' extends Model {}');
        }
        return;
    } else {
        $file = LIB_PATH . '/' . $classname . '.php';
    }

    if (file_exists($file)) {
        require_once $file;
    }
});

if (PHP_SAPI == 'cli') {
    return $loader;
}

if (!defined('HTTPS')) {
    if (isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'], 'on')) {
        define('HTTPS', 1);
    } else {
        define('HTTPS', 0);
    }
}

if (!isset($_SERVER['HTTP_HOST'])) {
    $_SERVER['HTTP_HOST'] = $_SERVER['SERVER_NAME'];
}

if (strpos($_SERVER['HTTP_HOST'], ':')) {
    $_SERVER['HTTP_HOST'] = strtok($_SERVER['HTTP_HOST'], ':');
}

if (!defined('SITE_URL')) {
    $site_url = HTTPS ? 'https://' : 'http://';
    $site_url .= $_SERVER['HTTP_HOST'];

    // echo $site_url;exit;
    if (isset($_SERVER['SERVER_PORT'])) {
        if ((HTTPS && $_SERVER['SERVER_PORT'] != 443) || ($_SERVER['SERVER_PORT'] != 80)) {
            $site_url .= ':' . $_SERVER['SERVER_PORT'];
        }
    }

  // auto check subdir
  if (isset($_SERVER['PHP_SELF']) && strpos($_SERVER['PHP_SELF'], 'index.php')) {
      $site_url .= dirname($_SERVER['PHP_SELF']);
  } elseif (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
      $site_url .= dirname(substr($_SERVER['SCRIPT_FILENAME'], strlen($_SERVER['DOCUMENT_ROOT'])));
  }

  define('SITE_URL', rtrim($site_url, '/'));
}

// fix SCRIPT_URL
if (empty($_SERVER['SCRIPT_URL'])) {
    if (!empty($_SERVER['REDIRECT_URL'])) {
        $_SERVER['SCRIPT_URL'] = $_SERVER['REDIRECT_URL'];
    } elseif (!empty($_SERVER['REQUEST_URI'])) {
        $p = parse_url($_SERVER['REQUEST_URI']);
        $_SERVER['SCRIPT_URL'] = $p['path'];
    }
}
// it seems as 'php bug'
// if (strpos($_SERVER['REQUEST_URI'], '?') !== false && empty($_GET)) {
//     $p = parse_url($_SERVER['REQUEST_URI']);
//     parse_str($p['query'], $_GET);
// }

// if magic_quotes_sybase is ON then do this:
if (get_magic_quotes_gpc()) {
    $_GET    = stripslashes_recursive($_GET);
    $_POST   = stripslashes_recursive($_POST);
    $_COOKIE = stripslashes_recursive($_COOKIE);
}

return $loader;