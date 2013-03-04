<?php		/*------	função: acha máximos e mínimos	------*/
function max_min_def($tipo,$index,$var){
	switch($tipo){
		case 'max':
				$valor = -1000;
				foreach($var as $subvar)
					$valor = ($subvar[$index]>$valor)? $subvar[$index]:$valor;
				break;
		case 'min':
				$valor = +1000;
				foreach($var as $subvar)
					$valor = ($subvar[$index]<$valor)? $subvar[$index]:$valor;
				break;
		default:
				break;
	}
	return $valor;
}
function max_min_indef($tipo,$index){
	switch($tipo){
		case 'max':
			$valor = -1000;
			for($i=2;$i<func_num_args();$i++){
				$temp = func_get_arg($i);
				$valor = ($temp[$index]>$valor)? $temp[$index]:$valor;
			}
			break;
		case 'min':
			$valor = +1000;
			for($i=2;$i<func_num_args();$i++){
				$temp = func_get_arg($i);
				$valor = ($temp[$index]<$valor)? $temp[$index]:$valor;
			}
			break;
		default:
			break;
	}
	return $valor;
}
?>
<?php
require_once("valores.data.php");
			
$lim_max	=	
array(
	'for'	=>	array(
					'na'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$na_forum_acessos_true,$na_forum_acessos_false),
									'esforço'	=>	max_min_indef('max','esforço',$na_forum_acessos_true,$na_forum_acessos_false),
									'independência'	=>	max_min_indef('max','independência',$na_forum_acessos_true,$na_forum_acessos_false)
								),
					'nv'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'esforço'	=>	max_min_indef('max','esforço',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'independência'	=>	max_min_indef('max','independência',$nv_forum_visitas_true,$nv_forum_visitas_false)
								),
					'fp'	=>	array(
									'confiança'	=>	max_min_def('max','confiança',$fp_forum),
									'esforço'	=>	max_min_def('max','esforço',$fp_forum),
									'independência'	=>	max_min_def('max','independência',$fp_forum)
								),
					'mpf'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$mp_forum_formador_true,$mp_forum_formador_false),
									'esforço'	=>	max_min_indef('max','esforço',$mp_forum_formador_true,$mp_forum_formador_false),
									'independência'	=>	max_min_indef('max','independência',$mp_forum_formador_true,$mp_forum_formador_false)
								),
					'mpc'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$mp_forum_colega_true,$mp_forum_colega_false),
									'esforço'	=>	max_min_indef('max','esforço',$mp_forum_colega_true,$mp_forum_colega_false),
									'independência'	=>	max_min_indef('max','independência',$mp_forum_colega_true,$mp_forum_colega_false)
								),
					'ms'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'esforço'	=>	max_min_indef('max','esforço',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'independência'	=>	max_min_indef('max','independência',$to_forum_mensagem_true,$to_forum_mensagem_false)
								),
					'to'	=>	array(
									'confiança'	=>	max_min_indef('max','confiança',$to_forum_topico_true,$to_forum_topico_false),
									'esforço'	=>	max_min_indef('max','esforço',$to_forum_topico_true,$to_forum_topico_false),
									'independência'	=>	max_min_indef('max','independência',$to_forum_topico_true,$to_forum_topico_false)
								)
				),
	'btp'	=>	array(
					'fp'	=>	array(
									'confiança'	=>	max_min_def('max','confiança',$fp_btp),
									'esforço'	=>	max_min_def('max','esforço',$fp_btp),
									'independência'	=>	max_min_def('max','independência',$fp_btp)
								)
				),
	'ddb'	=>	array(
					'fp'	=>	array(
									'confiança'	=>	max_min_def('max','confiança',$fp_ddb),
									'esforço'	=>	max_min_def('max','esforço',$fp_ddb),
									'independência'	=>	max_min_def('max','independência',$fp_ddb)
								)
				)
);

