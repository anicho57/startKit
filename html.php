<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta http-equiv="content-script-type" content="text/javascript" />

        <title>Smarty→HTMLファイルの作成</title>

</head>

<body>

<?php

/*** html作成 ***/
/*
 * 指定ディレクトリ以下にhtmlファイルがあった場合に
 * htmlファイルと同じディレクトリ構造のSmartyテンプレートの内容を
 * 比較し違っていたらファイルを作成し出力する。
 *
 * 条件：htmlと同じディレクトリ構成のSmarty、templatesディレクト、ファイル名
 *
 * */


$path = "./";

//指定パス以下のディレクトリ・ファイル取得
$list = getdirlist( $path );//file[] dir[] に格納
$target = gethtmlpath($list);

print_r("<p>".date("Y/m/d g:i s")."</p>\n");

echo "</body>\n";
echo "</html>\n";

function gethtmlpath($array,$level=NULL){

  foreach( $array as $key => $arrs ){
    if ($key == 'file'){
      foreach ($arrs as $value){
        if( end(explode('.', $value)) == "tpl" && !strstr($level,'htparts') && $value != "404.tpl" )/*htpartsのテキストが含まれるファイルは除く*/
          dwrite($level. substr($value, 0, strrpos($value, '.')));
      }
    }
    if($key == 'dir'){
      foreach( $arrs as $k => $arr){
        $dirname = $level.$k."/";
        gethtmlpath($arr,$dirname);
      }
    }
  }

}


function dwrite($target){


        require_once('_libs/SiteSetting.class.php');
        $smarty = new MySmarty;

        $kazu = substr_count( $target, "/" );
        $level = "";
        if ($kazu == 0){
                $level = "./";
        }else{
                for ($i = 0; $i <= $kazu-1; $i++) {
                    $level .= "../";
                }
        }
        //print_r($level);
        $smarty->assign("level", $level);

        $basePath = str_replace("html.php","",$_SERVER['PHP_SELF']);
        $path = str_replace($basePath,"",$target);

        $pid = reset(explode('/', $path));
        if( $pid == "index" ) $pid = "home";
        $smarty->assign("pid", $pid);

        $html = $smarty->fetch("$target.tpl");


        $filepath = "$target.html";
        $data = "";
        if (file_exists("$filepath")){
           $fd = fopen ("$filepath", "r");
           while (!feof ($fd)) {
              $data .= fgets($fd, 4096);
           }
           fclose ($fd);
        }else{
                //ディレクトリパスを取得
                $dir = substr($filepath, 0, mb_strrpos($filepath,'/'));//最後の"/"位置までで
//              var_dump($dir);
                if(!file_exists($dir) && $dir !== ""){

                        if(mkdir($dir , 0707,true)){
                          chmod($dir, 0707);//umask 0002のためパーミション再設定
                        }else{
                          print_r("<p style='color:#c00;'>$dir ディレクトリ作成失敗</p>");
                        }
                }

                touch($filepath);
                chmod( $filepath, 0646 );
        }

        if($data == $html){
                print_r("<p>./$target.htmlに変更はあません。</p>");
        }else{
                $fp = fopen($filepath, "w");
                @fwrite( $fp, $html, strlen($html) );
                fclose($fp);
                print_r("<p style='color:#c00;'>./$target.htmlを書き換えました。</p>");
        }

}return true;


function getdirlist($dirpath='' , $flag = true ){

 if ( strcmp($dirpath,'')==0 ) die('dir name is undefind.');

  $file_list = array();
  $dir_list = array();

  if( ($dir = @opendir($dirpath) ) == FALSE ) {
   die( "dir {$dirpath} not found.");
  }

  while ( ($file=readdir( $dir )) !== FALSE ){
   if ( is_dir( "$dirpath/$file" ) ){
    if( strpos( $file ,'.' ) !== 0 ){
     $dir_list["$file"] = getdirlist( "$dirpath/$file" , $flag );
    }
   }else {
    if( $flag ){
     array_push($file_list, $file);
    }else{
     if( strpos( $file , '.' )!==0 ) array_push( $file_list , $file);
    }
   }
  }
 return array( "file"=>$file_list , "dir"=>$dir_list);
}
