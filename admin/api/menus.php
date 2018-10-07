<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  if($_SERVER['REQUEST_METHOD']=='GET'){
      $key=empty($_GET['key'])?'0':$_GET['key'];
      $sql="select value from options where `key`='{$key}'";
      $datas=xiu_fetch_one($sql);
      header("Content-Type:application/json");
      echo $datas['value'];
  }else{
    $key=empty($_POST['key'])?'0':$_POST['key'];
    $value=empty($_POST['value'])?'0':$_POST['value'];
    $sql="update options set value='{$value}' where `key` ='{$key}' ";
    $raw=xiu_execute($sql);
    echo $raw;
  }








