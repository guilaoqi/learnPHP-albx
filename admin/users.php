<?php 
     require_once('../config.php');
     require_once XIU_DIR.'functions.php';
     $user=xiu_get_current_user();
     function edit_user(){
   if(empty($_POST['slug'])||empty($_POST['email'])||empty($_POST['nickname'])||empty($_POST['password'])){
     $GLOBALS['err_message'] = '缺少必要信息！';
     return;
   }
   $slug=$_POST['slug'];
   $user_id=$_POST['user_id'];
   $email=$_POST['email'];
   $nickname=$_POST['nickname'];
   $password=md5($_POST['password']);
   $result=xiu_execute("update users set slug='{$slug}', email='{$email}', nickname='{$nickname}', password='{$password}' where id = {$user_id} ");
   $GLOBALS['err_message']=$result>0?'':'修改失败！' ;
 }
 function add_user(){
   if (empty($_POST['slug'])||empty($_POST['email'])||empty($_POST['nickname'])||empty($_POST['password'])) {
     $GLOBALS['err_message'] = '请完整填写表单！';
     return;
   }
   $slug=$_POST['slug'];
   $email=$_POST['email'];
   $nickname=$_POST['nickname'];
   $password=md5($_POST['password']);
   $result=xiu_execute("insert into users values(null,'{$slug}', '{$email}' , '{$password}', '{$nickname}',null,null,'activated')");
   $GLOBALS['err_message']=$result>0?'':'添加失败！' ;
 }




 if ($_SERVER['REQUEST_METHOD']==='POST') {
  // var_dump($_POST);
  if($user['status']!='activated'){
    $GLOBALS['err_message']='您还没有添加用户操作权限，请联系总管理员！';
  }
  else if(empty($_POST['user_id'])){
    add_user();
  }
  else{  
    $user_id=$_POST['user_id'];
    edit_user();
  }
 }
 ?>






<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Users &laquo; Admin</title>
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
        <h1>用户</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div id="err_mess" class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="row">
        <div class="col-md-4">
          <form method='post' action="<?php echo $_SERVER['PHP_SELF'] ;?>">
            <h2>添加新用户</h2>



            <?php if (isset($GLOBALS['err_message'])): ?>
              <?php if (empty($GLOBALS['err_message'])): ?>
                <div class="alert alert-success">
                  <strong>成功！</strong><?php echo empty($user_id)?'添加成功！':'修改成功！' ;?>
                </div>
              <?php else:?>
                <div class="alert alert-danger">
                  <strong>失败！</strong><?php echo $GLOBALS['err_message'] ;?>
                </div>
              <?php endif ?>
            <?php endif ?>





            <div class="form-group">
              <label for="email">邮箱</label>
              <input id="email" class="form-control" name="email" type="email" placeholder="邮箱">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/author/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <label for="nickname">昵称</label>
              <input id="nickname" class="form-control" name="nickname" type="text" placeholder="昵称">
            </div>
            <div class="form-group">
              <label for="password">密码</label>
              <input id="password" class="form-control" name="password" type="text" placeholder="密码">
              <input id="user_id" name="user_id" type='hidden'>
            </div>
            <div class="form-group">
              <button id='btn_submit' class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
               <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center" width="80">头像</th>
                <th>邮箱</th>
                <th>别名</th>
                <th>昵称</th>
                <th>状态</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody></tbody>
            <script type='text/jsrender' id="users_tmpl">
                {{for users}}
                <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td class="text-center"><img class="avatar" src="{{:avatar}}"></td>
                <td>{{:email}}</td>
                <td>{{:slug}}</td>
                <td>{{:nickname}}</td>
                <td>{{if status=="activated"}}激活{{else}}未激活{{/if}}</td>
                <td class="text-center" td_id="{{:id}}" >
                  <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
                </tr>
                {{/for}}}
              </script>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $current_page='users' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src='/static/assets/vendors/jsrender/jsrender.js'></script>
  <script>NProgress.done()</script>
  <script>
    function update_users(){
      $.get('./api/get_user.php',{},function(res){
        var html=$("#users_tmpl").render({users:res});
        $('tbody').html(html);
      })
    }
    update_users();
     //==为编辑按钮注册点击事件==
        $('tbody').on('click','a.btn-default',function(){          
          var id=$(this).parent().attr('td_id');
          // console.log(id);
          $.get('./api/edit_load_user.php',{id:id},function(res2){
            // console.log(res2);
            if(res2=="您还没有编辑操作权限，请联系总管理员！"){
              $('#err_mess').css('display','block').text(res2);
            }else{
              $('#email').val(res2.email);
              $('#slug').val(res2.slug);
              $('#nickname').val(res2.nickname);
              $('#user_id').val(res2.id);
              $('#btn_submit').text('修改');
              $('h2').text("编辑用户");
            }            
          });
        });
      //==为删除按钮注册点击事件==
        $('tbody').on('click','a.btn-danger',function(){
          var id=$(this).parent().attr('td_id');
          // console.log(id);
          $.get('./api/delete_user.php',{id:id},function(res3){
            if(res3=='您还没有删除操作权限，请联系总管理员！'){
              $('#err_mess').css('display','block').text(res3);
            }else{
              update_users();
            }
          });
        });

  </script>
</body>
</html>
