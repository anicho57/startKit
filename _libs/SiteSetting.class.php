<?php
class SiteSetting{

  function get_relative_path(){
    $kaisou = substr_count( $_SERVER['REQUEST_URI'], "/" ) - substr_count( $_SERVER['PHP_SELF'], "/" );
    if ($kaisou == 0){
      $topPath = "./";
    }else{
      $topPath = str_repeat('../',$kaisou);
    }
    return $topPath;
  }

  function get_base_path(){
    return str_replace("index.php","",$_SERVER['PHP_SELF']);
  }

  function get_path(){
    $reqUri = $_SERVER['REQUEST_URI'];
    if ($_GET){
      $reqUri = explode('?', $reqUri);
      $reqUri = reset($reqUri);
    }
    $basePath = $this->get_base_path();
    return str_replace($basePath,"",$reqUri);
  }

  function get_page_id(){
    $path = $this->get_path();
    $path = explode('/',$path);
    $pid = reset($path);
    if( $pid == "" || strstr($pid, '.') == '.html' ){
      return "home";
    }else{
      return $pid;
    }
  }

  function get_current_dir(){
    $path = $this->get_path();
    $buff = explode('/', $path);
    array_pop($buff);
    $dir = end($buff);
    if( $dir == "" ){
      return "";
    }else{
      return $dir;
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

  function get_html_path(){
    $path = $this->get_path();
    if (strstr($path,'.') == false){
        return $path . "index.html";
    }else{
        return $path;
    }
  }

}
