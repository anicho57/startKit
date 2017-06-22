<?php
namespace anicho\startkit;

define('DS', DIRECTORY_SEPARATOR);
define('ABSPATH', dirname(__FILE__));
define('SRCPATH', realpath(ABSPATH . '/.src' ));
define('DOCPATH', realpath(ABSPATH));

require(SRCPATH . '/_libs/SiteSetting.class.php');

use \SiteSetting;

// Samrty
require("C:\\xampp\libs\Smarty3\libs\Smarty.class.php");
// require("/usr/share/php/Smarty3/libs/Smarty.class.php");
use \Smarty;

$smarty = new Smarty;
$smarty->template_dir = array(DOCPATH);
$smarty->compile_dir = SRCPATH . '/_libs/templates_c/';
$smarty->addPluginsDir(array(SRCPATH . '/_libs/plugins/'));

$setting = new SiteSetting;

// topへのパス
$smarty->assign("level", $setting->get_base_path());
// $smarty->assign("level", $setting->get_relative_path());

// Page Id = first directory name
$smarty->assign("pid", $setting->get_page_id());

$smartyPath = $setting->get_smarty_path();
if ($smartyPath == "contacts/index"){
    include './includes/contact.php';
}else{
    // tpl -> html -> 404
    if(file_exists($smarty->template_dir[0] . "$smartyPath.html") && !strstr($smartyPath,'_inc')){
      $ext = 'html';
      $smarty->display("$smartyPath.$ext");
    }else{
      header('HTTP/1.0 404 Not Found');
      $smarty->assign("pid", 'e404');
      $smarty->display("404.html");
      var_dump($smartyPath);
    }
}

// print_r($_SERVER);
// print_r($smartyPath);
