

  <?php 
     require_once('../config.php');
     require_once XIU_DIR.'functions.php';
     $current_page='categories' ;
     xiu_get_current_user();


     function edit_cat(){
         if (empty($_POST['name'])||empty($_POST['slug'])) {
         $GLOBALS['err_message'] = '分类信息不能为空！';
         return;
       }
       $name=$_POST['name'];
       $slug=$_POST['slug'];
       $edit2_id=$_GET['id'];
       $result=xiu_execute("update categories set slug='{$slug}',name='{$name}' where id = '{$edit2_id}' ");
       $GLOBALS['err_message']=$result>0?'':'修改失败！' ;
     }
     function add_cat(){
       if (empty($_POST['name'])||empty($_POST['slug'])) {
         $GLOBALS['err_message'] = '请完整填写分类信息！';
         return;
       }
       $name=$_POST['name'];
       $slug=$_POST['slug'];
       $result=xiu_execute("insert into categories values(null,'{$slug}','{$name}')");
       $GLOBALS['err_message']=$result>0?'':'添加失败！' ;
     }


     if ($_SERVER['REQUEST_METHOD']==='POST') {
      if(empty($_GET['id'])){add_cat();}
      else{  
        edit_cat();
        $edit2_id=$_GET['id'];
        }
     }

     if ($_SERVER['REQUEST_METHOD']==='GET') {
      if(!empty($_GET['id'])){
        $edit_id=$_GET['id'];
        $data=xiu_fetch_one("select * from categories where id= {$edit_id} ");
        $name=$data['name'];
        $slug=$data['slug'];
      }
     }

     $categories=xiu_fetch_all("select * from categories");

  ?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (isset($GLOBALS['err_message'])): ?>
        <?php if (empty($GLOBALS['err_message'])): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo empty($edit2_id)?'添加成功！':'修改成功！' ;?>
          </div>
        <?php else:?>
          <div class="alert alert-danger">
            <strong>失败！</strong><?php echo $GLOBALS['err_message'] ;?>
          </div>
        <?php endif ?>
      <?php endif ?>

      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form method='post' action="<?php echo $_SERVER['PHP_SELF'].'?id='.(empty($name)?'':$edit_id) ?>">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称" value="<?php echo empty($name)?'':$name ;?>">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value="<?php echo empty($slug)?'':$slug ;?>">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit"><?php echo empty($name)?'添加':'修改' ;?></button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" id='btn-delete' href="/admin/categories-delete.php" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($categories as $key => $value): ?>
                <tr>
                <td class="text-center"><input  type="checkbox" data-id='<?php echo $value['id']; ?>'></td>
                <td><?php echo $value['name']; ?></td>
                <td><?php echo $value['slug']; ?></td>
                <td class="text-center">
                  <a href="/admin/categories.php?id=<?php echo $value['id'] ;?>" class="btn btn-info btn-xs">编辑</a>
                  <a href="/admin/categories-delete.php?id=<?php echo $value['id'] ;?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach ?>
              
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


  <?php include './inc/aside.php' ; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>  
    $(function($){

      var $checkbox=$('tbody input');
      var $btn_delete=$('#btn-delete');
      var checkList=[];

      $checkbox.on('change',function(){
        var $id=$(this).attr('data-id');
        $(this).prop('checked')?checkList.push($id):checkList.splice(checkList.indexOf($id),1);
        console.log(checkList);
        checkList.length>0 ? $btn_delete.fadeIn():$btn_delete.fadeOut();
        $btn_delete.prop('search','?id='+checkList);
      })
      $('thead input').on('change',function(){
        $checkbox.prop('checked',$(this).prop('checked')).trigger('change');
      })


    })
  </script>

</body>
</html>
