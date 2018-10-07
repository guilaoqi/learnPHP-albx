<?php 
    require_once('../config.php');
    require_once XIU_DIR.'functions.php';
    xiu_get_current_user();



	if(empty($_GET['id'])){
		exit('缺少必要参数');
	}
	$id=$_GET['id'];

	$page=empty($_GET['page'])?'1':$_GET['page'];
	$category=empty($_GET['category'])?'all':$_GET['category'];
	$status=empty($_GET['status'])?'all':$_GET['status'];

	$rows=xiu_execute("delete from posts where id in ( {$id} )");
	if(!$rows){
		exit('删除失败');
	}else{
		// header("Location:/admin/posts.php?page={$page}&category={$category}&status={$status}");

		header("Location:{$_SERVER['HTTP_REFERER']}");
	}






