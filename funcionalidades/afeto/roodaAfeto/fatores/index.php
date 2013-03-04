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
	array(	'confian�a' => 0,
			'esfor�o' => 0,
			'independ�ncia' =>0);
?>
<?php
$export = array();
/*---------------------------------	analise FORUM	------------------------*/
// if($acessos_forum){						//media de acessos ao forum
	// $fatores['confian�a']		+=	$na_forum_acessos_true['confian�a'];
	// $fatores['esfor�o']			+=	$na_forum_acessos_true['esfor�o'];		
	// $fatores['independ�ncia']	+=	$na_forum_acessos_true['independ�ncia'];
	// $export['for.na'] = array	(	'confian�a'		=>	$na_forum_acessos_true['confian�a'],
									// 'esfor�o'		=>	$na_forum_acessos_true['esfor�o'],
									// 'independ�ncia'	=>	$na_forum_acessos_true['independ�ncia']);
	// }
// else{
	// $fatores['confian�a']		+=	$na_forum_acessos_false['confian�a'];
	// $fatores['esfor�o']			+=	$na_forum_acessos_false['esfor�o'];		
	// $fatores['independ�ncia']	+=	$na_forum_acessos_false['independ�ncia'];
	// $export['for.na'] = array	(	'confian�a'		=>	$na_forum_acessos_false['confian�a'],
									// 'esfor�o'		=>	$na_forum_acessos_false['esfor�o'],
									// 'independ�ncia'	=>	$na_forum_acessos_false['independ�ncia']);
	// }

if($visitas_forum){						//media de mensagens vistas no forum
	$fatores['confian�a']		+=	$nv_forum_visitas_true['confian�a'];
	$fatores['esfor�o']			+=	$nv_forum_visitas_true['esfor�o'];		
	$fatores['independ�ncia']	+=	$nv_forum_visitas_true['independ�ncia'];
	$export['for.nv'] = array	(	'confian�a'		=>	$nv_forum_visitas_true['confian�a'],
									'esfor�o'		=>	$nv_forum_visitas_true['esfor�o'],
									'independ�ncia'	=>	$nv_forum_visitas_true['independ�ncia']);
	}
else{
	$fatores['confian�a']		+=	$nv_forum_visitas_false['confian�a'];
	$fatores['esfor�o']			+=	$nv_forum_visitas_false['esfor�o'];		
	$fatores['independ�ncia']	+=	$nv_forum_visitas_false['independ�ncia'];
	$export['for.nv'] = array	(	'confian�a'		=>	$nv_forum_visitas_false['confian�a'],
									'esfor�o'		=>	$nv_forum_visitas_false['esfor�o'],
									'independ�ncia'	=>	$nv_forum_visitas_false['independ�ncia']);
}
	

switch($freq_forum){						//frequencia de participacao no forum
	case 4:			case 3:			case 2:			case 1:
			$fatores['confian�a']		+=	$fp_forum[$freq_forum]['confian�a'];
			$fatores['esfor�o']			+=	$fp_forum[$freq_forum]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_forum[$freq_forum]['independ�ncia'];
			$export['for.fp'] = array	(	'confian�a'		=>	$fp_forum[$freq_forum]['confian�a'],
											'esfor�o'		=>	$fp_forum[$freq_forum]['esfor�o'],
											'independ�ncia'	=>	$fp_forum[$freq_forum]['independ�ncia']);
		break;
	case 0:
	default:
			$fatores['confian�a']		+=	$fp_forum[0]['confian�a'];
			$fatores['esfor�o']			+=	$fp_forum[0]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_forum[0]['independ�ncia'];
			$export['for.fp'] = array	(	'confian�a'		=>	$fp_forum[0]['confian�a'],
											'esfor�o'		=>	$fp_forum[0]['esfor�o'],
											'independ�ncia'	=>	$fp_forum[0]['independ�ncia']);
		break;
}

