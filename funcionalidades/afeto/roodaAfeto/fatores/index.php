<?php
include_once("analise.geral.php");
include_once("geral.php");
include_once("forum.php");
include_once("ddb.php");
include_once("batepapo.php");
include_once("tempo.php");
include_once("valores.data.php");
if(!isset($able))
	include_once('../able.php');

$fatores = 		//guarda as informacoes pertinentes a analise do banco de dados
	array(	'confiança' => 0,
			'esforço' => 0,
			'independência' =>0);
?>
<?php
$export = array();
/*---------------------------------	analise FORUM	------------------------*/
// if($acessos_forum){						//media de acessos ao forum
	// $fatores['confiança']		+=	$na_forum_acessos_true['confiança'];
	// $fatores['esforço']			+=	$na_forum_acessos_true['esforço'];		
	// $fatores['independência']	+=	$na_forum_acessos_true['independência'];
	// $export['for.na'] = array	(	'confiança'		=>	$na_forum_acessos_true['confiança'],
									// 'esforço'		=>	$na_forum_acessos_true['esforço'],
									// 'independência'	=>	$na_forum_acessos_true['independência']);
	// }
// else{
	// $fatores['confiança']		+=	$na_forum_acessos_false['confiança'];
	// $fatores['esforço']			+=	$na_forum_acessos_false['esforço'];		
	// $fatores['independência']	+=	$na_forum_acessos_false['independência'];
	// $export['for.na'] = array	(	'confiança'		=>	$na_forum_acessos_false['confiança'],
									// 'esforço'		=>	$na_forum_acessos_false['esforço'],
									// 'independência'	=>	$na_forum_acessos_false['independência']);
	// }

if($visitas_forum){						//media de mensagens vistas no forum
	$fatores['confiança']		+=	$nv_forum_visitas_true['confiança'];
	$fatores['esforço']			+=	$nv_forum_visitas_true['esforço'];		
	$fatores['independência']	+=	$nv_forum_visitas_true['independência'];
	$export['for.nv'] = array	(	'confiança'		=>	$nv_forum_visitas_true['confiança'],
									'esforço'		=>	$nv_forum_visitas_true['esforço'],
									'independência'	=>	$nv_forum_visitas_true['independência']);
	}
else{
	$fatores['confiança']		+=	$nv_forum_visitas_false['confiança'];
	$fatores['esforço']			+=	$nv_forum_visitas_false['esforço'];		
	$fatores['independência']	+=	$nv_forum_visitas_false['independência'];
	$export['for.nv'] = array	(	'confiança'		=>	$nv_forum_visitas_false['confiança'],
									'esforço'		=>	$nv_forum_visitas_false['esforço'],
									'independência'	=>	$nv_forum_visitas_false['independência']);
}
	

switch($freq_forum){						//frequencia de participacao no forum
	case 4:			case 3:			case 2:			case 1:
			$fatores['confiança']		+=	$fp_forum[$freq_forum]['confiança'];
			$fatores['esforço']			+=	$fp_forum[$freq_forum]['esforço'];		
			$fatores['independência']	+=	$fp_forum[$freq_forum]['independência'];
			$export['for.fp'] = array	(	'confiança'		=>	$fp_forum[$freq_forum]['confiança'],
											'esforço'		=>	$fp_forum[$freq_forum]['esforço'],
											'independência'	=>	$fp_forum[$freq_forum]['independência']);
		break;
	case 0:
	default:
			$fatores['confiança']		+=	$fp_forum[0]['confiança'];
			$fatores['esforço']			+=	$fp_forum[0]['esforço'];		
			$fatores['independência']	+=	$fp_forum[0]['independência'];
			$export['for.fp'] = array	(	'confiança'		=>	$fp_forum[0]['confiança'],
											'esforço'		=>	$fp_forum[0]['esforço'],
											'independência'	=>	$fp_forum[0]['independência']);
		break;
}

if($resp_formador){						//respondeu a um formador
	$fatores['confiança']		+=	$mp_forum_formador_true['confiança'];
	$fatores['esforço']			+=	$mp_forum_formador_true['esforço'];		
	$fatores['independência']	+=	$mp_forum_formador_true['independência'];
	$export['for.mpf'] = array	(	'confiança'		=>	$mp_forum_formador_true['confiança'],
									'esforço'		=>	$mp_forum_formador_true['esforço'],
									'independência'	=>	$mp_forum_formador_true['independência']);
	}
else{
	$fatores['confiança']		+=	$mp_forum_formador_false['confiança'];
	$fatores['esforço']			+=	$mp_forum_formador_false['esforço'];		
	$fatores['independência']	+=	$mp_forum_formador_false['independência'];
	$export['for.mpf'] = array	(	'confiança'		=>	$mp_forum_formador_false['confiança'],
									'esforço'		=>	$mp_forum_formador_false['esforço'],
									'independência'	=>	$mp_forum_formador_false['independência']);
	}

