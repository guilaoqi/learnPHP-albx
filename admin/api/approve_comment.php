<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  xiu_get_current_user();
  $id=empty($_GET['id'])?'0':$_GET['id'];
  $sql=sprintf("update comments set status='approved' where id in (%s)",$id);    
  echo xiu_execute($sql);
  // echo $sql;