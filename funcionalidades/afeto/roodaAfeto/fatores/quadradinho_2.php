<?php
header ("Content-type: image/png");
	$c = ImageCreate(50,50);
	switch($_GET['crc']){
		case 'confianca':	
		case 'c':		
					ImageColorAllocate($c,211,55,73);	break;
		case 'esforco':				
		case 'e':	
					ImageColorAllocate($c,0,156,0);	break;
		case 'independencia':	
		case 'i':	
					ImageColorAllocate($c,63,139,226);	break;
		default:	
					ImageColorAllocate($c,$_GET['r'],$_GET['g'],$_GET['b']);	break;
	}
	ImagePNG($c);
	ImageDestroy($c);
?>