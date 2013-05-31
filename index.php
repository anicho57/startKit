<?php

require_once('_setting/SiteSetting.class.php');

$setting = new SiteSetting;
$smarty = new MySmarty;

$smarty->assign("level", $setting->get_base_path());
//$smarty->assign("level", $setting->get_relative_path());

$smarty->assign("pid", $setting->get_page_id());

$smartyPath = $setting->get_smarty_path();
if ($smartyPath == "contacts/index"){
    include './includes/contact.php';
}else{
    if(file_exists($smarty->template_dir[0] . "$smartyPath.tpl")){
      $smarty->display("$smartyPath.tpl");
    }else if(file_exists($smarty->template_dir[0] . $smartyPath)){
      $smarty->display($smarty->template_dir[0] . $smartyPath);
    }else{
      header('HTTP/1.0 404 Not Found');
      $smarty->assign("pid", 'e404');
      $smarty->display("404.tpl");
      var_dump($smartyPath);
    }
}

// print_r($_SERVER);
// print_r($smartyPath);

