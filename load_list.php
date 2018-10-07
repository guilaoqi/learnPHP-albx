<?php 
  require_once('config.php');
  require_once XIU_DIR.'functions.php';

  $sql="
  	  select 
      posts.id,
      posts.title,
      posts.created,
      posts.`status`,
      posts.content,
      categories.`name` as category_name,
      users.nickname as user_name
      from posts
      inner join categories on posts.category_id = categories.id
      inner join users on posts.user_id = users.id
      where categories.name='web-js'
      order by posts.created desc
      limit 0,10
      ";

  $datas=xiu_fetch_all($sql);
  header("Content-Type:application/json");
  echo json_encode($datas);
