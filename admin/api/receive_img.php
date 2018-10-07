<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  xiu_get_current_user();
  $path=image_save();
  // header('Content-Type:application/json');
  header("Content-Type:application/json");
  $messpath= ['message'=>"{$message}",'path'=>"{$path}" ];
  // $nummm= ["message"=>"","path"=>"Cpng"];
  echo json_encode($messpath);


