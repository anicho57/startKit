<?php

//Smartyライブラリを読み込む
require_once(SMARTY_DIR . "Smarty.class.php");

//MySmartyクラスの定義
class MySmarty extends Smarty{
	function __construct() {
		parent::__construct();
        $this->template_dir = HTDOCS;
        $this->compile_dir  = SMARTY_MYDIR . 'templates_c/';
        $this->cache_dir    = SMARTY_MYDIR . 'cache/';
        $this->config_dir   = SMARTY_MYDIR . 'configs/';
	}
}

?>