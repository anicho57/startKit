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
<!--         <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
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
 -->
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
// ini_set('display_errors',0);
date_default_timezone_set('Asia/Tokyo');
set_time_limit(600);

define('ABSPATH', dirname(__FILE__) );
define('SRCPATH', realpath(ABSPATH . '/../' ));
define('DOCPATH', realpath(ABSPATH . '/../../' ));
require(SRCPATH . '/_libs/SiteSetting.class.php');
// Samrty
require('C:\\xampp\libs\smarty3\libs\Smarty.class.php');
// require("/usr/share/php/Smarty3/libs/Smarty.class.php");
require('functions.php');

$smarty = new Smarty;
$smarty->template_dir = array(DOCPATH);
$smarty->compile_dir = SRCPATH . '/_libs/templates_c/';
$smarty->addPluginsDir(array(SRCPATH . '/_libs/plugins/'));

$setting = new SiteSetting;

// topへのパス
$smarty->assign("level", substr($setting->get_base_path(),0,-strlen('.src/output/')));
// $outpath = 'absolute'; // 絶対 絶対時は上記の設定にて
$outpath = 'relative'; // 相対


$basePath = DOCPATH;
//指定パス以下のディレクトリ・ファイル取得

$ignore = array('_inc','.src','.git','.tmp','node_modules',);
$listOption = array(
        'html' => array(
            'ignore' => array_merge($ignore,array('404.html')),
            'filter' => array('*.html')
        ),
        'images' => array(
            'ignore' => $ignore,
            'filter' => array('*.jpg','*.png','*.gif','*.svg')
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
            'filter' => array('*.xls','*.doc','*.pdf','*.eot','*.ttf','*.woff')
        )
    );

?>


<?php
if (isset($_GET['check'])):

    $htmlList = array();
    $updateCount = array();
    foreach ($listOption as $key => $option) {
        $fileList = getFileList($basePath,-1 ,$option['ignore'],$option['filter']);
        $updateCount[$key] = 0;

        foreach ($fileList as $filePath) {
            $fileInfo = pathinfo($filePath);
            $hpFilePath = str_replace($basePath,"",$filePath);

            if ($key == 'html'){
                // Page Id = first directory name
                $pid = $setting->get_page_id($hpFilePath);
                $smarty->assign("pid", $pid);

                // if level = relative_path
                if ($outpath == 'relative')
                    $smarty->assign("level", $setting->get_relative_path(ltrim($hpFilePath, '/')));

                $html = $smarty->fetch($filePath);
                $smartyUpdateTime = filemtime($filePath);
                // foreach ($smarty->ext->_subTemplate->subTplInfo as $subTpl => $val) {
                //     $subTplTime = filemtime(preg_replace('/^file:/', $smarty->template_dir[0], $subTpl));
                //     $smartyUpdateTime = ($subTplTime > $smartyUpdateTime) ? $subTplTime : $smartyUpdateTime;
                // }


                // $outPutPath = $fileInfo['dirname'].'/'.$fileInfo['filename'].'.html';
                $outPutPath = ABSPATH .'/dist'. $hpFilePath;

                $oldData = "";
                if (file_exists($outPutPath)){
                    $fd = fopen ($outPutPath, "r");
                    while (!feof ($fd)) {
                           $oldData .= fgets($fd, 4096);
                    }
                    fclose ($fd);
                }

                $msgDist = (file_exists($outPutPath)) ? date ("Y年n月j日 H:i:s.", filemtime($outPutPath)) : 'ファイルがありません';
                if($oldData != $html){
                    $updateCount[$key]++;
                    $htmlList[$key][] = '<tr class="danger"><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", $smartyUpdateTime).'</td><td>'.$msgDist.'</td></tr>';
                }else{
                    $htmlList[$key][] = '<tr><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", $smartyUpdateTime).'</td><td>'.$msgDist.'</td></tr>';
                }
            }else{

                $outPutPath = ABSPATH .'/dist'. $hpFilePath;

                $isNew = true;
                if (file_exists($outPutPath))
                    if (filemtime($filePath) <= filemtime($outPutPath))
                        $isNew = false;

                $msgDist = (file_exists($outPutPath)) ? date ("Y年n月j日 H:i:s.", filemtime($outPutPath)) : 'ファイルがありません';
                if ($isNew){
                    $updateCount[$key]++;
                    $htmlList[$key][] = '<tr class="danger"><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$msgDist.'</td></tr>';
                }else{
                    $htmlList[$key][] = '<tr><td>'.$hpFilePath.'</td><td>'.date ("Y年n月j日 H:i:s.", filemtime($filePath)).'</td><td>'.$msgDist.'</td></tr>';
                }

            }
        }
    }
