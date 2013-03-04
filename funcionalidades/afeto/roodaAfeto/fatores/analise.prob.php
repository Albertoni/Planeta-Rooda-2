<?php		/*----		FUNÇÕES AUXILIARES		----*/
function normaliza_prob($int){	//intensidade, limites_maximoas
	$limites = array(30, 40, 60, 70, 100);
	switch($int){
		case ($int < $limites[0]):
				return 'mfraco';
				break;
		case ($int < $limites[1]):
				return 'fraco';
				break;
		case ($int <= $limites[2]):
				return 'equilibrio';
				break;
		case ($int <= $limites[3]):
				return 'forte';
				break;
		case ($int <= $limites[4]):
				return 'mforte';
				break;
		default:
				return 'equilibrio';
				break;
	}
}

function busca_sql($what,$from,$where=1,$assoc=false){	//busca em sql
	$result = mysql_query("select $what from $from where $where");
		$rest = array();
	if(!$assoc)
		while($tmp = mysql_fetch_array($result))
			$rest[] = $tmp;
	else
		while($tmp = mysql_fetch_assoc($result))
			$rest[] = $tmp;

	return $rest;
}
?>
<?php
$el_sql	=	array(	'fatorespersonalidade'	=>	'ra_fatorespersonalidade',
					'tpposava'	=>	'ra_tpposava',
					'tpnegava'	=>	'ra_tpnegava',
					'prepo'		=>	'ra_prepo',
					'motivacao'	=>	'ra_motivacao',
					'ea'	=>	'ra_ea'
			);

$assistencia = 'ass';	$desempenho = 'des';	$autonomia = 'aut';
$persistencia = 'pers';	$mudanca = 'mu';		$ordem = 'o';
$dominacao = 'dom';		$denegacao = 'den';		$agressao = 'ag';

$usuarios	=	busca_sql('*',$el_sql['fatorespersonalidade'],"codusuario={$codUs}");
	$usuario = $usuarios[0];

$subjetividade	=	$argumentos['subjetividade'];
$fatores		=	$argumentos['fatores'];
	
$intens		=	array('mforte','forte','equilibrio','fraco','mfraco');
/*------------------	'probabilizar' estados	-----------*/
$subjetividade = array('satisfeito'=>0,'insatisfeito'=>0,'animado'=>0,'desanimado'=>0,'indefinido'=>0);
foreach($subjetividade as $registro){
	switch($registro['quad']){
		case	1:	$quad	=	'satisfeito';	break;
		case	2:	$quad	=	'insatisfeito';	break;
		case	3:	$quad	=	'desanimado';	break;
		case	4:	$quad	=	'animado';		break;
		default:	$quad	=	'indefinido';	break;
	}
	$subjetividade[$quad]++;
}

$qtd = array_sum($subjetividade);
foreach(array_keys($subjetividade) as $key)
	$subjetividade[$key] /= $qtd;
/*--------------------------------------------------------*/
?>
<?php		/*----				TPPOSAVA			----*/
	$indices_positivos	=	array($assistencia,$desempenho,$autonomia,$persistencia,$mudanca,$ordem);
	$fatores_positivos	= array();
		for($i=0;$i<count($indices_positivos);$i++){
			$fatores_positivos[$i]	=	$usuario[$indices_positivos[$i]];
			$fatores_positivos[$indices_positivos[$i]]
				=	normaliza_prob($usuario[$indices_positivos[$i]]);
		}

		$where = "";
	for($i=0;$i<(count($indices_positivos)-1);$i++)
		$where .= "{$indices_positivos[$i]}='{$fatores_positivos[$indices_positivos[$i]]}' and ";
	$where .= "{$indices_positivos[$i]}='{$fatores_positivos[$indices_positivos[$i]]}'";

	$tpposava = busca_sql("`mforte`,`forte`,`equilibrio`,`fraca`,`mfraca`",$el_sql['tpposava'],$where,true);
		$tpposava = $tpposava[0];					//resultado de TPPosAVA
