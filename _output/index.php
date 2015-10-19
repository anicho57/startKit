<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8" />
    <title>公開用データ出力</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
</head>
<body>
<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
  <div class="container">
    <div class="navbar-header">
      <a href="./" class="navbar-brand">公開用データ出力</a>
    </div>
  </div>
</header>
<div id="content">
    <div class="container">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <a class="collapsed btn-block" role="button" data-toggle="collapse" data-parent="#accordion" href="#pathsetting" aria-expanded="false" aria-controls="pathsetting">
                        設定
                    </a>
                </div>
                <div id="pathsetting" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">
                        <label class="radio-inline">
                            <input type="radio" name="path" checked="checked" value="relative"> 相対パス（../形式）
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="path" value="absolute"> 絶対パス
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-body">
                <p>
                    <a class="btn btn-primary" href="?check"><span class="glyphicon glyphicon-list"></span> ファイル更新日調査</a>
                    <a class="btn btn-success" href="?output"><span class="glyphicon glyphicon-file"></span> ファイル出力</a>
                    <a class="btn btn-danger" href="?deleteconf"><span class="glyphicon glyphicon-trash"></span> 出力ファイルの全削除</a>
                </p>
                <p>公開データは<code>_output/dist/</code>内に出力されます。</p>
            </div>
        </div>

<?php

error_reporting(E_ALL);
ini_set('display_errors','On');
date_default_timezone_set('Asia/Tokyo');
set_time_limit(600);

define('ABSPATH', dirname(__FILE__) . '/' );
define('DOCPATH', substr(ABSPATH, 0, -9) . '/' );
require(DOCPATH . '_libs/SiteSetting.class.php');
// Samrty
require('D:\\xampp\libs\smarty-3.1.27\libs\Smarty.class.php');
require('functions.php');

$smarty = new Smarty;
$smarty->template_dir = array(DOCPATH);
$smarty->compile_dir = DOCPATH . '_libs/templates_c/';
$smarty->addPluginsDir(array(DOCPATH . '_libs/plugins/'));

$setting = new SiteSetting;

// topへのパス
$smarty->assign("level", substr($setting->get_base_path(),0,-8));
$basePath = DOCPATH;
//指定パス以下のディレクトリ・ファイル取得

$ignore = array('_htparts','.git','.tmp','node_modules','_output','_libs','_sass');
$listOption = array(
        'html' => array(
            'ignore' => array_merge($ignore,array('404.tpl')),
            'filter' => array('*.tpl')
        ),
        'images' => array(
            'ignore' => $ignore,
            'filter' => array('*.jpg','*.png','*.gif')
        ),
        'css' => array(
            'ignore' => $ignore,
            'filter' => array('*.css')
        ),
        'js' => array(
            'ignore' => array_merge($ignore,array('gulpfile.js')),
            'filter' => array('*.js')
        ),
        'others' => array(
            'ignore' => $ignore,
            'filter' => array('*.eot','*.svg','*.ttf','*.woff')
        )
    );

$outpath = 'relative';
// $outpath = 'absolute';
?>


<?php
if (isset($_GET['check'])):

    $htmlList = array();
    foreach ($listOption as $key => $option) {
        $fileList = getFileList($basePath,-1 ,$option['ignore'],$option['filter']);

        foreach ($fileList as $filePath) {
            $fileInfo = pathinfo($filePath);
            $hpFilePath = str_replace($basePath,"",$filePath);

            if ($key == 'html'){
                // Page Id = first directory name
                $pid = $setting->get_page_id($hpFilePath);
                $smarty->assign("pid", $pid);

                // if level = relative_path
                if ($outpath == 'relative')
                    $smarty->assign("level", $setting->get_relative_path($hpFilePath));

                $html = $smarty->fetch($filePath);

                // $outPutPath = $fileInfo['dirname'].'/'.$fileInfo['filename'].'.html';
                $outPutPath = ABSPATH .'dist/'. substr($hpFilePath,0,-3) . 'html';

                $oldData = "";
                if (file_exists("$outPutPath")){
                    $fd = fopen ("$outPutPath", "r");
                    while (!feof ($fd)) {
                           $oldData .= fgets($fd, 4096);
                    }
                    fclose ($fd);
                }
                $outputtxt = (file_exists($outPutPath)) ? date ("Y年n月j日 H:i:s.", filemtime($outPutPath)) : 'ファイルがありません';
                if($oldData != $html){
                    $htmlList[$key][] = '<tr class="danger"><td>'.substr($hpFilePath,0,-3).'html</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$outputtxt.'</td></tr>';
                }else{
                    $htmlList[$key][] = '<tr><td>'.substr($hpFilePath,0,-3).'html</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$outputtxt.'</td></tr>';
                }
            }else{

                $outPutPath = ABSPATH .'dist/'. $hpFilePath;

                $isNew = true;
                if (file_exists($outPutPath)){
                    if (filemtime($filePath) <= filemtime($outPutPath))
                        $isNew = false;
                }

                $outputtxt = (file_exists($outPutPath)) ? date ("Y年n月j日 H:i:s.", filemtime($outPutPath)) : 'ファイルがありません';
                if ($isNew){
                    $htmlList[$key][] = '<tr class="danger"><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$outputtxt.'</td></tr>';
                }else{
                    $htmlList[$key][] = '<tr><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$outputtxt.'</td></tr>';
                }

            }
        }
    }
