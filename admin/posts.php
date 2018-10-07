  <?php
    require_once('../config.php');
    require_once XIU_DIR.'functions.php';
    xiu_get_current_user();

    $page=empty($_GET['page'])?1:(int)$_GET['page'];
    $size=10;
    $span=5;

        //=================设置筛选条件==================
    $where='1=1';
    var_dump($where);
    if(isset($_GET['category'])&&$_GET['category']!="all"){
      $where.=" and posts.category_id={$_GET['category']}";
    }
    if(isset($_GET['status'])&&$_GET['status']!="all"){
      $where.=" and posts.status='{$_GET['status']}'";
    }
    var_dump($where);
    //=====================获取筛选条件下的页数==========================


    $content=(int)xiu_fetch_one("select count(1) as post_length from posts 
      inner join categories on posts.category_id = categories.id
      inner join users on posts.user_id = users.id
      where {$where}
      ")['post_length'];
    $max_page=(int)ceil($content/$size);
    //=======================校验$span是否合理===========================
    $span=$span<$max_page?$span:$max_page;
    $min=$page-(int)floor($span/2);
    $max=$min+$span;
    //========================$min不为负值==============================
    if($min<1){
      $min=1;
      $max=$min+$span;
      $page=$min<$page?$page:$min;
    }
    //========================$max不超过$max_page=======================
    if($max>$max_page+1){
      $max=$max_page+1;
      $min=$max-$span;
      $page=$max>$page?$page:($max-1);
    }



   


    //==============按条件查询所需展示结果======================
    $offset=($page-1)*$size;
    $posts=xiu_fetch_all("select 
      posts.id,
      posts.title,
      posts.created,
      posts.`status`,
      categories.`name` as category_name,
      users.nickname as user_name
      from posts
      inner join categories on posts.category_id = categories.id
      inner join users on posts.user_id = users.id
      where {$where}
      order by posts.created desc
      limit {$offset},{$size}
      ");

    function xiu_match_status($attr){
      $attrs=[
        "published"=>'已发布',
        'drafted'=>'草稿',
        'trashed'=>'回收站'
      ];
      return isset($attrs[$attr])?$attrs[$attr]:'未知';
    };

    function xiu_time($attr){
      $timestamp=strtotime($attr);
      return date('Y年m月d日 <b\r> H:i:s',$timestamp);
    }

    // function xiu_author($attr){
    //   return xiu_fetch_one("select * from users where id = {$attr}")['nickname'];
    // }

    // function xiu_categories($attr){
    //   return xiu_fetch_one("select * from categories where id = {$attr}")['name'];
    // }

    $categories=xiu_fetch_all('select * from categories');


    //===================设置页码按钮的href用==================================
    $category=isset($_GET['category'])?$_GET['category']:'all';
    $status=isset($_GET['status'])?$_GET['status']:'all';


   ?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <select name="category" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php foreach ($categories as $items): ?>
              <option value="<?php echo $items['id'] ?>"
                <?php echo isset($_GET['category'])&&$_GET['category']==$items['id']?'selected':'';  ?>
              >  <?php echo $items['name'] ?>
              </option>
            <?php endforeach ?>

          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted"
            <?php echo isset($_GET['status'])&&$_GET['status']=='drafted'?'selected':'';  ?>
            >草稿</option>
            <option value="published"
            <?php echo isset($_GET['status'])&&$_GET['status']=='published'?'selected':'';  ?>
            >已发布</option>
            <option value="trashed"
            <?php echo isset($_GET['status'])&&$_GET['status']=='trashed'?'selected':'';  ?>
            >回收站</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="<?php $pagex=$page-1;echo "?page={$pagex}&category={$category}&status={$status}";?>">上一页</a></li>
          <?php for($i=$min;$i<$max;$i++):?>
          <li <?php echo ($i===$page)?' class="active"':'';?> ><a href="<?php echo 
          
          "?page={$i}&category={$category}&status={$status}"
          // $i.'&category='.$_GET['category'].'&status='.$_GET['status']


          ;?>" ><?php echo $i ;?></a></li>
          <?php endfor ?>
          <li><a href="<?php $pagex=$page+1;echo "?page={$pagex}&category={$category}&status={$status}";?>" >下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($posts)): ?>
              <?php foreach ($posts as $items): ?>
              <tr>
                <td class="text-center"><input value='<?php echo $items['id'] ?>' type="checkbox"></td>
                <td><?php echo $items['title']; ?></td>
                <td><?php echo $items['user_name']; ?></td>
                <td><?php echo $items['category_name']; ?></td>
                <td class="text-center"><?php echo xiu_time($items['created']); ?></td>
                <td class="text-center"><?php echo xiu_match_status($items['status']);?></td>
                <td class="text-center">
                  <a href="/admin/post-add.php?post_id=<?php echo $items['id'] ?>" class="btn btn-default btn-xs">编辑</a>
                  <a href="<?php echo "/admin/posts-delete.php?id={$items['id']}&page={$page}&category={$category}&status={$status}";?>" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page='posts' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script>
    
    $(function($){
      $delete_btn=$('div.page-action>a.btn.btn-danger.btn-sm');
      $select_all=$('thead input');
      console.log($delete_btn.attr('href'));
      var check_list=[];
      $('tbody input').on('change',function(){
        $(this).prop('checked')?check_list.push($(this).val()):check_list.splice(check_list.indexOf('$(this).val()'),1);
        console.log(check_list);
        check_list.length>0?$delete_btn.fadeIn():$delete_btn.fadeOut();
        $delete_btn.attr('href','/admin/posts-delete.php/?id='+check_list);
      })
      $select_all.on('change',function(){
        $('tbody input').prop('checked',$(this).prop('checked')).trigger('change');
      })
    })
  </script>
</body>
</html>
