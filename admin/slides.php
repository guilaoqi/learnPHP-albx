<?php 
  require_once('../config.php');
  require_once XIU_DIR.'functions.php';
  xiu_get_current_user();
 ?>





<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Slides &laquo; Admin</title>
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
        <h1>图片轮播</h1>
      </div>
      <!-- 有错误信息时展示 -->
<!--       <div  class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新轮播内容</h2>
            <div class="form-group">
              <label for="image">图片</label>
              <!-- show when image chose -->
              <img class="help-block thumbnail" style="display: none">
              <input id="image" class="form-control" name="image" type="file">
              <input id="image1" name="image" type="hidden">
            </div>
            <div class="form-group">
              <label for="text">文本</label>
              <input id="text" class="form-control" name="text" type="text" placeholder="文本">
            </div>
            <div class="form-group">
              <label for="link">链接</label>
              <input id="link" class="form-control" name="link" type="text" placeholder="链接">
            </div>
            <div class="form-group">
              <a href="javascript:;" id='add_slide' class="btn btn-primary" type="submit">添加</a>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id='dele_all' class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th class="text-center">图片</th>
                <th>文本</th>
                <th>链接</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody></tbody>
            <script type=text/jsrender id='js_tmpl'>
              {{for slide}}
              <tr>
              <td class="text-center"><input type="checkbox"></td>
              <td class="text-center"><img class="slide" src="{{:image}}"></td>
              <td>{{:text}}</td>
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
  <?php $current_page='slides' ;?>
  <?php include './inc/aside.php' ; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/jsrender/jsrender.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>


  <script>
    function get_slide(callback){
      $.get('./api/menus.php',{key:'home_slides'},function(res){
      callback(res);

    })
    }
    function upload_slide(value,callback){
      $.post('./api/menus.php',{key:'home_slides',value:value},function(res){
        callback(res);
      })
    }
    function init_slide(res){
     var html= $('#js_tmpl').render({slide:res});
     $('tbody').html(html);
    }
    get_slide(init_slide);

  </script>
  <script>
    $('#image').on('change',function(){
      var file=this.files[0]
      // console.dir(file);
      var data= new FormData();
      data.append('feature',file);
      xhr=new XMLHttpRequest();
      xhr.open('post','./api/receive_img.php');
      xhr.send(data);
      xhr.onload=function(){
      var path=JSON.parse(xhr.responseText).path;
      path=path.substr(12);
      // console.log(path);
      $("img.help-block").css('display','block').attr('scr',path);
      $('#image1').val(path);
      }
    })
    
    $('#add_slide').on("click",function(){
      var datas={
        image: $('#image1').val(),
        text: $('#text').val(),
        link: $('#link').val()
      }
      get_slide(function(res){
        res.push(datas);
        // console.log(res);
        upload_slide(JSON.stringify(res),function(resu){
          get_slide(init_slide);
        })
      })
    })

    $('tbody').on('click','a',function(){
      var index=$(this).parent().parent().index();
      get_slide(function(res){
        res.splice(index,1);
        upload_slide(JSON.stringify(res),function(resu){
        get_slide(init_slide);
        })
      })
    })
    var checkList=[];
    $('tbody').on('change','input',function(){
      var index=$(this).parent().parent().index();
      $(this).prop('checked')?(checkList.indexOf(index)==-1)&&checkList.push(index):(checkList.indexOf(index)!=-1)&&checkList.splice(checkList.indexOf(index),1);
      console.log(checkList);
      checkList.length>0?$('#dele_all').css('display','inline'):$('#dele_all').css('display','none')
    })
    $('thead').on('change','input',function(){
      var status=$(this).prop('checked');
      $('tbody input').prop('checked',status).change();
    })
    $('#dele_all').on('click',function(){
      get_slide(function(res){
        var res1=[];
        for(var i in res){
        if(checkList.indexOf(parseInt(i))==-1){
          res1.push(res[i]);
        };
      };
      console.log(res1);
      upload_slide(JSON.stringify(res1),function(){
      get_slide(init_slide);
        });
      })
    })
  </script>
</body>
</html>
