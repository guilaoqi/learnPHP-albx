<?php 
    date_default_timezone_set('PRC');
    require_once('../config.php');
    require_once XIU_DIR.'functions.php';
    $current_user=xiu_get_current_user();
    $category=xiu_fetch_all('select  id, name from categories ');
    

    function load_post(){
      if(!empty($_GET['post_id'])){
        global $post_id;
        $post_id=$_GET['post_id'];
        $sql=sprintf("select * from posts where id = %d ", $post_id);
        $datas=xiu_fetch_one($sql);
        return $datas;
      }
    }




    if($_SERVER['REQUEST_METHOD']==="POST"){
      $feature=image_save();
      // var_dump($_FILES);
      if(empty($_GET['post_id'])){
      //==========POST+无id：新增业务===========       
        if($feature){
          post_add($feature);
        }
      }else{
      //==========POST+有id：修改业务===========
        $post_id=$_GET['post_id'];
        post_save($feature,$post_id);

      }

    }

    if($_SERVER['REQUEST_METHOD']==="GET"){
      $datas=load_post();
    }



?>









<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <?php if (!empty($message)): ?>
        <?php if ($message==='文章添加成功！'||$message==='文章修改成功！'): ?>
          <div class="alert alert-success">
            <strong>成功！</strong><?php echo $message ?>
          </div>
        <?php else: ?>
          <div class="alert alert-danger">
            <strong>错误！</strong><?php echo $message ?>
          </div>
        <?php endif ?>
      <?php endif ?>


      <form class="row" method='post' action='<?php echo $_SERVER['PHP_SELF'].'?post_id='.empty($post_id)?'':$post_id ?>' enctype="multipart/form-data">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题*</label>
            <input value='<?php echo empty($datas['title'])?'':$datas['title'];?>' id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group " style="border-radius:50px" >
            <label for="content">内容</label>
            <!-- <textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"> -->
              <!-- </textarea> -->
              <!-- 加载编辑器的容器 -->      
              <script  type="text/plain" id="container" name="content" style="width:100%;height:100%;">
                <?php echo empty($datas['content'])?'<p style="opacity:0.5" class="containerp" id="myEditorP" >在此输入</p>':$datas['content'];?>
              </script>     


          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">别名*</label>
            <input id="slug" class="form-control" name="slug" type="text" placeholder="slug" value='<?php echo empty($datas['slug'])?'':$datas['slug'];?>'>
            <p class="help-block">唯一标示/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file" value='<?php echo empty($datas['feature'])?'':$datas['feature'];?>'>
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category_id">
              <?php foreach ($category as  $cat_items): ?>
                <option value="<?php echo $cat_items['id']; ?>" 
                  <?php echo isset($datas['category_id'])&&$datas['category_id']==$cat_items['id']?'selected':'';  ?>
                  ><?php echo $cat_items['name']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label> 
            <input id="created" class="form-control" name="created" type="datetime-local" value="<?php 
            echo empty($datas['created'])?'':str_replace(' ','T',substr($datas['created'],0,-3));?>">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted"
              <?php echo isset($datas['status'])&&$datas['status']=='drafted'?'selected':'';  ?>
              >草稿</option>
              <option value="published"
              <?php echo isset($datas['status'])&&$datas['status']=='published'?'selected':'';  ?>
              >已发布</option>
              <option value="trashed"
            <?php echo isset($datas['status'])&&$datas['status']=='trashed'?'selected':'';  ?>
            >回收站</option>
            </select>
          </div>
          <div class="form-group">
            <button class="btn btn-primary" type="submit">保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  <?php $current_page='post_add' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
    <!-- 配置文件 -->
    <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/static/assets/vendors/ueditor/ueditor.all.min.js"></script>
    <script type="text/javascript" src="/static/assets/vendors/ueditor/lang/zh-cn/zh-cn.js"></script>
    <!-- <script type="/static/assets/vendors/umeditor/text/javascript" src="third-party/jquery.min.js"></script> -->
    <!-- 实例化编辑器 -->


    <script type="text/javascript">
     //实例化富文本编辑器
    var ue = UE.getEditor('container');
    ue.addListener( 'focus', function( ue ) {
     //编辑器家获得焦点后移除默认文本
     var subb=$("#ueditor_0").contents().find(".containerp").remove();
     $('.containerp').remove();    
    });    
    </script>
    <script type="text/javascript" src="/static/assets/vendors/umeditor/1.js"></script>
    <script>
      $(function($){
        function GetDate()
        {
          var date = new Date();
          this.year = date.getFullYear();
          this.month = date.getMonth() + 1;
          if(this.month<10){this.month='0'+this.month}
          this.date = date.getDate();
          if(this.date<10){this.date='0'+this.date}
          this.hour = date.getHours() < 10 ? "0" + date.getHours() : date.getHours();
          this.minute = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
          this.second = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
          var currentTime = this.year + "-" + this.month + "-" + this.date +'T'+this.hour+':'+this.minute;
          return currentTime;
        }
        $('#created').on('focus',function(){
          var d = GetDate();
          $('#created').val(d);
        });
      })
    </script>
</body>
</html>
