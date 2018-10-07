<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  $user=xiu_get_current_user();
  if($user['status']!='activated'){
  	exit('您还没有删除操作权限，请联系总管理员！');
  }
  $id=empty($_GET['id'])?'0':$_GET['id'];
  $sql="delete from users where id = {$id}";
  $row=xiu_execute($sql);
  header("Content-Type:application/json");
  echo json_encode($row);


