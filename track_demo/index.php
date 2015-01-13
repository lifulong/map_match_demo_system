<?php
require_once('source.php');
$data=new source($_GET['file']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>真实轨迹展示</title>
	<style type="text/css">
	body, html,#allmap {width: 100%;height: 100%;overflow: hidden;margin:0;font-family:"微软雅黑";}
	</style>
	<link href="../js/jquery-ui.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="../js/jquery-2.1.1.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=F9bc1ccf4e86179b1a423f8dc0faf8b2"></script>
</head>
<body>
	<h1>实时轨迹查询</h1>
	红线和紫线是实时轨迹，绿线蓝线是所有定位点<br/>
	<div id="allmap"></div>
</body>
</html>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");    // 创建Map实例
    map.centerAndZoom(new BMap.Point(116.404, 39.915), 11);  // 初始化地图,设置中心点坐标和地图级别
    map.addControl(new BMap.MapTypeControl());   //添加地图类型控件
    var opts = {anchor: BMAP_ANCHOR_TOP_RIGHT, offset: new BMap.Size(40, 40)};
    map.addControl(new BMap.NavigationControl(opts));
    map.enableScrollWheelZoom();
    map.addControl(new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT}));
    var point,marker,label,dis=0.0,pointA,pointB;
    var arrBaiduPoint = new Array();
    var arrTencentPoint = new Array();
    var myIcon,marker;
    //map.setCurrentCity("北京");          // 设置地图显示的城市 此项是必须设置的
	//endTime 格式 2014-10-11 20:00:00
	function transDate(endTime){
		var date=new Date();
		date.setFullYear(endTime.substring(0,4));
		date.setMonth(endTime.substring(5,7)-1);
		date.setDate(endTime.substring(8,10));
		date.setHours(endTime.substring(11,13));
		date.setMinutes(endTime.substring(14,16));
		date.setSeconds(endTime.substring(17,19));
		return Date.parse(date)/1000;
	}
	function showPoints(arrPoints,color){
		console.log("just test");
		var arrRealPoints = new Array();
		var len=arrPoints.length;
		console.log("len="+len);
		var label;
		if(color=='red'){
		    for(var i=1;i<len-1;++i){
			label = new BMap.Label(i, {position:arrPoints[i],offset:new BMap.Size(0,0)}); 
			map.addOverlay(label);
			var pt = new BMap.Point(arrPoints[i].lng, arrPoints[i].lat);
			var myIcon = new BMap.Icon("./16.png", new BMap.Size(16,16));
			var marker2 = new BMap.Marker(pt,{icon:myIcon});  // 创建标注
			map.addOverlay(marker2);  
		    }
		}
		var j=-1;
		for(var i=0;i!=len;++i){
			if(i>0&&j>=0){
				if(false){
					var polyline = 
						new BMap.Polyline(arrRealPoints, 
						{strokeColor:color, strokeWeight:3, strokeOpacity:0.5});
					map.addOverlay(polyline);
					arrRealPoints=new Array();
					j=-1;
				}
			}
			arrRealPoints.push(arrPoints[i]);
			j=i;
    	}
		if(arrRealPoints.length>=2){
			var polyline = new BMap.Polyline(arrRealPoints, 
				{strokeColor:color, strokeWeight:3, strokeOpacity:0.5});
			map.addOverlay(polyline);
		}
	}	

</script>
<?php

function registerScript($script){
	echo "<script type='text/javascript'>$script</script>";
}
		$eps=1e-6;
		$arrAllPoints=array();
		$sum_lng=0.0;
		$sum_lat=0.0;
		$baidu_cnt=0;
		$firstPoint=null;
		$lastPoint=null;
		echo "<script type='text/javascript'>console.log('test".count($data->positions)."')</script>";
		foreach($data->positions as $point){
			if($point['gps_type']=='baidu'){
				if($point['lng']>$eps && $point['lat']>$eps){
					++$baidu_cnt;
					$sum_lng+=$point['lng'];
					$sum_lat+=$point['lat'];
					if($firstPoint===null){
						$firstPoint=$point;
					}
					$lastPoint=$point;
				}
			}
			$arrAllPoints[]=json_encode($point);
		}

		$ratio=array(50,100,200,500,1000,2000,5000,10000,20000,25000,50000,100000,200000,500000,1000000,2000000);
		$distance=source::calDistance($firstPoint,$lastPoint);
		$ratio_level=0;
		for($ratio_level=0;$ratio_level<count($ratio);++$ratio_level){
			if($distance<10*$ratio[$ratio_level]){
				break;
			}
		}
		$level=18-$ratio_level;
		if($baidu_cnt>0){
			registerScript("map.centerAndZoom(new BMap.Point($sum_lng/$baidu_cnt,$sum_lat/$baidu_cnt),$level);");
		}

		echo "<script type='text/javascript'>console.log('before point check')</script>";

		if($firstPoint != null)
			registerScript('var firstIcon = new BMap.Icon("http://" + location.hostname + "/tools/guiji/start.png", new BMap.Size(33, 44), { offset: new BMap.Size(0, 0), imageOffset: new BMap.Size(0, 0)});  marker = new BMap.Marker('.json_encode($firstPoint).', {icon: firstIcon}); map.addOverlay(marker); ');
		if($lastPoint != null)
			registerScript('var lastIcon = new BMap.Icon("http://" + location.hostname + "/tools/guiji/end.png", new BMap.Size(33, 44), { offset: new BMap.Size(0, 0), imageOffset: new BMap.Size(0, 0)});  marker = new BMap.Marker('.json_encode($lastPoint).', {icon: lastIcon}); map.addOverlay(marker); ');
		$strAllJsArray=implode(',',$arrAllPoints);
		registerScript("showPoints(new Array($strAllJsArray),'red');");

?>

