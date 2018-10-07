<?php 
   require_once('../config.php');
   require_once XIU_DIR.'functions.php';
   xiu_get_current_user();




 ?>





<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Navigation menus &laquo; Admin</title>
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
        <h1>导航菜单</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div id='err-message' class="alert " style='display: none'>
        <<strong>注意！</strong>
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新导航链接</h2>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="title">标题</label>
              <input id="title" class="form-control" name="title" type="text" placeholder="标题">
            </div>
            <div class="form-group">
              <label for="href">链接</label>
              <input id="href" class="form-control" name="href" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <a href="javascript:;" class="btn btn-primary" id='btn-save' type="submit">添加</a>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id='all_delete' class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>文本</th>
                <th>标题</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody></tbody>
            <script type='text/jsrender' id="menus_tmpl">
              {{for menus}}
              <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><i class="{{:icon}}"></i>{{:text}}</td>
                <td>{{:title}}</td>
                <td>{{:link}}</td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              {{/for}}
            </script>
          </table>
        </div>
      </div>
    </div>
  </div>
  <?php $current_page='nav_menus' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script>NProgress.done()</script>

  <script type="text/javascript">
    //用ajax的get和post区分是取数据还是上传数据
    function load_data(callback){
      $.get("./api/menus.php",{key:'nav_menus'},function(res){
      callback(res);
    })
    }
    function upload_data(values,callback){
      $.post("./api/menus.php",{key:'nav_menus',value:values},function(res){
      console.log(res);
      callback(res);
    })
    }
    function fresh(res){
      var html=$('#menus_tmpl').render({menus:res});
      $('tbody').html(html);
    }    
    load_data(fresh);
  </script>

    <script>
      //为删除按钮注册点击事件
    $('tbody').on('click','a.btn-danger',function(){
      var index=$(this).parent().parent().index();
      // console.log(index);
      load_data(function(res){
        res.splice(index,1);        
        // console.log(JSON.stringify(res));
        upload_data(JSON.stringify(res),function(raw){
          if (raw>0){
            $('#err-message').css('display',' block').addClass('alert-success').text('删除成功');
          }else{
            $('#err-message').css('display',' block').addClass('alert-danger').text('删除失败');
          }
        });
        load_data(fresh);
      });
    });

      //为添加按钮注册点击事件
      $('#btn-save').on('click',function(){
        var menu={icon:"fa fa-glass"  ,text: $('#text').val(), title: $('#title').val(), link: $('#href').val()};
        load_data(function(res){
          res.push(menu);
          // console.log(res);
          upload_data(JSON.stringify(res),function(raw){
          if (raw>0){
            $('#err-message').css('display',' block').removeClass('alert-danger').addClass('alert-success').text('添加成功');
          }else{
            $('#err-message').css('display',' block').removeClass('alert-success').addClass('alert-danger').text('添加失败');
          }
          });
          fresh(res);
        })
      });
      var checkList=[];

      //为表内checkbox注册事件（以委托方式）
      $('tbody').on('change','input',function(){
        var index=$(this).parent().parent().index();
        $(this).prop('checked')?checkList.indexOf(index)==-1&&checkList.push(index):checkList.splice(checkList.indexOf(index),1);
        console.log(checkList);
        checkList.length>0?$('#all_delete').css('display','inline'):$('#all_delete').css('display','none');
      })

      //表头的check注册事件
      $('thead input').on('change',function(){
        $('tbody input').prop('checked',$(this).prop('checked')).change();
      })
      //为批量删除按钮注册事件
      $('#all_delete').on('click',function(){
        load_data(function(res){
          var res1=[];
          for(var i in res){
            if(checkList.indexOf(parseInt(i))==-1){
              res1.push(res[i]);
            };
          }
          console.log(res1);
          upload_data(JSON.stringify(res1),function(raw){
          if (raw>0){
            $('#err-message').css('display',' block').removeClass('alert-danger').addClass('alert-success').text('删除成功');
          }else{
            $('#err-message').css('display',' block').removeClass('alert-success').addClass('alert-danger').text('删除失败');
          }
        });
        checkList=[];
        $('#all_delete').css('display','none');
        load_data(fresh);
        })
      })

  </script>

</body>
</html>
