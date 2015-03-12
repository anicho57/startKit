<?php
class SiteSetting{

  /**
   * インストールディレクトリの階層を取得
   * @return [string] インストールディレクトリの階層
   */
  function get_base_path(){
    $fileInfo = pathinfo($_SERVER['PHP_SELF']);
    return ($fileInfo['dirname'] == '/') ? '/' : $fileInfo['dirname'].'/';
  }

  /**
   * インストールディレクトリ以降のパスを取得
   * @return [string] インストールディレクトリ以降のパス
   */
  function get_path(){
    $reqUri = $_SERVER['REQUEST_URI'];
    if ($_GET){
      $reqUri = explode('?', $reqUri);
      $reqUri = reset($reqUri);
    }
    $basePath = $this->get_base_path();
    $path = substr($reqUri, strlen($basePath), strlen($reqUri));
    return ($path) ? $path : '' ;
  }

  /**
   * インストールディレクトリへの相対パスを取得
   * @return [string] トップディレクトリへの相対パス
   */
  function get_relative_path(){
    $path = $this->get_path();
    $kaisou = substr_count( $path, '/' );
    return ($kaisou == 0) ? './' : str_repeat('../',$kaisou);
  }

  /**
   * 文字列から最初のディレクトリ名（/で囲まれた名前）を取得
   * @return [string] 最初のディレクトリ名。トップ階層は'home'を返す
   */
  function get_page_id(){
    $path = $this->get_path();
    $first_dir = 'home';
    if( preg_match('/[^\/]+/', $path, $m)){
      $first_dir = $m[0];
    }
    return $first_dir;
  }

  /**
   * 現在のURLから一つ上位のディレクトリ名を取得
   * @return [type] [description]
   */
  function get_current_dir(){
    $path = $this->get_path();
    $last_dir = '';
    if( preg_match('|(?<=/)(?=.*/)(?!.*/.*/)[^/]+|', '/' . $path, $m)){
      $last_dir = $m[0];
    }
    return $last_dir;
  }

  function get_smarty_path(){
    $path = $this->get_path();

    // html拡張子の場合は取り除く
    if (strstr($path,'.html')){
        return str_replace('.html','',$path);
    }else if (strstr($path,'.') == false){
        return $path . 'index';
    }else{
        return $path;
    }
  }

  function get_html_path(){
    $path = $this->get_path();
    return (strstr($path,'.') == false) ? $path . 'index.html' :  $path;
  }

}