?>
<?php		/*----				TPNEGAVA			----*/
	$indices_negativos	=	array($dominacao,$denegacao,$agressao);
	$fatores_negativos	= array();
		for($i=0;$i<count($indices_negativos);$i++){
			$fatores_negativos[$i]	=	$usuario[$indices_negativos[$i]];
			$fatores_negativos[$indices_negativos[$i]]
				=	normaliza_prob($usuario[$indices_negativos[$i]]);
		}

	$where = "";
	for($i=0;$i<(count($indices_negativos)-1);$i++)
		$where .= "{$indices_negativos[$i]}='{$fatores_negativos[$indices_negativos[$i]]}' and ";
	$where .= "{$indices_negativos[$i]}='{$fatores_negativos[$indices_negativos[$i]]}'";

	$tpnegava = busca_sql("`mforte`,`forte`,`equilibrio`,`fraca`,`mfraca`",$el_sql['tpnegava'],$where,true);
		$tpnegava = $tpnegava[0];					//resultado de TPNegAVA
?>
<?php		/*----				PREDO_FP			----*/
		$predofp = array('positiva' => 0, 'negativa'=> 0 , 'ambigua' => 0);
	foreach($tpposava as $pos=>$probpos){
		foreach($tpnegava as $neg=>$probneg){
			if( $probneg * $probpos != 0 ){
				$sql = mysql_fetch_assoc(mysql_query(	"select `positiva`, `negativa`, `ambigua` from {$el_sql['prepo']} ".
														"where tpposava='{$pos}' and tpnegava='{$neg}'"));
				foreach($sql as $tipo=>$intensidade){
					$predofp[$tipo] += $intensidade*$probneg*$probpos;
				}
			}
		}
	}
?>
<?php		/*----				MOTIVACAO			----*/
	$motivacao = array('motivado' => 0, 'n_motivado' => 0);
	foreach($predofp as  $tipo=>$intensidade){
		$motiva_temp	=	busca_sql(	"`motivado`,`n_motivado`",
										$el_sql['motivacao'],
										"c='{$fatores['confianca']}' and e='{$fatores['esforco']}' and i='{$fatores['independ']}'".
											"and ea_fp='{$tipo}'",
										true);
		$motivacao['motivado']	+=	$intensidade*$motiva_temp[0]['motivado'];
		$motivacao['n_motivado']	+=	$intensidade*$motiva_temp[0]['n_motivado'];
	}
?>
<?php		/*----				ESTADOANIMO			----*/
//		echo $EA['satisfeito'].";".$EA['insatisfeito'].";".$EA['animado'].";".$EA['desanimado'].";".$EA['indefinido'].";\n";
	$EA	=	array('satisfeito'=>0,'insatisfeito'=>0,'animado'=>0,'desanimado'=>0,'indefinido'=>0);
	foreach($subjetividade as $subjet=>$v_subjet){
		foreach($predofp as $pfp=>$v_pfp){
			foreach($motivacao as $motiv=>$v_motiv){
				$temp_ea	=	
					busca_sql(	"`satisfeito`,`insatisfeito`,`animado`,`desanimado`,`indefinido`",
								$el_sql['ea'],"motivacao='{$motiv}' and s='{$subjet}' and ea_fp='{$pfp}'",
								true);
				foreach($temp_ea[0] as $afet=>$prob){
					$EA[$afet] += 	$prob*$v_subjet*$v_pfp*$v_motiv;
				}
			}
		}
	}
		/*----				job's done				----*/
?>
<?php	/*	d-bug	* /
	echo "fatores_positivos:\n";	print_r($fatores_positivos);
	echo "fatores_negativos:\n";	print_r($fatores_negativos);
	echo "tppos: (prob=".array_sum($tpposava).")\n";
		print_r($tpposava);
	echo "\ntpneg: (prob=".array_sum($tpnegava).")\n";
		print_r($tpnegava);
	echo "\npredofp: (prob=".array_sum($predofp).")\n";
		print_r($predofp);
	echo "fatores:\n";	print_r($fatores);
	echo "\nmotivação: (prob=".array_sum($motivacao).")\n";
		print_r($motivacao);
	echo "\nsubjetividade: (prob=".array_sum($subjetividade).")\n";
		print_r($subjetividade);
	echo "\nestados animo: (prob=".array_sum($EA).")\n";
		print_r($EA);
/* */
?>