if($resp_formador){						//respondeu a um formador
	$fatores['confian�a']		+=	$mp_forum_formador_true['confian�a'];
	$fatores['esfor�o']			+=	$mp_forum_formador_true['esfor�o'];		
	$fatores['independ�ncia']	+=	$mp_forum_formador_true['independ�ncia'];
	$export['for.mpf'] = array	(	'confian�a'		=>	$mp_forum_formador_true['confian�a'],
									'esfor�o'		=>	$mp_forum_formador_true['esfor�o'],
									'independ�ncia'	=>	$mp_forum_formador_true['independ�ncia']);
	}
else{
	$fatores['confian�a']		+=	$mp_forum_formador_false['confian�a'];
	$fatores['esfor�o']			+=	$mp_forum_formador_false['esfor�o'];		
	$fatores['independ�ncia']	+=	$mp_forum_formador_false['independ�ncia'];
	$export['for.mpf'] = array	(	'confian�a'		=>	$mp_forum_formador_false['confian�a'],
									'esfor�o'		=>	$mp_forum_formador_false['esfor�o'],
									'independ�ncia'	=>	$mp_forum_formador_false['independ�ncia']);
	}

if($resp_colega){						//respondeu a um colega
	$fatores['confian�a']		+=	$mp_forum_colega_true['confian�a'];
	$fatores['esfor�o']			+=	$mp_forum_colega_true['esfor�o'];		
	$fatores['independ�ncia']	+=	$mp_forum_colega_true['independ�ncia'];
	$export['for.mpc'] = array	(	'confian�a'		=>	$mp_forum_colega_true['confian�a'],
									'esfor�o'		=>	$mp_forum_colega_true['esfor�o'],
									'independ�ncia'	=>	$mp_forum_colega_true['independ�ncia']);
	}
else{
	$fatores['confian�a']		+=	$mp_forum_colega_false['confian�a'];
	$fatores['esfor�o']			+=	$mp_forum_colega_false['esfor�o'];		
	$fatores['independ�ncia']	+=	$mp_forum_colega_false['independ�ncia'];
	$export['for.mpc'] = array	(	'confian�a'		=>	$mp_forum_colega_false['confian�a'],
									'esfor�o'		=>	$mp_forum_colega_false['esfor�o'],
									'independ�ncia'	=>	$mp_forum_colega_false['independ�ncia']);
	}

if($forum_a_mensagem){					//criou uma mensagem
	$fatores['confian�a']		+=	$to_forum_mensagem_true['confian�a'];
	$fatores['esfor�o']			+=	$to_forum_mensagem_true['esfor�o'];		
	$fatores['independ�ncia']	+=	$to_forum_mensagem_true['independ�ncia'];
	$export['for.ms'] = array	(	'confian�a'		=>	$to_forum_mensagem_true['confian�a'],
									'esfor�o'		=>	$to_forum_mensagem_true['esfor�o'],
									'independ�ncia'	=>	$to_forum_mensagem_true['independ�ncia']);
	}
else{
	$fatores['confian�a']		+=	$to_forum_mensagem_false['confian�a'];
	$fatores['esfor�o']			+=	$to_forum_mensagem_false['esfor�o'];		
	$fatores['independ�ncia']	+=	$to_forum_mensagem_false['independ�ncia'];
	$export['for.ms'] = array	(	'confian�a'		=>	$to_forum_mensagem_false['confian�a'],
									'esfor�o'		=>	$to_forum_mensagem_false['esfor�o'],
									'independ�ncia'	=>	$to_forum_mensagem_false['independ�ncia']);
	}

if($forum_a_topico){					//criou um topico
	$fatores['confian�a']		+=	$to_forum_topico_true['confian�a'];
	$fatores['esfor�o']			+=	$to_forum_topico_true['esfor�o'];		
	$fatores['independ�ncia']	+=	$to_forum_topico_true['independ�ncia'];
	$export['for.to'] = array	(	'confian�a'		=>	$to_forum_topico_true['confian�a'],
									'esfor�o'		=>	$to_forum_topico_true['esfor�o'],
									'independ�ncia'	=>	$to_forum_topico_true['independ�ncia']);
	}