if($resp_colega){						//respondeu a um colega
	$fatores['confiança']		+=	$mp_forum_colega_true['confiança'];
	$fatores['esforço']			+=	$mp_forum_colega_true['esforço'];		
	$fatores['independência']	+=	$mp_forum_colega_true['independência'];
	$export['for.mpc'] = array	(	'confiança'		=>	$mp_forum_colega_true['confiança'],
									'esforço'		=>	$mp_forum_colega_true['esforço'],
									'independência'	=>	$mp_forum_colega_true['independência']);
	}
else{
	$fatores['confiança']		+=	$mp_forum_colega_false['confiança'];
	$fatores['esforço']			+=	$mp_forum_colega_false['esforço'];		
	$fatores['independência']	+=	$mp_forum_colega_false['independência'];
	$export['for.mpc'] = array	(	'confiança'		=>	$mp_forum_colega_false['confiança'],
									'esforço'		=>	$mp_forum_colega_false['esforço'],
									'independência'	=>	$mp_forum_colega_false['independência']);
	}

if($forum_a_mensagem){					//criou uma mensagem
	$fatores['confiança']		+=	$to_forum_mensagem_true['confiança'];
	$fatores['esforço']			+=	$to_forum_mensagem_true['esforço'];		
	$fatores['independência']	+=	$to_forum_mensagem_true['independência'];
	$export['for.ms'] = array	(	'confiança'		=>	$to_forum_mensagem_true['confiança'],
									'esforço'		=>	$to_forum_mensagem_true['esforço'],
									'independência'	=>	$to_forum_mensagem_true['independência']);
	}
else{
	$fatores['confiança']		+=	$to_forum_mensagem_false['confiança'];
	$fatores['esforço']			+=	$to_forum_mensagem_false['esforço'];		
	$fatores['independência']	+=	$to_forum_mensagem_false['independência'];
	$export['for.ms'] = array	(	'confiança'		=>	$to_forum_mensagem_false['confiança'],
									'esforço'		=>	$to_forum_mensagem_false['esforço'],
									'independência'	=>	$to_forum_mensagem_false['independência']);
	}

if($forum_a_topico){					//criou um topico
	$fatores['confiança']		+=	$to_forum_topico_true['confiança'];
	$fatores['esforço']			+=	$to_forum_topico_true['esforço'];		
	$fatores['independência']	+=	$to_forum_topico_true['independência'];
	$export['for.to'] = array	(	'confiança'		=>	$to_forum_topico_true['confiança'],
									'esforço'		=>	$to_forum_topico_true['esforço'],
									'independência'	=>	$to_forum_topico_true['independência']);
	}
else{
	$fatores['confiança']		+=	$to_forum_topico_false['confiança'];
	$fatores['esforço']			+=	$to_forum_topico_false['esforço'];		
	$fatores['independência']	+=	$to_forum_topico_false['independência'];
	$export['for.to'] = array	(	'confiança'		=>	$to_forum_topico_false['confiança'],
									'esforço'		=>	$to_forum_topico_false['esforço'],
									'independência'	=>	$to_forum_topico_false['independência']);
	}