?>
    <ul class="nav nav-tabs" role="tablist">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <li role="presentation"<?php echo ($key == 'html') ? ' class="active"':'';?>><a href="#<?php echo $key;?>" aria-controls="<?php echo $key;?>" role="tab" data-toggle="tab"><?php echo strtoupper($key);?> （<?php echo count($arrayhtml) ?>）<?php echo ($updateCount[$key] > 0) ? '<span class="label label-pill label-danger" style="vertical-align: top;border-radius: 1em;">'.$updateCount[$key].'</span>' : '' ?></a></li>
        <?php endforeach;?>
    </ul>

    <div class="tab-content">
        <?php foreach ($htmlList as $key => $arrayhtml) :?>
            <div role="tabpanel" class="tab-pane<?php echo ($key == 'html') ? ' active':'';?>" id="<?php echo $key;?>">
                <div class="panel-body row">
                    <table class="table table-striped table-bordered">
                        <col>
                        <col width="20%">
                        <col width="20%">
                        <thead>
                            <tr>
                                <th>ファイルパス</th>
                                <th>更新日（src内）</th>
                                <th>更新日（dist内）</th>
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

<?php endif;// output ?>


<?php
if (isset($_GET['output'])):

    $htmlList = array();
    $updateCount = array();
    foreach ($listOption as $key => $option) {
        $fileList = getFileList($basePath,-1 ,$option['ignore'],$option['filter']);
        $updateCount[$key] = 0;

        foreach ($fileList as $filePath) {
            $fileInfo = pathinfo($filePath);
            $hpFilePath = str_replace($basePath,"",$filePath);

            if ($key == 'html'){
                // Page Id = first directory name
                $pid = $setting->get_page_id($hpFilePath);
                $smarty->assign("pid", $pid);

                // if level = relative_path
                if ($outpath == 'relative')
                    $smarty->assign("level", $setting->get_relative_path(ltrim($hpFilePath, '/')));

                $html = $smarty->fetch($filePath);
                $smartyUpdateTime = filemtime($filePath);
                // foreach ($smarty->ext->_subTemplate->subTplInfo as $subTpl => $val) {
                //     $subTplTime = filemtime(preg_replace('/^file:/', $smarty->template_dir[0], $subTpl));
                //     $smartyUpdateTime = ($subTplTime > $smartyUpdateTime) ? $subTplTime : $smartyUpdateTime;
                // }

                // $outPutPath = $fileInfo['dirname'].'/'.$fileInfo['filename'].'.html';
                $outPutPath = ABSPATH .'/dist'. $hpFilePath;

                $oldData = "";
                if (file_exists("$outPutPath")){
                    $fd = fopen ("$outPutPath", "r");
                    while (!feof ($fd)) {
                           $oldData .= fgets($fd, 4096);
                    }
                    fclose ($fd);
                }
                $link = 'dist' . $hpFilePath;
                if($oldData == $html){
                    $htmlList[$key][] = '<li class="list-group-item">'.$hpFilePath.' に変更はあません。 <a href="'.$link.'" target="_blank">Link</a></li>';
                }else{
                    $updateCount[$key]++;
                    buildPutFile($outPutPath,$html);
                    touch($outPutPath,$smartyUpdateTime);
                    $htmlList[$key][] = '<li class="list-group-item text-danger">'.$hpFilePath.' を書き換えました。<a href="'.$link.'" target="_blank">link</a></li>';
                }
            }else{

                $outPutPath = ABSPATH .'/dist'. $hpFilePath;

                $isNew = true;
                if (file_exists($outPutPath)){
                    if (filemtime($filePath) <= filemtime($outPutPath))
                        $isNew = false;
                }else{
                    buildMakeDir(dirname($outPutPath));
                }

                $link = 'dist' . $hpFilePath;
                $img = ($key == 'images') ? '<img src="dist/'.$hpFilePath.'" alt="" width="24" /> ' : '';
                if ($isNew){
                    $updateCount[$key]++;
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
            <li role="presentation"<?php echo ($key == 'html') ? ' class="active"':'';?>><a href="#<?php echo $key;?>" aria-controls="<?php echo $key;?>" role="tab" data-toggle="tab"><?php echo strtoupper($key);?> （<?php echo count($arrayhtml) ?>）<?php echo ($updateCount[$key] > 0) ? '<span class="label label-pill label-success" style="vertical-align: top;border-radius: 1em;">'.$updateCount[$key].'</span>' : '' ?></a></li>
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