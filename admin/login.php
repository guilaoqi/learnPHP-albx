
<?php 
require_once('../config.php');
require_once(XIU_DIR.'functions.php');

  function login(){
    global $erro_message;
    if (empty($_POST['email'])) {
      $erro_message="请输入用户名";
      return;
    }
    if (empty($_POST['pass'])) {
      $erro_message="请输入密码";
      return;
    }
    $email=$_POST['email'];
    $pass=$_POST['pass'];
    
    $users=xiu_fetch_one("select * from users where email='{$email}' limit 1");
    if(!$users){
      $erro_message='用户不存在！';
      return;
    }
    if($users["password"]!==md5($pass)){
      $erro_message='密码错误！';
      return;
    }
    if(!session_id()) session_start();
    $_SESSION['last_access'] = time(); 
    $_SESSION['current_user']=$users;
    header('location:/admin/index.php');
  }

if ($_SERVER['REQUEST_METHOD']==="POST") {
  login();
}

if ($_SERVER['REQUEST_METHOD']==='GET'&&isset($_GET['action'])&&$_GET['action']==='logout') {
  if(!session_id()) session_start();
  unset($_SESSION['last_access']);
  unset($_SESSION['current_user']);

}


?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
</head>
<body>
  <div class="login">
    <form class="login-wrap" action="<?php echo $_SERVER['PHP_SELF'] ;?>" method="POST" >
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <?php if (!empty($erro_message)):?>
      <div class="alert alert-danger">
        <strong>错误！</strong> <?php echo $erro_message ; ?>
      </div>
    <?php endif ;?>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="pass" type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" href="index.html">登 录</button>
    </form>
  </div>
  <script type="text/javascript">

    $(function($){

      $("#email").on("blur",function(){
        // =====邮箱验证====
        var reg=/^[a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*\.[a-zA-Z0-9]{2,6}$/ ;
        var email_val=$(this).val() ;
        if(!email_val || !reg.test(email_val)){
          return;}
        // ===调用Jquery的Ajax方法，用get去委托avatar.php请求头像地址===       
        $.get('/admin/api/avatar.php',{email:email_val},function(res){
          var array=["缺少参数",'连接数据库失败','查询失败','用户不存在！']
          if($.inArray(res,array)!=-1){
            console.log(res);
            $('.alert.alert-danger').remove();
            $('.avatar').after("<div class='alert alert-danger'><strong>错误！</strong>"+res+"</div>");
            return;
          }else{
            $('.alert.alert-danger').remove();
            $('.avatar').attr("src",res).fadeIn();
          }
          
        });
      })

    })
  </script>
</body>
</html>
