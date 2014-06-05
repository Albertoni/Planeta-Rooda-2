<?php
require_once("cor.fatores.php");
require_once("limites.php");
//header("Content-type: image/png");

	$subf	= $_GET['subf'];		$fator	= $_GET['fator'];		$val	= $_GET['val'];
	
	$data = array();
	$j = 0;
	for($i=0;$i<count($subf);$i++){
		$data[$subf[$i]][$fator[$i]] = $val[$i];
	}
	switch($_GET['tp']){
		case 'for':
			$l[0] = array(	'na' =>	$data['na'],	'nv' =>	$data['nv'],	'fp' =>	$data['fp']);
			$l[1] = array(	'mpf' =>	$data['mpf'],	'mpc' =>	$data['mpc']);
			$l[2] = array(	'ms' =>	$data['ms'],	'to' =>	$data['to']);
				$l = $l[	$_GET['ll']	];
			break;
		case 'btp':
			$l[0] = array(	'fp' =>	$data['fp']);
				$l = $l[	$_GET['ll']	];
			break;
		case 'ddb':
			$l[0] = array(	'fp' =>	$data['fp']);
				$l = $l[	$_GET['ll']	];
			break;
		default:
			break;
	}
	print_r($l);
?>