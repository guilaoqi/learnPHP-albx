<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
    $user=xiu_get_current_user();
  if($user['status']!='activated'){
    //无编辑操作权限
    exit('您还没有编辑操作权限，请联系总管理员！');
  }
  $id=empty($_GET['id'])?'0':$_GET['id'];
  $sql="select 
    id, 
    avatar,
    password,
    email,
    slug,
    nickname,
    status
   from users
   where id = {$id}
   ";
  $datas=xiu_fetch_one($sql);
  header("Content-Type:application/json");
  echo json_encode($datas);


