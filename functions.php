<?php 


function xiu_get_current_user()
{
	if (!session_id()) session_start();
	if(empty($_SESSION['current_user'])){
		header('location:/admin/login.php');
		exit();
	}

	if(!isset($_SESSION['last_access'])||(time()-$_SESSION['last_access'])>6000){
		header('location:/admin/login.php');
		exit();
	}else{
		$_SESSION['last_access'] = time(); 
		return $_SESSION['current_user'];
	} 

}


function xiu_fetch_all($sql){
	$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
	if(!$conn){
		exit('连接数据库失败');
	}
	$query=mysqli_query($conn,$sql);
	if (!$query) {
		return false;
	}
	while ( $data=mysqli_fetch_assoc($query)) {
		$datas[]=$data;
	}

	mysqli_free_result($query);
	mysqli_close($conn);
	return $datas;

}


function xiu_fetch_one($sql){
	$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
	if(!$conn){
		exit('连接数据库失败');
	}
	$query=mysqli_query($conn,$sql);
	if (!$query) {
		return false;
	}
	return mysqli_fetch_assoc($query);
}



function xiu_execute($sql){
	$conn=mysqli_connect(XIU_DB_HOST,XIU_DB_USER,XIU_DB_PASS,XIU_DB_NAME);
	if(!$conn){
		exit('连接数据库失败');
	}
	$query=mysqli_query($conn,$sql);
	if (!$query) {
		return false;
	}
	$affected_rows=mysqli_affected_rows($conn);
	mysqli_close($conn);
	return $affected_rows;
}




    function post_add($feature){
      global $message;
      global $current_user;
      global $datas;
          $datas=[
       	  "slug"    	=>  empty($_POST['slug'])?'':$_POST['slug'], 
       	  "title"   	=>  empty($_POST['title'])?'':$_POST['title'], 
       	  "feature" 	=>  empty($_POST['feature'])?'':$_POST['feature'], 
       	  "created" 	=>  empty($_POST['created'])?'':$_POST['created'], 
       	  "content" 	=>  empty($_POST['content'])?'':$_POST['content'], 
       	  "status"  	=>  empty($_POST['status'])?'':$_POST['status'], 
       	  "user_id" 	=>  empty($_POST['user_id'])?'':$_POST['user_id'], 
       	  "category_id" =>  empty($_POST['category_id'])?'':$_POST['category_id']           
       	];
      if(empty($_POST)
          ||empty($_POST['title'])
          ||empty($_POST['slug'])
          ||empty($_POST['category_id'])
          // ||empty($_POST['created'])
          ||empty($_POST['status'])
          ||empty($_POST['content'])
      ){
        $message='请完整填写必要信息';       
        return ;
      }
      elseif(xiu_fetch_one(sprintf("select count(1) as num from posts where slug = '%s'",$_POST['slug']))["num"]>0) {

        $message="别名已存在，请换个别名吧";
        return ;
      }
      else{
        $title=$_POST['title'];
        $slug=$_POST['slug'];
        $category_id=$_POST['category_id'];
        $created=empty($_POST['created'])?date('Y-m-d H:i:s'):$_POST['created'];
        $created=str_replace('T',' ',$created);
        $status=$_POST['status'];
        $content=$_POST['content'];
        $user_id=$current_user['id'];

        $sql=sprintf("insert into posts values(null,'%s','%s','%s','%s','%s',0,0,'%s',%d,%d)" ,
        $slug,
        $title,
        $feature,
        $created,
        $content,
        $status,
        $user_id,
        $category_id
        );

        // echo $sql;
        $raws=xiu_execute($sql);
        if($raws>0){
          $message='文章添加成功！';
        }
        else{
          $message='上传数据库失败！';
        }

      }
    }

    function post_save($feature,$post_id){
      global $message;
      global $current_user;
      global $datas;

          $datas=[
       	  "slug"    	=>  empty($_POST['slug'])?'':$_POST['slug'], 
       	  "title"   	=>  empty($_POST['title'])?'':$_POST['title'], 
       	  "feature" 	=>  empty($_POST['feature'])?'':$_POST['feature'], 
       	  "created" 	=>  empty($_POST['created'])?'':$_POST['created'], 
       	  "content" 	=>  empty($_POST['content'])?'':$_POST['content'], 
       	  "status"  	=>  empty($_POST['status'])?'':$_POST['status'], 
       	  "user_id" 	=>  empty($_POST['user_id'])?'':$_POST['user_id'], 
       	  "category_id" =>  empty($_POST['category_id'])?'':$_POST['category_id']           
       	];
      $current_slug_id=xiu_fetch_one(sprintf("select id from posts where slug = '%s'",$_POST['slug']))["id"];
      if(empty($_POST)
          ||empty($_POST['title'])
          ||empty($_POST['slug'])
          ||empty($_POST['category_id'])
          // ||empty($_POST['created'])
          ||empty($_POST['status'])
          ||empty($_POST['content'])
      ){
        $message='请完整填写必要信息';       
        return ;
      }
      elseif(!empty($current_slug_id)&&$current_slug_id!=$post_id) {
        $message="别名已存在，请换个别名吧";
        return ;
      }
      else{
        $title=$_POST['title'];
        $slug=$_POST['slug'];
        $category_id=$_POST['category_id'];
        $created=empty($_POST['created'])?date('Y-m-d H:i:s'):$_POST['created'];
        $created=str_replace('T',' ',$created);
        $status=$_POST['status'];
        $content=$_POST['content'];
        $user_id=$current_user['id'];

        if(empty($feature)||$feature===' '){
        	$sql=sprintf("update posts set 
        		slug='%s',
        		title='%s',
        		created='%s',
        		content='%s',
        		status='%s',
        		user_id=%d,       		
        		category_id=%d
        		where id=%s",
        		$slug,
        		$title,
        		$created,
        		$content,
        		$status,
        		$user_id,
        		$category_id,
        		$post_id
        	);
        }else{
        	$sql=sprintf("update posts set 
        		slug='%s',
        		title='%s',
        		feature='%s',
        		created='%s',
        		content='%s',
        		status='%s',
        		user_id= %d,
        		category_id= %d 
        		where id=%s",
        		$slug,
        		$title,
        		$feature,
        		$created,
        		$content,
        		$status,
        		$user_id,
        		$category_id,
        		$post_id
        	);
        }
	
        // echo $sql;
        $raws=xiu_execute($sql);
        if($raws>0){
          $message='文章修改成功！';
        }
        else{
          $message='上传数据库失败！';
        }

      }
    }


    function image_save(){
      global $message;
      if(empty($_FILES['feature'])){
        $message="图像上传失败！";
        return ' ';
      }
      $feature=$_FILES['feature'];
      if(empty($feature["name"])) {
        $message= "图像上传失败！";
        return ' ';
      }
      if($feature["error"]!=0) {
        $message= "特色图像上传失败";
        return false;
      }
      $tmp_name=$feature['tmp_name'];
      $pathStr=XIU_DIR.'static/upload/';
      $pathStr .= date( "Ymd" );
      if ( !file_exists( $pathStr ) ) {
        if ( !mkdir( $pathStr , 0777 , true ) ) {
          $message= "创建上传目录失败";
          return false;
        }
      }
      $seed=substr(rand(),3);
      $pathStr.='/'.$seed.$feature['name'];
      if(!move_uploaded_file($tmp_name, $pathStr)){
          $message= "特色图像保存失败 ";
          return false;
      }
      return $pathStr;
    }





