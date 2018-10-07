<?php 
require_once('../config.php');
require_once(XIU_DIR.'functions.php');
xiu_get_current_user();

if (empty($_GET['id'])) {
	exit("缺少必要参数");
}

$id=$_GET['id'];

$rows=xiu_execute("delete from categories where id in ( {$id} )");

header('Location: /admin/categories.php');