$lim_min	=	
array(
	'for'	=>	array(
					'na'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$na_forum_acessos_true,$na_forum_acessos_false),
									'esforço'	=>	max_min_indef('min','esforço',$na_forum_acessos_true,$na_forum_acessos_false),
									'independência'	=>	max_min_indef('min','independência',$na_forum_acessos_true,$na_forum_acessos_false)
								),
					'nv'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'esforço'	=>	max_min_indef('min','esforço',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'independência'	=>	max_min_indef('min','independência',$nv_forum_visitas_true,$nv_forum_visitas_false)
								),
					'fp'	=>	array(
									'confiança'	=>	max_min_def('min','confiança',$fp_forum),
									'esforço'	=>	max_min_def('min','esforço',$fp_forum),
									'independência'	=>	max_min_def('min','independência',$fp_forum)
								),
					'mpf'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$mp_forum_formador_true,$mp_forum_formador_false),
									'esforço'	=>	max_min_indef('min','esforço',$mp_forum_formador_true,$mp_forum_formador_false),
									'independência'	=>	max_min_indef('min','independência',$mp_forum_formador_true,$mp_forum_formador_false)
								),
					'mpc'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$mp_forum_colega_true,$mp_forum_colega_false),
									'esforço'	=>	max_min_indef('min','esforço',$mp_forum_colega_true,$mp_forum_colega_false),
									'independência'	=>	max_min_indef('min','independência',$mp_forum_colega_true,$mp_forum_colega_false)
								),
					'ms'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'esforço'	=>	max_min_indef('min','esforço',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'independência'	=>	max_min_indef('min','independência',$to_forum_mensagem_true,$to_forum_mensagem_false)
								),
					'to'	=>	array(
									'confiança'	=>	max_min_indef('min','confiança',$to_forum_topico_true,$to_forum_topico_false),
									'esforço'	=>	max_min_indef('min','esforço',$to_forum_topico_true,$to_forum_topico_false),
									'independência'	=>	max_min_indef('min','independência',$to_forum_topico_true,$to_forum_topico_false)
								)
				),
	'btp'	=>	array(
					'fp'	=>	array(
									'confiança'	=>	max_min_def('min','confiança',$fp_btp),
									'esforço'	=>	max_min_def('min','esforço',$fp_btp),
									'independência'	=>	max_min_def('min','independência',$fp_btp)
								)
				),
	'ddb'	=>	array(
					'fp'	=>	array(
									'confiança'	=>	max_min_def('min','confiança',$fp_ddb),
									'esforço'	=>	max_min_def('min','esforço',$fp_ddb),
									'independência'	=>	max_min_def('min','independência',$fp_ddb)
								)
				)
);
/*--------------		extremos geral		
$extremos	=	array(
					'max'	=>	array(
									'confiança'		=>	-1000,
									'esforço'		=>	-1000,
									'independência'	=>	-1000
								),
					'min'	=>	array(
									'confiança'		=>	1000,
									'esforço'		=>	1000,
									'independência'	=>	1000
								),
				);
foreach($lim_max as $lmax)
	foreach($lmax as $ferr){
			if($ferr['confiança'] >		 $extremos['max']['confiança'])
				$extremos['max']['confiança'] = $ferr['confiança'];
			if($ferr['esforço']	>		 $extremos['max']['esforço'])
				$extremos['max']['esforço'] = $ferr['esforço'];
			if($ferr['independência'] >	 $extremos['max']['independência'])
				$extremos['max']['independência'] = $ferr['independência'];
		}
foreach($lim_min as $lmin)
	foreach($lmin as $ferr){
			if($ferr['confiança'] <		 $extremos['min']['confiança'])
				$extremos['min']['confiança'] = $ferr['confiança'];
			if($ferr['esforço']	<		 $extremos['min']['esforço'])
				$extremos['min']['esforço'] = $ferr['esforço'];
			if($ferr['independência'] <	 $extremos['min']['independência'])
				$extremos['min']['independência'] = $ferr['independência'];
		}
//----------------------*/

$grafica = array();
foreach($lim_max as $fer=>$elem1){
	foreach($elem1 as $subfer=>$elem2){
		foreach($elem2 as $fator=>$val){
			if( $lim_max[$fer][$subfer][$fator] != 0 || $lim_min[$fer][$subfer][$fator] != 0)
				$grafica[]	= "{$fer}.{$subfer}.{$fator}";
		}
	}
}

$extremos	=	array(	'max'	=>	array(	'for' => -1000,	'btp' => -1000,	'ddb' => -1000),	// por
						'min'	=>	array(	'for' => 1000,	'btp' => 1000,	'ddb' => 1000));	// ferramenta

foreach($lim_min as $ferramenta=>$sf)
	foreach($sf as $mn)
		if(min($mn) < $extremos['min'][$ferramenta])
			$extremos['min'][$ferramenta] = min($mn);

foreach($lim_max as $ferramenta=>$sf)
	foreach($sf as $mx)
		if(max($mx) > $extremos['max'][$ferramenta])
			$extremos['max'][$ferramenta] = max($mx);
?>