else{
	$fatores['confian�a']		+=	$to_forum_topico_false['confian�a'];
	$fatores['esfor�o']			+=	$to_forum_topico_false['esfor�o'];		
	$fatores['independ�ncia']	+=	$to_forum_topico_false['independ�ncia'];
	$export['for.to'] = array	(	'confian�a'		=>	$to_forum_topico_false['confian�a'],
									'esfor�o'		=>	$to_forum_topico_false['esfor�o'],
									'independ�ncia'	=>	$to_forum_topico_false['independ�ncia']);
	}
?>
<?php
/*-----------------------------	analise BATE-PAPO	------------------------*/
switch($freq_btp){						//frequencia de participacao no bate-papo
	case 4:			case 3:			case 2:			case 1:
			$fatores['confian�a']		+=	$fp_btp[$freq_btp]['confian�a'];
			$fatores['esfor�o']			+=	$fp_btp[$freq_btp]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_btp[$freq_btp]['independ�ncia'];
			$export['btp.fp'] = array	(	'confian�a'		=>	$fp_btp[$freq_btp]['confian�a'],
											'esfor�o'		=>	$fp_btp[$freq_btp]['esfor�o'],
											'independ�ncia'	=>	$fp_btp[$freq_btp]['independ�ncia']);
		break;
	case 0:
	default:
			$fatores['confian�a']		+=	$fp_btp[0]['confian�a'];
			$fatores['esfor�o']			+=	$fp_btp[0]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_btp[0]['independ�ncia'];
			$export['btp.fp'] = array	(	'confian�a'		=>	$fp_btp[0]['confian�a'],
											'esfor�o'		=>	$fp_btp[0]['esfor�o'],
											'independ�ncia'	=>	$fp_btp[0]['independ�ncia']);
		break;
}
?>
<?php
/*-------------------------	analise DIARIO DE BORDO	------------------------*/
switch($freq_ddb){						//frequencia de participacao no diario de bordo
	case 4:			case 3:			case 2:			case 1:
			$fatores['confian�a']		+=	$fp_ddb[$freq_ddb]['confian�a'];
			$fatores['esfor�o']			+=	$fp_ddb[$freq_ddb]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_ddb[$freq_ddb]['independ�ncia'];
			$export['ddb.fp'] = array	(	'confian�a'		=>	$fp_ddb[$freq_ddb]['confian�a'],
											'esfor�o'		=>	$fp_ddb[$freq_ddb]['esfor�o'],
											'independ�ncia'	=>	$fp_ddb[$freq_ddb]['independ�ncia']);
		break;
	case 0:
	default:
			$fatores['confian�a']		+=	$fp_ddb[0]['confian�a'];
			$fatores['esfor�o']			+=	$fp_ddb[0]['esfor�o'];		
			$fatores['independ�ncia']	+=	$fp_ddb[0]['independ�ncia'];
			$export['ddb.fp'] = array	(	'confian�a'		=>	$fp_ddb[0]['confian�a'],
											'esfor�o'		=>	$fp_ddb[0]['esfor�o'],
											'independ�ncia'	=>	$fp_ddb[0]['independ�ncia']);
		break;
}
?>
<?php
/*---------------------------	analise TEMPO	----------------------------*/
if($tempo_us){
	$fatores['esfor�o']			+= $tp_tempo_true['esfor�o'];
	$fatores['independ�ncia']	+= $tp_tempo_true['independ�ncia'];
}
else{
	$fatores['esfor�o']			+= $tp_tempo_false['esfor�o'];
	$fatores['independ�ncia']	+= $tp_tempo_false['independ�ncia'];
}
?>
<?php
/*-------------------------	normaliza��o Fatores	------------------------*/
$fator_cru = $fatores;
	$fatores['confian�a']		=	normaliza($fator_cru['confian�a'],-19,-14,-5,5,14,19);
	$fatores['esfor�o']			=	normaliza($fator_cru['esfor�o'],-20,-14,-5,5,14,20);
	$fatores['independ�ncia']	=	normaliza($fator_cru['independ�ncia'],-3,-2,-1,1,2,3);
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