?>
<?php
/*-----------------------------	analise BATE-PAPO	------------------------*/
switch($freq_btp){						//frequencia de participacao no bate-papo
	case 4:			case 3:			case 2:			case 1:
			$fatores['confiança']		+=	$fp_btp[$freq_btp]['confiança'];
			$fatores['esforço']			+=	$fp_btp[$freq_btp]['esforço'];		
			$fatores['independência']	+=	$fp_btp[$freq_btp]['independência'];
			$export['btp.fp'] = array	(	'confiança'		=>	$fp_btp[$freq_btp]['confiança'],
											'esforço'		=>	$fp_btp[$freq_btp]['esforço'],
											'independência'	=>	$fp_btp[$freq_btp]['independência']);
		break;
	case 0:
	default:
			$fatores['confiança']		+=	$fp_btp[0]['confiança'];
			$fatores['esforço']			+=	$fp_btp[0]['esforço'];		
			$fatores['independência']	+=	$fp_btp[0]['independência'];
			$export['btp.fp'] = array	(	'confiança'		=>	$fp_btp[0]['confiança'],
											'esforço'		=>	$fp_btp[0]['esforço'],
											'independência'	=>	$fp_btp[0]['independência']);
		break;
}
?>
<?php
/*-------------------------	analise DIARIO DE BORDO	------------------------*/
switch($freq_ddb){						//frequencia de participacao no diario de bordo
	case 4:			case 3:			case 2:			case 1:
			$fatores['confiança']		+=	$fp_ddb[$freq_ddb]['confiança'];
			$fatores['esforço']			+=	$fp_ddb[$freq_ddb]['esforço'];		
			$fatores['independência']	+=	$fp_ddb[$freq_ddb]['independência'];
			$export['ddb.fp'] = array	(	'confiança'		=>	$fp_ddb[$freq_ddb]['confiança'],
											'esforço'		=>	$fp_ddb[$freq_ddb]['esforço'],
											'independência'	=>	$fp_ddb[$freq_ddb]['independência']);
		break;
	case 0:
	default:
			$fatores['confiança']		+=	$fp_ddb[0]['confiança'];
			$fatores['esforço']			+=	$fp_ddb[0]['esforço'];		
			$fatores['independência']	+=	$fp_ddb[0]['independência'];
			$export['ddb.fp'] = array	(	'confiança'		=>	$fp_ddb[0]['confiança'],
											'esforço'		=>	$fp_ddb[0]['esforço'],
											'independência'	=>	$fp_ddb[0]['independência']);
		break;
}
?>
<?php
/*---------------------------	analise TEMPO	----------------------------*/
if($tempo_us){
	$fatores['esforço']			+= $tp_tempo_true['esforço'];
	$fatores['independência']	+= $tp_tempo_true['independência'];
}
else{
	$fatores['esforço']			+= $tp_tempo_false['esforço'];
	$fatores['independência']	+= $tp_tempo_false['independência'];
}
?>
<?php
/*-------------------------	normalização Fatores	------------------------*/
$fator_cru = $fatores;
	$fatores['confiança']		=	normaliza($fator_cru['confiança'],-19,-14,-5,5,14,19);
	$fatores['esforço']			=	normaliza($fator_cru['esforço'],-20,-14,-5,5,14,20);
	$fatores['independência']	=	normaliza($fator_cru['independência'],-3,-2,-1,1,2,3);
/*--------------------------------------------------------------------------*/
?>
<?php/*
 include_once("limites.php");

$fer = array();
$srcfor = "fatores/ferramenta.grafico.php?tp=for&";
$srcbtp = "fatores/ferramenta.grafico.php?tp=btp&";
$srcddb = "fatores/ferramenta.grafico.php?tp=ddb&";
foreach($grafica as $elemento){
	$elemento = explode(".",$elemento);
	$ferr	=	$elemento[0];
	$subf	=	$elemento[1];
	$fator	=	$elemento[2];
	
	$fer[$ferr][] = array(	'subf' => $subf,	'fator' => $fator,	
							'min' => $lim_min[$ferr][$subf][$fator],
							'max' => $lim_max[$ferr][$subf][$fator],
							'val' => $export[$ferr.".".$subfer][$fator]
					);
	echo	"<tr><td><a href='fatores/ferramenta.resultado.php?fer={$ferr}&subf={$subf}&fator={$fator}&".
			"val={$export[$ferr.".".$subfer][$fator]}'>"."{$ferr}.{$subfer}.{$fator}</a></td></tr>";
	switch($ferr){
		case 'for':
			$srcfor	.= 	"subf[]={$subf}&fator[]={$fator}&val[]=".$export[$ferr.".".$subfer][$fator]."&";
			break;
		case 'btp':
			$srcbtp .= 	"subf[]={$subf}&fator[]={$fator}&val[]=".$export[$ferr.".".$subfer][$fator]."&";
			break;
		case 'ddb':
			$srcddb .= 	"subf[]={$subf}&fator[]={$fator}&val[]=".$export[$ferr.".".$subfer][$fator]."&";
			break;
		default: break;
	}
}

echo "FOR: <img src={$srcfor}&ll=0 />";
echo "FOR: <img src={$srcfor}&ll=1 />";
echo "FOR: <img src={$srcfor}&ll=2 />";
echo "BTP: <img src={$srcbtp} />";
echo "DDB: <img src={$srcddb} />";

echo "FOR: <a href='{$srcfor}&ll=0'>f1</a>";
echo "FOR: <a href='{$srcfor}&ll=1'>f2</a>";
echo "FOR: <a href='{$srcfor}&ll=2'>f3</a>";
echo "BTP: <a href='{$srcbtp}&ll=0'>b</a>";
echo "DDB: <a href='{$srcddb}&ll=0'>d</a>";
*/
?>
<?php
$link = array();

	foreach($fatores as $ind=>$dados)		//inclui parametros para o grafico
		$link[$ind] = $dados[0];		// -1 <= x <= 1
		// $link[$ind] = $dados[4];		// 0 <= x <= 2

?>