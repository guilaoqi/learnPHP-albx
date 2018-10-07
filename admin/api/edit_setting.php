<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
 function edit_setting(){
   if(empty($_POST['site_logo'])||empty($_POST['site_name'])||empty($_POST['site_description'])||empty($_POST['site_keywords'])||empty($_POST['comment_status'])||empty($_POST['comment_reviewed'])){
     exit('缺少必要信息！');
   }
    $site_logo=$_POST['site_logo'];
    $site_name=$_POST['site_name'];
    $site_description=$_POST['site_description'];
    $site_keywords=$_POST['site_keywords'];
    $comment_status=$_POST['comment_status'];
    $comment_reviewed=$_POST['comment_reviewed'];
    $result1=xiu_execute("update options set value='{$site_logo}' where `key` = 'site_logo' ");
    $result2=xiu_execute("update options set value='{$site_name}' where `key` = 'site_name' ");
    $result3=xiu_execute("update options set value='{$site_description}' where `key` = 'site_description' ");
    $result4=xiu_execute("update options set value='{$site_keywords}' where `key` = 'site_keywords' ");
    $result5=xiu_execute("update options set value='{$comment_status}' where `key` = 'comment_status' ");
    $result6=xiu_execute("update options set value='{$comment_reviewed}' where `key` = 'comment_reviewed' ");
    if(!($result1&&$result2&&$result3&&$result4&&$result5&&$result6)){
      exit("上传失败");
    }else{
      exit('0');
    }
 }

 if ($_SERVER['REQUEST_METHOD']==='POST') {
    edit_setting();
}






