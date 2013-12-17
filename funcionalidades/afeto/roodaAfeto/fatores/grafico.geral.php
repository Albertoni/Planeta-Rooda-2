<?php
$EA = $_GET;

$EA[0] = 0;		//'anula' indefinido


/*---	normalizacao de tamanhos para a impressao correta	---*/
$nsize = array();
foreach($EA as $animo=>$int){
	$int = round($int*1000)/1000;
	$nsize[$animo]	=	$int;
}	$size = $nsize[2];	$nsize[2]=$nsize[4];	$nsize[4]=$size;

$ordem = $nsize;
	arsort($ordem);	$ordem = array_keys($ordem);



header("Content-type: image/png");

$size	=	400;
$rmax	=	$size/2;

$im		=	imagecreate($size,$size);

$cor	=	array(	
				'black'	=>	ImageColorAllocate($im,0,0,0),
				'gray'	=>	ImageColorAllocate($im,200,200,200),
				'0'	=>	ImageColorAllocate($im,255,255,255),
				'1'	=>	ImageColorAllocate($im,172,127,16),
				'2'	=>	ImageColorAllocate($im,64,130,62),
				'3'	=>	ImageColorAllocate($im,78,99,140),
				'4'	=>	ImageColorAllocate($im,142,64,68),
			);
$g = $cor['gray'];
$b = $cor['0'];

function arc_quad($quad){
	global $im,$size,$nsize,$cor,$rd;
	ImageFilledArc(	$im,
					($size/2),($size/2),
					(0.9*$nsize[$quad]*$size),(0.9*$nsize[$quad]*$size),
					$rd[$quad][0],$rd[$quad][1],		$cor[$quad], IMG_ARC_PIE);/*---	Q0	---*/
}
$rd = array(	0 => array(360,0),
				1 => array(270,360),
				2 => array(0,90),
				3 => array(90,180),
				4 => array(180,270));

for($i=0;$i<=4;$i++){	//desenha os quadrantes todos
	arc_quad($ordem[$i]);
}

/*---	separadores	---*/
ImageDashedLine(	$im,
				(0.0*$size/2),($size/2),
				(2*$size/2),($size/2),
			ImageColorAllocate($im,0,248,255)
);
ImageDashedLine(	$im,
				($size/2),(0.0*$size/2),
				($size/2),(2*$size/2),
			ImageColorAllocate($im,0,248,255)
);

$style = array ($g,$g,$g,$g,$g,$b,$b,$b,$b,$b);
imagesetstyle ($im, $style);
imagearc($im,$size/2,$size/2,0.9*$size,0.9*$size,0,360,IMG_COLOR_STYLED);

ImagePNG($im);
ImageDestroy($im);
/*	*/
?>