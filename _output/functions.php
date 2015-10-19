<?php
/**
 * 指定したディレクトリから、ファイルのパスの配列を返す
 *
 * @param string $directory ディレクトリ
 * @param int $depth たどる階層の深さ -1 だと無制限
 * @param array $ignore 無視するパターン (fnmatch関数の引数)
 * @param array $filter 検出するパターン (fnmatch関数の引数)
 * @return array ファイルパスのリスト
 */
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

/**
 * ディレクトリが存在しない場合、作成を試みてファイル作成を行う
 *
 * @param string $filepath ファイルのパス
 * @param string $content 書き込む内容
 * @param string $hook_func ファイル、ディレクトリ作成ごとに実行する関数を指定。
 * @return resouce ファイルポインタ
 */
function buildPutFile($filepath,$content){
   buildMakeDir(dirname($filepath));

    if ($fp =buildFileOpen($filepath)){
        flock($fp,LOCK_EX);
        ftruncate($fp,0);
        fputs($fp,$content);
        flock($fp,LOCK_UN);
        fclose($fp);
        return true;
    }
    return false;
}

/**
 * 与えられたパスのディレクトリを全て作成する
 *
 * @param string $filepath パス
 * @param string $hook_func ディレクトリ作成ごとに実行する関数を指定。
 */
function buildMakeDir($filepath){
    if (strpos($filepath,'\\') !== false){
        $filepath = str_replace('\\','/',$filepath);
    }
    $filepath = rtrim($filepath,'\\/').'/';
    if (!is_dir($filepath)){
        //再帰的に呼び出す　一つ上のパスがディレクトリで存在しているか確認
        if ($parent_path = dirname($filepath)){
           buildMakeDir($parent_path);
        }
        if (@mkdir($filepath)){
            chmod($filepath,0755);
        }else{
            return false;
        }
    }
}

/**
 * ディレクトリが存在しない場合、作成を試みてファイルポインタを返す。
 *
 * @param string $filepath ファイルのパス
 * @param string $mode fopenのmode
 * @param string $hook_func ファイル、ディレクトリ作成ごとに実行する関数を指定。
 * @return resouce ファイルポインタ
 */
function buildFileOpen($filepath,$mode = 'w'){
   buildMakeDir(dirname($filepath));

    if (!is_dir($filepath)){
        return fopen($filepath,$mode);
    }

    return false;
}
/**
 * ディレクトリごと再帰的に削除
 * 中にファイルが入っていても削除する。
 *
 * @param string $dir ディレクトリ名
 * @param bool $remove_top トップのディレクトリを削除するか
 * @param string $hook_func 削除ごとに実行する関数を指定。
 */
function removeDir($dir,$remove_top = true){
    if (is_file($dir)){
        unlink($dir);
    }else if (is_dir($dir)){
    if (class_exists('FilesystemIterator',false)){
            $iterator = new RecursiveDirectoryiterator($dir,FilesystemIterator::SKIP_DOTS);
        }else{
            $iterator = new RecursiveDirectoryiterator($dir);
        }
        foreach($iterator as $path){
            if ($path->isDir()){
                removeDir($path->getPathname());
            }else{
                unlink($path->getPathname());
            }
        }
        if ($remove_top){
            rmdir($dir);
        }
    }
    if (file_exists($dir)){
        return false;
    }else{
        return true;
    }
}
