<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  $sql="select 
  	id, 
  	avatar,
	  email,
	  slug,
	  nickname,
	  status
   from users
   ";
  $datas=xiu_fetch_all($sql);
  header("Content-Type:application/json");
  echo json_encode($datas);


