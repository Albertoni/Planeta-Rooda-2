<?php			/*--------		dados forum		--------*/
//na >= media
// $na_forum_acessos_true		=	array(	'confiança'	=>	1,
										// 'esforço'	=>	1,
										// 'independência'	=>	0);
										
// $na_forum_acessos_false		=	array(	'confiança'	=>	-1,
										// 'esforço'	=>	-1,
										// 'independência'	=>	0);

//nv >= media
$nv_forum_visitas_true		=	array(	'confiança'	=>	1,
										'esforço'	=>	0,
										'independência'	=>	1);
										
$nv_forum_visitas_false		=	array(	'confiança'	=>	-1,
										'esforço'	=>	0,
										'independência'	=>	-1);

$fp_forum					=	array(
									0	=>	array(	'confiança'	=>	-3,
													'esforço'	=>	-3,
													'independência'	=>	0),
									1	=>	array(	'confiança'	=>	0,
													'esforço'	=>	-2,
													'independência'	=>	0),
									2	=>	array(	'confiança'	=>	0,
													'esforço'	=>	-1,
													'independência'	=>	0),
									3	=>	array(	'confiança'	=>	0,
													'esforço'	=>	1,
													'independência'	=>	0),
									4	=>	array(	'confiança'	=>	0,
													'esforço'	=>	2,
													'independência'	=>	0)		);

//respondeu formador?
$mp_forum_formador_true		=	array(	'confiança'	=>	2,
										'esforço'	=>	3,
										'independência'	=>	0);

$mp_forum_formador_false	=	array(	'confiança'	=>	-2,
										'esforço'	=>	-1,
										'independência'	=>	0);

//respondeu colega?
$mp_forum_colega_true		=	array(	'confiança'	=>	1,
										'esforço'	=>	2,
										'independência'	=>	0);
$mp_forum_colega_false		=	array(	'confiança'	=>	-1,
										'esforço'	=>	1,
										'independência'	=>	0);

//criou mensagem?
$to_forum_mensagem_true		=	array(	'confiança'	=>	1,
										'esforço'	=>	1,
										'independência'	=>	1);

$to_forum_mensagem_false	=	array(	'confiança'	=>	0,
										'esforço'	=>	0,
										'independência'	=>	0);

//criou topico?
$to_forum_topico_true		=	array(	'confiança'	=>	2,
										'esforço'	=>	2,
										'independência'	=>	0);
										
$to_forum_topico_false		=	array(	'confiança'	=>	0,
										'esforço'	=>	0,
										'independência'	=>	0);
?>
<?php			/*--------		dados btp		--------*/
$fp_btp						=	array(
									0	=>	array(	'confiança'	=>	-3,
													'esforço'	=>	-3,
													'independência'	=>	0),
									1	=>	array(	'confiança'	=>	-1,
													'esforço'	=>	-2,
													'independência'	=>	0),
									2	=>	array(	'confiança'	=>	1,
													'esforço'	=>	-1,
													'independência'	=>	0),
									3	=>	array(	'confiança'	=>	2,
													'esforço'	=>	1,
													'independência'	=>	0),
									4	=>	array(	'confiança'	=>	3,
													'esforço'	=>	2,
													'independência'	=>	0)		);
?>
<?php			/*--------		dados ddb		--------*/
$fp_ddb						=	array(
									0	=>	array(	'confiança'	=>	-3,
													'esforço'	=>	-3,
													'independência'	=>	0),
									1	=>	array(	'confiança'	=>	-1,
													'esforço'	=>	-2,
													'independência'	=>	0),
									2	=>	array(	'confiança'	=>	1,
													'esforço'	=>	-1,
													'independência'	=>	0),
									3	=>	array(	'confiança'	=>	2,
													'esforço'	=>	1,
													'independência'	=>	0),
									4	=>	array(	'confiança'	=>	3,
													'esforço'	=>	2,
													'independência'	=>	0)		);

?>
<?php			/*--------		dados tempo		--------*/
//tempo_usuario <= tempo_turma?
$tp_tempo_true			=	array(	'confiança'	=>	0,
									'esforço'	=>	-1,
									'independência'	=>	1);

$tp_tempo_false			=	array(	'confiança'	=>	0,
									'esforço'	=>	1,
									'independência'	=>	-1);
?>