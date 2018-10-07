<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  $id=empty($_GET['id'])?'0':$_GET['id'];
  $sql=sprintf('delete from comments where id in (%s)',$id);  	
  echo xiu_execute($sql);
  // echo $sql;


