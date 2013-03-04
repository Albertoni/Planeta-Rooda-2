<?php		/*------	fun��o: acha m�ximos e m�nimos	------*/
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
									'confian�a'	=>	max_min_indef('max','confian�a',$na_forum_acessos_true,$na_forum_acessos_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$na_forum_acessos_true,$na_forum_acessos_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$na_forum_acessos_true,$na_forum_acessos_false)
								),
					'nv'	=>	array(
									'confian�a'	=>	max_min_indef('max','confian�a',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$nv_forum_visitas_true,$nv_forum_visitas_false)
								),
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('max','confian�a',$fp_forum),
									'esfor�o'	=>	max_min_def('max','esfor�o',$fp_forum),
									'independ�ncia'	=>	max_min_def('max','independ�ncia',$fp_forum)
								),
					'mpf'	=>	array(
									'confian�a'	=>	max_min_indef('max','confian�a',$mp_forum_formador_true,$mp_forum_formador_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$mp_forum_formador_true,$mp_forum_formador_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$mp_forum_formador_true,$mp_forum_formador_false)
								),
					'mpc'	=>	array(
									'confian�a'	=>	max_min_indef('max','confian�a',$mp_forum_colega_true,$mp_forum_colega_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$mp_forum_colega_true,$mp_forum_colega_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$mp_forum_colega_true,$mp_forum_colega_false)
								),
					'ms'	=>	array(
									'confian�a'	=>	max_min_indef('max','confian�a',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$to_forum_mensagem_true,$to_forum_mensagem_false)
								),
					'to'	=>	array(
									'confian�a'	=>	max_min_indef('max','confian�a',$to_forum_topico_true,$to_forum_topico_false),
									'esfor�o'	=>	max_min_indef('max','esfor�o',$to_forum_topico_true,$to_forum_topico_false),
									'independ�ncia'	=>	max_min_indef('max','independ�ncia',$to_forum_topico_true,$to_forum_topico_false)
								)
				),
	'btp'	=>	array(
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('max','confian�a',$fp_btp),
									'esfor�o'	=>	max_min_def('max','esfor�o',$fp_btp),
									'independ�ncia'	=>	max_min_def('max','independ�ncia',$fp_btp)
								)
				),
	'ddb'	=>	array(
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('max','confian�a',$fp_ddb),
									'esfor�o'	=>	max_min_def('max','esfor�o',$fp_ddb),
									'independ�ncia'	=>	max_min_def('max','independ�ncia',$fp_ddb)
								)
				)
);

$lim_min	=	
array(
	'for'	=>	array(
					'na'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$na_forum_acessos_true,$na_forum_acessos_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$na_forum_acessos_true,$na_forum_acessos_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$na_forum_acessos_true,$na_forum_acessos_false)
								),
					'nv'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$nv_forum_visitas_true,$nv_forum_visitas_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$nv_forum_visitas_true,$nv_forum_visitas_false)
								),
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('min','confian�a',$fp_forum),
									'esfor�o'	=>	max_min_def('min','esfor�o',$fp_forum),
									'independ�ncia'	=>	max_min_def('min','independ�ncia',$fp_forum)
								),
					'mpf'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$mp_forum_formador_true,$mp_forum_formador_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$mp_forum_formador_true,$mp_forum_formador_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$mp_forum_formador_true,$mp_forum_formador_false)
								),
					'mpc'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$mp_forum_colega_true,$mp_forum_colega_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$mp_forum_colega_true,$mp_forum_colega_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$mp_forum_colega_true,$mp_forum_colega_false)
								),
					'ms'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$to_forum_mensagem_true,$to_forum_mensagem_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$to_forum_mensagem_true,$to_forum_mensagem_false)
								),
					'to'	=>	array(
									'confian�a'	=>	max_min_indef('min','confian�a',$to_forum_topico_true,$to_forum_topico_false),
									'esfor�o'	=>	max_min_indef('min','esfor�o',$to_forum_topico_true,$to_forum_topico_false),
									'independ�ncia'	=>	max_min_indef('min','independ�ncia',$to_forum_topico_true,$to_forum_topico_false)
								)
				),
	'btp'	=>	array(
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('min','confian�a',$fp_btp),
									'esfor�o'	=>	max_min_def('min','esfor�o',$fp_btp),
									'independ�ncia'	=>	max_min_def('min','independ�ncia',$fp_btp)
								)
				),
	'ddb'	=>	array(
					'fp'	=>	array(
									'confian�a'	=>	max_min_def('min','confian�a',$fp_ddb),
									'esfor�o'	=>	max_min_def('min','esfor�o',$fp_ddb),
									'independ�ncia'	=>	max_min_def('min','independ�ncia',$fp_ddb)
								)
				)
);
/*--------------		extremos geral		
$extremos	=	array(
					'max'	=>	array(
									'confian�a'		=>	-1000,
									'esfor�o'		=>	-1000,
									'independ�ncia'	=>	-1000
								),
					'min'	=>	array(
									'confian�a'		=>	1000,
									'esfor�o'		=>	1000,
									'independ�ncia'	=>	1000
								),
				);
foreach($lim_max as $lmax)
	foreach($lmax as $ferr){
			if($ferr['confian�a'] >		 $extremos['max']['confian�a'])
				$extremos['max']['confian�a'] = $ferr['confian�a'];
			if($ferr['esfor�o']	>		 $extremos['max']['esfor�o'])
				$extremos['max']['esfor�o'] = $ferr['esfor�o'];
			if($ferr['independ�ncia'] >	 $extremos['max']['independ�ncia'])
				$extremos['max']['independ�ncia'] = $ferr['independ�ncia'];
		}
foreach($lim_min as $lmin)
	foreach($lmin as $ferr){
			if($ferr['confian�a'] <		 $extremos['min']['confian�a'])
				$extremos['min']['confian�a'] = $ferr['confian�a'];
			if($ferr['esfor�o']	<		 $extremos['min']['esfor�o'])
				$extremos['min']['esfor�o'] = $ferr['esfor�o'];
			if($ferr['independ�ncia'] <	 $extremos['min']['independ�ncia'])
				$extremos['min']['independ�ncia'] = $ferr['independ�ncia'];
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