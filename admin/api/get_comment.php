<?php 
  require_once('../../config.php');
  require_once XIU_DIR.'functions.php';
  $row=10;
  $page=empty($_GET['page'])?1:$_GET['page'];
  $total_pages=ceil(xiu_fetch_one("select count(1) as total_pages from comments inner join posts  on comments.post_id = posts.id")['total_pages']/$row);
  $page=$page>$total_pages?$total_pages:$page;
  $page=$page>1?$page:1;
  $start=($page-1)*$row;
  $sql=sprintf("select 
  	comments.id, 
  	comments.author,
	comments.content,
	comments.created,
	comments.parent_id,
	posts.title,
	comments.status
   from comments
   inner join posts  on comments.post_id = posts.id
   order by comments.created desc
   limit %d,%d",$start,$row);
  $datas=xiu_fetch_all($sql);
  header("Content-Type:application/json");
  echo json_encode(array(
  	'start'=>$start,
  	'datas'=>$datas,
  	'totalPages'=>$total_pages,
    'page'=>$page
  ));


