<?php 
  require_once('../config.php');
  require_once XIU_DIR.'functions.php';
  xiu_get_current_user();
 ?>



<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th width="50">状态</th>
            <th class="text-center" width="135">操作</th>
          </tr>
        </thead>

        <tbody id="comment_body" body_page="1">
        </tbody>
      </table>
    </div>
  </div>
  <?php $current_page='comments' ; ?>
  <?php include './inc/aside.php' ; ?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>NProgress.done()</script>
  <script src='/static/assets/vendors/jsrender/jsrender.js'></script>
  <script src='/static/assets/vendors/twbs-pagination/jquery.twbsPagination.js'></script>
  <script type='text/jsrender' id="comment_temp">
      {{for datas}}
      <tr class={{if status=='approved'}} "info" {{else status=='rejected'}} 'danger' {{else}} 'warning' {{/if}}>
        <td class="text-center"><input type="checkbox" check_id={{:id}}></td>
        <td>{{: author}}</td>
        <td>{{: content}}</td>
        <td>{{: title}}</td>
        <td>{{: created}}</td>
        <td>{{if status=='approved'}} 准许 {{else status=='rejected'}} 拒绝 {{else}} 待审 {{/if}}</td>
        <td class="text-center" button_id='{{:id}}'>
          {{if status!='approved'}} <a href="javascript:;" class="btn btn-info btn-xs">批准</a> {{/if}}
          {{if status!='rejected'}} <a href="javascript:;" class="btn btn-warning btn-xs">驳回</a> {{/if}}
           <a href="javascript:;" class="btn btn-danger btn-xs" >删除</a> 
        </td>
      </tr>
      {{/for}}
  </script>
  <script type="text/javascript">
    var num_id=[];
    function get_comment(page){
      $('.btn-batch').attr('page_id',page);
      $.get("api/get_comment.php",{page:page},function(data){
        // ==根据ajax请求返回的data利用模板渲染tbody==
        var html = $('#comment_temp').render(data);
        $('#comment_body').html(html);
        //==初始化分页，并为分页注册点击事件==
        // console.log(data.totalPages);
        // console.log(typeof(data.page));
        // console.log(typeof(page));
        // page=page<data.totalPages?page:data.totalPages;
        // page=data.page;
        $('ul.pagination').twbsPagination('destroy');
        $('ul.pagination').twbsPagination({
          totalPages: data.totalPages,
          visiblePages: 5,
          first: '&laquo&laquo',
          prev: '&laquo',
          next: '&raquo',
          last: '&raquo&raquo',
          startPage:parseInt(data.page),
          initiateStartPageClick: false ,
          onPageClick: function (event, page1) {
            // console.log(page1);
            get_comment(page1);
          }
        });

        $('tbody input').on('change',function(){
          $(this).prop("checked")?num_id.push($(this).attr('check_id')):num_id.splice(num_id.indexOf($(this).attr('check_id')),1);
          // console.log(num_id);
          num_id.length>0?$('.btn-batch').css('display','inline'):$('.btn-batch').css('display','none');
        });
      })
    }

    get_comment(1);

    

   
  </script>
  <script>

        //=============删除按钮=====================
    $('tbody').on('click','a.btn-danger',function(){
          var deleteId=$(this).parent().attr('button_id');
          $.get("./api/delete_comment.php",{id:deleteId},function(res){
        // console.log(res);
        if(res){
          get_comment($('.btn-batch').attr('page_id'));
        }
      });
        });
    //=============批准按钮=====================
    $('tbody').on('click','a.btn-info',function(){
      var approveId=$(this).parent().attr('button_id');
      $.get("./api/approve_comment.php",{id:approveId},function(res){
        // console.log(res);
        if(res){
          get_comment($('.btn-batch').attr('page_id'));
        }
      });
    });
    //=============拒绝按钮=====================
    $('tbody').on('click','a.btn-warning',function(){
      var rejectId=$(this).parent().attr('button_id');
      $.get("./api/reject_comment.php",{id:rejectId},function(res){
        // console.log(res);
        if(res){
          get_comment($('.btn-batch').attr('page_id'));
        }
      });
    });

        //==为表头checkbox注册change事件==
    $('thead input').on('change',function(){
      var theadStatus=$(this).prop("checked");
      $('tbody input').prop("checked",theadStatus);
      $('tbody input').change();
    });
        //==为表头批量删除按钮注册change事件==
    $('.btn-batch  .btn-danger').on('click',function(){
      $.get("./api/delete_comment.php",{id:num_id.join(",")},function(res){
        // console.log(res);
        $('thead input').prop('checked',false);
        $('.btn-batch').css('display','none');
        num_id=[];
        if(res){

          get_comment($('.btn-batch').attr('page_id'));
        }
      });
    })  
            //==为表头批量批准按钮注册change事件==
    $('.btn-batch  .btn-info').on('click',function(){
      $.get("./api/approve_comment.php",{id:num_id.join(",")},function(res){
        // console.log(res);
        $('thead input').prop('checked',false);
        $('.btn-batch').css('display','none');
        num_id=[];
        if(res){
          get_comment($('.btn-batch').attr('page_id'));
        }
      });
    }) 
                //==为表头批量拒绝按钮注册change事件==
    $('.btn-batch  .btn-warning').on('click',function(){
      $.get("./api/reject_comment.php",{id:num_id.join(",")},function(res){
        // console.log(res);
        $('thead input').prop('checked',false);
        $('.btn-batch').css('display','none');
        num_id=[];
        if(res){
          get_comment($('.btn-batch').attr('page_id'));
        }
      });
    }) 
  </script>

</body>
</html>





