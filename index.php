<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/debug.css" rel="stylesheet" type="text/css">

</head>

<?php

echo "<h1 style='text-align:center;color:red;'>Welcome to realtimetrack demo system</h1>";
echo "<br>";


$navigator = array();


$navi_road = array(
		'name' => 'navi_road',
		'url' => 'road_demo',
		'display' => 'road network demo system',
		'desc' => 'null',
		);
$navigator[] = $navi_road;

$navi_online = array(
		'name' => 'navi_track',
		'url' => 'track_demo',
		'display' => 'real track demo system',
		'desc' => 'null',
		);
$navigator[] = $navi_online;

$navi_dev = array(
		'name' => 'navi_match',
		'url' => 'map_match_demo',
		'display' => 'map match track demo system',
		'desc' => 'null',
		);
$navigator[] = $navi_dev;


echo "<h3>导航:<h3>";
echo "<br>";

foreach($navigator as $navi)
{
	$linkTag = "<a href='http://".$_SERVER['HTTP_HOST']."/".$navi['url'].'/all.php'."'>".$navi['display']."</a>";
	echo $linkTag;
	echo "<br>";
}

?>

<div id="web_debug">
<?php 
	echo "Debug_Info:\n";

	echo "_SERVER:";
	echo var_dump($_SERVER);
	echo "\n";
?>
</div>

</html>

