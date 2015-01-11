<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>真实轨迹</title>
    <style type="text/css">
    body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
    </style>
    <link href="../jquery-ui-1.11.2.custom/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="../src/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="../jquery-ui-1.11.2.custom/jquery-ui.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=F9bc1ccf4e86179b1a423f8dc0faf8b2"></script>
</head>
<body>
<br/><br/>

<?php
function getAllFiles($dir_path){
	$result=array();
	$current_dir=opendir($dir_path);
	while(($file=readdir($current_dir))!==false){
		if($file=='.'||$file=='..'){
			continue;
		}
		$result[]=$file;
	}
	return $result;
}
function registerScript($script){
    echo "<script type='text/javascript'>$script</script>";
}

if(isset($_GET['dir']))
	$path=$_GET['dir'].'/';
else
	$path='/data/raw/';

$result=getAllFiles($path);

foreach($result as $file){
	if(is_file($path.$file))
        	$linkTag="&nbsp;&nbsp;&nbsp<a target='_blank' href='http://".$_SERVER['HTTP_HOST'].'/realtimetrack_newstrategy/?file='.$path.$file."'>".$file."</a>";
	else if(is_dir($path.$file))
        	$linkTag="&nbsp;&nbsp;&nbsp<a target='_blank' href='http://".$_SERVER['HTTP_HOST'].'/realtimetrack_newstrategy/all.php?dir='.$path.$file."'>".$file."</a>";
	echo $linkTag."<br/>";
}
?>

</body>
