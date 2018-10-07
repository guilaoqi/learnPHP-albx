<?php  
  require_once('../config.php');
  require_once XIU_DIR.'functions.php';
  $user_email=xiu_get_current_user()['email'];
  $user=xiu_fetch_one("select * from users where email='{$user_email}' limit 1");
  var_dump($user);


  function save_profile(){
    global $post_message;
    if(!isset($_POST['slug'])||!isset($_POST['avatar'])||!isset($_POST['nickname'])||!isset($_POST['bio'])||!isset($_POST['email'])){
      $post_message='表单提交错误！';
      return;
    }
    if(empty($_POST['slug'])){
    $post_message='请填写别名！';
      return;
    }

    if(empty($_POST['avatar'])){ 
      $sql=sprintf("update users set 
      slug='%s',
      nickname='%s',
      bio='%s'  
      where 
      email='%s' ",$_POST['slug'],$_POST['nickname'],$_POST['bio'],$_POST['email']);
      }
    else{
      $sql=sprintf("update users set 
      avatar='%s',
      slug='%s',
      nickname='%s',
      bio='%s'  
      where 
      email='%s' ",$_POST['avatar'],$_POST['slug'],$_POST['nickname'],$_POST['bio'],$_POST['email']);
    }

    $raw=xiu_execute($sql);
    $post_message=$raw>0?'修改成功！':'修改失败！';
  }

  if($_SERVER['REQUEST_METHOD']=="POST"){
    save_profile();
  }
 ?>




<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
  <?php include './inc/navbar.php' ;?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>我的个人资料</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if(!empty($post_message)&&$post_message=='修改成功！') : ?>
        <div id='warnning' class="alert alert-success" >
          <strong>修改成功！</strong>
        </div>
      <?php elseif(!empty($post_message)): ?>
        <div id='warnning' class="alert alert-danger" >
          <strong>错误！</strong><?php echo $post_message ?>
        </div>
      <?php endif ?>
      <div id='warnning' class="alert alert-danger" style='display: none'>
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal" method='POST' action="<?php echo $_SERVER['PHP_SELF'] ?>">
        <div class="form-group">
          <label class="col-sm-3 control-label">头像</label>
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img src='<?php echo empty($user['avatar'])?'/static/assets/img/default.png':$user['avatar'] ?>' >
              <i class="mask fa fa-upload"></i>
            </label>
            <input id='hid_avatar' type="hidden" name="avatar">
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="<?php echo $user['email'] ?>" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="slug" class="col-sm-3 control-label">别名</label>
          <div class="col-sm-6">
            <input id="slug" class="form-control" name="slug" type="type" value="<?php echo $user['slug'] ?>" placeholder="slug">
            <p class="help-block">https://zce.me/author/<strong>zce</strong></p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="<?php echo $user['nickname'] ?>" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea name="bio" id="bio" class="form-control" placeholder="Bio" cols="30" rows="6"><?php echo empty($user['bio'])?'MAKE IT BETTER!':$user['bio'] ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <button type="submit" class="btn btn-primary">更新</button>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php $current_page='profile' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>

  <script>
    $('#avatar').on('change',function(){
      console.log(111);
      var files=$(this).prop('files');
      if(!files.length)return;
      console.log(files[0]);
      var data= new FormData();
      console.dir(data);
      data.append('feature',files[0]);
      var xhl=new XMLHttpRequest();
      xhl.open('post','/admin/api/receive_img.php');
      xhl.send(data);
      xhl.onload=function(){
      console.log(JSON.parse(this.responseText));
      var message=JSON.parse(this.responseText);
      if(!message.path||message.path==' '){
        $('#warnning').css('display','block').text(message.message);
      }else{
        var path=message.path.slice(12);
        console.log(path);
        $('#avatar').next().prop("src",path);
        $('#hid_avatar').val(path);
      }        
      }
    })
  </script>
</body>
</html>
