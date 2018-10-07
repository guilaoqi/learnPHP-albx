<?php 
  require_once('../config.php');
  require_once XIU_DIR.'functions.php';
  $user=xiu_get_current_user();
 ?>





<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
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
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div id='warning' class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label" >确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码" name='newpass'>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <button id='button'  type="submit" class="btn btn-primary">修改密码</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php $current_page='password_reset' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script type="text/javascript">
    $('#old').on('blur',function(){
      var pass=$(this).val();
      $.post('./api/change_password.php',{pass:pass},function(res){
        if(res){
          console.log(res);
          $('#warning').css('display','block').text(res);
          $('#old').css({'border-color':'pink','box-shadow':'0 0 5px rgba(255, 0, 0, 0.9)'});
        }else{
          $('#warning').css('display','none');
          $('#old').css({'border-color':'#ccc','box-shadow':'none'});
        }
      })
    })

    $('#password').on('blur',function(){
       var newpass=$(this).val();
      if(newpass.length<6){
        $('#warning').css('display','block').text('新密码过短');
        $('#password').css({'border-color':'pink','box-shadow':'0 0 5px rgba(255, 0, 0, 0.9)'});}
      else{
        $('#warning').css('display','none');
        $(this).css({'border-color':'#ccc','box-shadow':'none'});
      }
    })





    $('#button').on('click',function(){
      var pass=$('#old').val();
      var newpass=$('#password').val();
      console.log(newpass);
      var confirm=$('#confirm').val();
      console.log(confirm);
      if(newpass.length<6){
        $('#warning').css('display','block').text('新密码过短');
        $('#password').css({'border-color':'pink','box-shadow':'0 0 5px rgba(255, 0, 0, 0.9)'});
        return false;
      }else if(newpass!=confirm){
        $('#warning').css('display','block').text('两次新密码输入不一致');
        $('#password,#confirm').css({'border-color':'pink','box-shadow':'0 0 5px rgba(255, 0, 0, 0.9)'});
        return false;
      }
      else{
        $.post('./api/change_password.php',{pass:pass,newpass:newpass},function(res){
          console.log(res);
          if(res&&res!='success'){
            $('#warning').css('display','block').text(res);
        }else if(res='success'){
          $('#warning').removeClass('alert-danger').addClass('alert-success').css('display','block').text('密码修改成功');
          }
        });
        return false;
      }
    })
  </script>
</body>
</html>
