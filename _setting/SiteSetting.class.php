<?php

define('HTDOCS', 'D:\xampp\htdocs\startkit/');
// Smarty
define('SMARTY_DIR', 'D:\xampp\libs\Smarty3\libs/');
define('SMARTY_MYDIR', HTDOCS . '_setting/');


require_once('MySmarty.class.php');


class SiteSetting{

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