?>
    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <li role="presentation"<?php echo ($key == 'html') ? 'class="active"':'';?>><a href="#<?php echo $key;?>" aria-controls="<?php echo $key;?>" role="tab" data-toggle="tab"><?php echo strtoupper($key);?> （<?php echo count($arrayhtml) ?>）</a></li>
        <?php endforeach;?>
    </ul>

    <div class="tab-content">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <div role="tabpanel" class="tab-pane<?php echo ($key == 'html') ? ' active':'';?>" id="<?php echo $key;?>">
                <div class="panel-body row">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>ファイルパス</td>
                                <td>更新日（元ファイル）</td>
                                <td>更新日（出力ファイル）</td>
                            </tr>
                        </thead>
                        <tbody>
            <?php foreach ($arrayhtml as $item) echo $item;?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php  endforeach;?>
    </div>
    <?php print_r("<p>".date("Y/m/d g:i s")."</p>\n"); ?>

<?php endif;// output ?>


<?php
if (isset($_GET['output'])):

    $htmlList = array();
    foreach ($listOption as $key => $option) {
        $fileList = getFileList($basePath,-1 ,$option['ignore'],$option['filter']);

        foreach ($fileList as $filePath) {
            $fileInfo = pathinfo($filePath);
            $hpFilePath = str_replace($basePath,"",$filePath);

            if ($key == 'html'){
                // Page Id = first directory name
                $pid = $setting->get_page_id($hpFilePath);
                $smarty->assign("pid", $pid);

                // if level = relative_path
                if ($outpath == 'relative')
                    $smarty->assign("level", $setting->get_relative_path($hpFilePath));

                $html = $smarty->fetch($filePath);

                // $outPutPath = $fileInfo['dirname'].'/'.$fileInfo['filename'].'.html';
                $outPutPath = ABSPATH .'dist/'. substr($hpFilePath,0,-3) . 'html';

                $oldData = "";
                if (file_exists("$outPutPath")){
                    $fd = fopen ("$outPutPath", "r");
                    while (!feof ($fd)) {
                           $oldData .= fgets($fd, 4096);
                    }
                    fclose ($fd);
                }
                $link = 'dist/' . substr($hpFilePath,0,-3) . 'html';
                if($oldData == $html){
                    $htmlList[$key][] = '<li class="list-group-item">'.substr($hpFilePath,0,-3).'html に変更はあません。 <a href="'.$link.'" target="_blank">Link</a></li>';
                }else{
                    buildPutFile($outPutPath,$html);
                    touch($outPutPath,filemtime($filePath));
                    $htmlList[$key][] = '<li class="list-group-item text-danger">'.substr($hpFilePath,0,-3).'html を書き換えました。<a href="'.$link.'" target="_blank">link</a></li>';
                }
            }else{

                $outPutPath = ABSPATH .'dist/'. $hpFilePath;

                $isNew = true;
                if (file_exists($outPutPath)){
                    if (filemtime($filePath) <= filemtime($outPutPath))
                        $isNew = false;
                }else{
                    buildMakeDir(dirname($outPutPath));
                }

                $link = 'dist/' . $hpFilePath;
                $img = ($key == 'images') ? '<img src="dist/'.$hpFilePath.'" alt="" width="24" /> ' : '';
                if ($isNew){
                    copy($filePath,$outPutPath);
                    touch($outPutPath,filemtime($filePath));
                    $htmlList[$key][] = '<li class="list-group-item text-danger">'.$img.$hpFilePath.' を書き換えました。 <a href="'.$link.'" target="_blank">Link</a></li>';
                }else{
                    $htmlList[$key][] = '<li class="list-group-item">'.$img.$hpFilePath.' に変更はあません。 <a href="'.$link.'" target="_blank">Link</a></li>';
                }

            }
        }
    }
?>
    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <li role="presentation"<?php echo ($key == 'html') ? 'class="active"':'';?>><a href="#<?php echo $key;?>" aria-controls="<?php echo $key;?>" role="tab" data-toggle="tab"><?php echo strtoupper($key);?> （<?php echo count($arrayhtml) ?>）</a></li>
        <?php endforeach;?>
    </ul>

    <div class="tab-content">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <div role="tabpanel" class="tab-pane<?php echo ($key == 'html') ? ' active':'';?>" id="<?php echo $key;?>">
                <div class="panel-body row">
                    <ul class="list-group">
            <?php foreach ($arrayhtml as $item) echo $item;?>
                    </ul>
                </div>
            </div>
        <?php  endforeach;?>
    </div>
    <?php print_r("<p>".date("Y/m/d g:i s")."</p>\n"); ?>

<?php endif;// output ?>


<?php if (isset($_GET['deleteconf'])): ?>
    <div class="panel panel-default">
        <div class="panel-heading">全ての出力データを削除してよろしいですか？</div>
        <div class="panel-body">
            <a class="btn btn-danger" href="?deleteall"><span class="glyphicon glyphicon-remove"></span> 全て削除する</a>
            <a class="btn btn-default" href="./">キャンセル</a>
        </div>
    </div>
<?php endif;// deleteconf ?>


<?php
if (isset($_GET['deleteall'])):
    //　データ削除
    $result = removeDir('dist/');
    if ($result):
?>
        <div class="panel panel-success">
            <div class="panel-heading">全て出力データを削除しました。</div>
            <div class="panel-body">
                <a class="btn btn-default" href="./">戻る</a>
            </div>
        </div>
    <?php else: ?>
    <div class="panel panel-danger">
        <div class="panel-heading">エラー</div>
        <div class="panel-body">
            <p>データの削除ができませんでした。</p>
        </div>
    </div>
    <?php endif; ?>
<?php endif;// deleteall ?>



    </div>
</div>
<footer>
    <div class="container">
        <p class="text-right text-muted">v0.1</p>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script></body>
</html>