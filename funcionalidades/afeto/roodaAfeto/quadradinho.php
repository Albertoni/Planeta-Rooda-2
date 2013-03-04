<?php
$cor[0][0] =	'FFFFFF';
$cor[1][1]	=	'F6E011';
$cor[1][2]	=	'F7B617';
$cor[1][3]	=	'CB942B';
$cor[1][4]	=	'9E752B';
$cor[2][1]	=	'934C76';
$cor[2][2]	=	'C04F72';
$cor[2][3]	=	'D35455';
$cor[2][4]	=	'D8532E';
$cor[3][1]	=	'7975B5';
$cor[3][2]	=	'5982AF';
$cor[3][3]	=	'6598B7';
$cor[3][4]	=	'58A3AE';
$cor[4][1]	=	'ADD14E';
$cor[4][2]	=	'79C156';
$cor[4][3]	=	'39B45B';
$cor[4][4]	=	'00B17B';

$cc = $cor[3][3];

if(isset($_GET['q']) && isset($_GET['s'])){
	if($_GET['q']>=0 && $_GET['q']<=4 && $_GET['s']>=0 && $_GET['s']<=4){
		$cc = $cor[$_GET['q']][$_GET['s']];
	}
} else if(isset($_GET['gambiarra'])){
	$cc = $_GET['gambiarra'];
} else if(isset($_GET['awyeah'])){
	$quad = strtolower($_GET['awyeah']);
	switch($quad){
		case 'satisfeito':		$cc =	dechex(172).dechex(127).dechex(16);
								break;
		case 'insatisfeito':	$cc =	dechex(142).dechex(64).dechex(68);
								break;
		case 'desanimado':		$cc =	dechex(78).dechex(99).dechex(140);
								break;
		case 'animado':			$cc =	dechex(64).dechex(130).dechex(62);
								break;
		case 'indefinido':		
		default:				break;
	}
}

$ci[0] = hexdec(substr($cc,0,2));
$ci[1] = hexdec(substr($cc,2,2));
$ci[2] = hexdec(substr($cc,4,2));

	header ("Content-type: image/png");
		$c = ImageCreate(50,50);
			ImageColorAllocate($c,$ci[0],$ci[1],$ci[2]);
	ImagePNG($c);
	ImageDestroy($c);

?>