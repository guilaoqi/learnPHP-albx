<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  $user_email=xiu_get_current_user()['email'];

  if(empty($_POST["pass"])){
  	exit("请填写旧密码");
  };
  $user_pass=xiu_fetch_one("select password from users where email='{$user_email}' limit 1")['password'];
  if(md5($_POST["pass"])!=$user_pass){
  	exit('旧密码填写错误');
  }
  if(empty($_POST["newpass"])){
  	exit('');
  }
  $newpass=md5($_POST["newpass"]);
  $sql=sprintf("update users set password='%s' where email='%s' ",$newpass,$user_email);
  $raw=xiu_execute($sql);
  if($raw<1){
  	exit('密码设置失败');
  }

  echo 'success';
