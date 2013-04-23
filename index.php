<?php

require_once('smarty_dir/MySmarty.class.php');

$smarty = new MySmarty;
$myFunc = new MyFunction;

$smarty->assign("level", $myFunc->get_base_path());
//$smarty->assign("level", $myFunc->get_relative_path());

$smarty->assign("pid", $myFunc->get_page_id());

$smartyPath = $myFunc->get_smarty_path();
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


class myFunction{

  function get_relative_path(){
    $level = "";
    $kaisou = substr_count( $_SERVER['REQUEST_URI'], "/" ) - substr_count( $_SERVER['PHP_SELF'], "/" );
    if ($kaisou == 0){
      $level = "./";
    }else{
      for ($i = 0; $i < $kaisou; $i++) {
          $level .= "../";
      }
    }
    return $level;
  }

  function get_base_path(){
    return str_replace("index.php","",$_SERVER['PHP_SELF']);
  }

  function get_path(){
    $reqUri = $_SERVER['REQUEST_URI'];
    $basePath = $this->get_base_path();
    return str_replace($basePath,"",$reqUri);
  }

  function get_page_id(){
    $path = $this->get_path();
    $pid = reset(explode('/', $path));
    if( $pid == "" ){
      return "home";
    }else{
      return $pid;
    }
  }

  function get_smarty_path(){
    $path = $this->get_path();

    if (strstr($path,'.html') == true){
        return str_replace('.html',"",$path);
    }else if (strstr($path,'.') == false){
        return $path . "index";
    }else{
        return $path;
    }
  }
}