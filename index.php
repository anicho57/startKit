<?php
namespace anicho\startkit;

define('ABSPATH', dirname(__FILE__) . '/' );

require(ABSPATH . '_libs/SiteSetting.class.php');
use \SiteSetting;

// Samrty
require("D:\\xampp\libs\Smarty3\libs\Smarty.class.php");
use \Smarty;

$smarty = new Smarty;
$smarty->template_dir = array(ABSPATH);
$smarty->compile_dir = ABSPATH . '_libs/templates_c/';
$smarty->addPluginsDir(array(ABSPATH . '_libs/plugins/'));

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
    if(file_exists($smarty->template_dir[0] . "$smartyPath.tpl") && !strstr($smartyPath,'_htparts')){
      $smarty->display("$smartyPath.tpl");
    }else if(file_exists($smarty->template_dir[0] . $setting->get_html_path()) && end(explode('.', $setting->get_html_path())) == "html"){ // html output
      echo file_get_contents($smarty->template_dir[0] . $setting->get_html_path());
    }else{
      header('HTTP/1.0 404 Not Found');
      $smarty->assign("pid", 'e404');
      $smarty->display("404.tpl");
      var_dump($smartyPath);
    }
}

// print_r($_SERVER);
// print_r($smartyPath);

