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

define('ABSPATH', dirname(__FILE__) . '/' );

require(ABSPATH . '_libs/SiteSetting.class.php');

// Samrty
require("D:\\xampp\libs\Smarty3\libs\Smarty.class.php");


$smarty = new Smarty;
$smarty->template_dir = array(ABSPATH);
$smarty->compile_dir = ABSPATH . '_libs/templates_c/';
$smarty->addPluginsDir(array(ABSPATH . '_libs/plugins/'));

$setting = new SiteSetting;

// topへのパス
$smarty->assign("level", $setting->get_base_path());

$basePath = ABSPATH;
//指定パス以下のディレクトリ・ファイル取得
$fileList = getFileList($basePath);//file[] dir[] に格納

foreach ($fileList as $filePath) {
    $fileInfo = pathinfo($filePath);
    if( @$fileInfo['extension'] == "tpl" && !strstr($fileInfo['dirname'],'htparts') && $fileInfo['filename'] != "404" ){

        // Page Id = first directory name
        $pid = $setting->get_page_id(str_replace($basePath,"",$filePath));
        $smarty->assign("pid", $pid);

        // if level = relative_path
        // $smarty->assign("level", $setting->get_relative_path(str_replace($basePath,"",$filePath)));

        $html = $smarty->fetch($filePath);

        $outPutPath = $fileInfo['dirname'].'/'.$fileInfo['filename'].'.html';

        $oldData = "";
        if (file_exists("$outPutPath")){
            $fd = fopen ("$outPutPath", "r");
            while (!feof ($fd)) {
                   $oldData .= fgets($fd, 4096);
            }
            fclose ($fd);
        }
        $link = str_replace($basePath,"",$outPutPath).'?view';
        if($oldData == $html){
            print_r('<p>'.$outPutPath.'に変更はあません。<a href="'.$link.'">link</a></p>');
        }else{
            buildPutFile($outPutPath,$html);
            print_r('<p style="color:#c00;">'.$outPutPath.'を書き換えました。<a href="'.$link.'">link</a></p></p>');
        }

    }
}
// $target = gethtmlpath($fileList);


print_r("<p>".date("Y/m/d g:i s")."</p>\n");
echo "</body>\n";
echo "</html>\n";


function getFileList($directory,$depth = -1,$ignore = array(),$filter = array()) {
    if ($depth == 0){
        return array();
    }

    $directory = rtrim($directory,'\\/');

    $tmp = array();

    if (class_exists('FilesystemIterator',false)){
        $iterator = new RecursiveDirectoryiterator($directory,FilesystemIterator::SKIP_DOTS);
    }else{
        $iterator = new RecursiveDirectoryiterator($directory);
    }

    foreach ($iterator as $path){
        $filename = $path->getBasename();
        foreach ((array)$ignore as $ival){
            if (fnmatch($ival,$filename)){
                continue 2;
            }
        }

        if ($path->isDir()){
            if ($depth > 0){
                $depth--;
            }
            foreach (getFileList($directory.'/'.$filename,$depth,$ignore,$filter) as $ppath){
                $tmp[] = $ppath;
            }
        }else{
            if ($filter){
                $hit = false;
                foreach ((array)$filter as $fval){
                    if (fnmatch($fval,$filename)){
                        $hit = true;
                        break;
                    }
                }
                if (!$hit){
                    continue;
                }
            }
            $tmp[] = $directory.'/'.$filename;
        }
    }

    return $tmp;
}

function buildPutFile($filepath,$content,$hook_func = ''){
   buildMakeDir(dirname($filepath),$hook_func);

    if ($fp =buildFileOpen($filepath)){
        flock($fp,LOCK_EX);
        ftruncate($fp,0);
        fputs($fp,$content);
        flock($fp,LOCK_UN);
        fclose($fp);
        if ($hook_func){
            call_user_func($hook_func,'make_file',$filepath);
        }

        return true;
    }

    return false;
}

function buildMakeDir($filepath,$hook_func = ''){
    if (strpos($filepath,'\\') !== false){
        $filepath = str_replace('\\','/',$filepath);
    }
    $filepath = rtrim($filepath,'\\/').'/';
    if (!is_dir($filepath)){
        //再帰的に呼び出す　一つ上のパスがディレクトリで存在しているか確認
        if ($parent_path = dirname($filepath)){
           buildMakeDir($parent_path,$hook_func);
        }
        if (@mkdir($filepath)){
            if ($hook_func){
                call_user_func($hook_func,'make_dir',$filepath);
            }
            chmod($filepath,0755);
        }else{
            return false;
        }
    }
}

function buildFileOpen($filepath,$mode = 'w',$hook_func = ''){
   buildMakeDir(dirname($filepath),$hook_func);

    if (!is_dir($filepath)){
        return fopen($filepath,$mode);
    }

    return false;
}
