<?php
class Source {

	const DEF_PI180 = 0.01745329252;
	const DEF_R = 6370693.5;
	public $arrAll=array();
	public function __construct($filename){
		$this->queue=new CQueue();
		$content=file_get_contents($filename);
		$arrAll=json_decode($content);
	}

	public static function getDistance($loc1,$loc2){

		if($loc1==null||$loc2==null||!self::isValidLocation($loc1)||!self::isValidLocation($loc2)){
			return null;
		}
		return self::getLongDistance((double)$loc1->lng,(double)$loc1->lat,(double)$loc2->lng,(double)$loc2->lat);
	}

	public static function isValidLocation($loc){
		return $loc!=null&&abs($loc->lat)<=90&&abs($loc->lat)>0&&abs($loc->lng)<=180&&abs($loc->lng)>0;
	}

	public static function getLongDistance($lon1, $lat1, $lon2, $lat2) {
		// 角度转换为弧度
		$ew1 = $lon1 * self::DEF_PI180;
		$ns1 = $lat1 * self::DEF_PI180;
		$ew2 = $lon2 * self::DEF_PI180;
		$ns2 = $lat2 * self::DEF_PI180;
		// 求大圆劣弧与球心所夹的角(弧度)
		$distance = sin($ns1) * sin($ns2) + cos($ns1)
			* cos($ns2) * cos($ew1 - $ew2);
		// 调整到[-1..1]范围内，避免溢出
		if ($distance > 1.0) {
		    $distance = 1.0;
		} else if ($distance < -1.0) {
		    $distance = -1.0;
		}
		// 求大圆劣弧长度
		$distance = self::DEF_R * acos($distance);
        	return $distance;
   	}

}

?>

