<?php			/*--------		dados forum		--------*/
//na >= media
// $na_forum_acessos_true		=	array(	'confian�a'	=>	1,
										// 'esfor�o'	=>	1,
										// 'independ�ncia'	=>	0);
										
// $na_forum_acessos_false		=	array(	'confian�a'	=>	-1,
										// 'esfor�o'	=>	-1,
										// 'independ�ncia'	=>	0);

//nv >= media
$nv_forum_visitas_true		=	array(	'confian�a'	=>	1,
										'esfor�o'	=>	0,
										'independ�ncia'	=>	1);
										
$nv_forum_visitas_false		=	array(	'confian�a'	=>	-1,
										'esfor�o'	=>	0,
										'independ�ncia'	=>	-1);

$fp_forum					=	array(
									0	=>	array(	'confian�a'	=>	-3,
													'esfor�o'	=>	-3,
													'independ�ncia'	=>	0),
									1	=>	array(	'confian�a'	=>	0,
													'esfor�o'	=>	-2,
													'independ�ncia'	=>	0),
									2	=>	array(	'confian�a'	=>	0,
													'esfor�o'	=>	-1,
													'independ�ncia'	=>	0),
									3	=>	array(	'confian�a'	=>	0,
													'esfor�o'	=>	1,
													'independ�ncia'	=>	0),
									4	=>	array(	'confian�a'	=>	0,
													'esfor�o'	=>	2,
													'independ�ncia'	=>	0)		);

//respondeu formador?
$mp_forum_formador_true		=	array(	'confian�a'	=>	2,
										'esfor�o'	=>	3,
										'independ�ncia'	=>	0);

$mp_forum_formador_false	=	array(	'confian�a'	=>	-2,
										'esfor�o'	=>	-1,
										'independ�ncia'	=>	0);

//respondeu colega?
$mp_forum_colega_true		=	array(	'confian�a'	=>	1,
										'esfor�o'	=>	2,
										'independ�ncia'	=>	0);
$mp_forum_colega_false		=	array(	'confian�a'	=>	-1,
										'esfor�o'	=>	1,
										'independ�ncia'	=>	0);

//criou mensagem?
$to_forum_mensagem_true		=	array(	'confian�a'	=>	1,
										'esfor�o'	=>	1,
										'independ�ncia'	=>	1);

$to_forum_mensagem_false	=	array(	'confian�a'	=>	0,
										'esfor�o'	=>	0,
										'independ�ncia'	=>	0);

//criou topico?
$to_forum_topico_true		=	array(	'confian�a'	=>	2,
										'esfor�o'	=>	2,
										'independ�ncia'	=>	0);
										
$to_forum_topico_false		=	array(	'confian�a'	=>	0,
										'esfor�o'	=>	0,
										'independ�ncia'	=>	0);
?>
<?php			/*--------		dados btp		--------*/
$fp_btp						=	array(
									0	=>	array(	'confian�a'	=>	-3,
													'esfor�o'	=>	-3,
													'independ�ncia'	=>	0),
									1	=>	array(	'confian�a'	=>	-1,
													'esfor�o'	=>	-2,
													'independ�ncia'	=>	0),
									2	=>	array(	'confian�a'	=>	1,
													'esfor�o'	=>	-1,
													'independ�ncia'	=>	0),
									3	=>	array(	'confian�a'	=>	2,
													'esfor�o'	=>	1,
													'independ�ncia'	=>	0),
									4	=>	array(	'confian�a'	=>	3,
													'esfor�o'	=>	2,
													'independ�ncia'	=>	0)		);
?>
<?php			/*--------		dados ddb		--------*/
$fp_ddb						=	array(
									0	=>	array(	'confian�a'	=>	-3,
													'esfor�o'	=>	-3,
													'independ�ncia'	=>	0),
									1	=>	array(	'confian�a'	=>	-1,
													'esfor�o'	=>	-2,
													'independ�ncia'	=>	0),
									2	=>	array(	'confian�a'	=>	1,
													'esfor�o'	=>	-1,
													'independ�ncia'	=>	0),
									3	=>	array(	'confian�a'	=>	2,
													'esfor�o'	=>	1,
													'independ�ncia'	=>	0),
									4	=>	array(	'confian�a'	=>	3,
													'esfor�o'	=>	2,
													'independ�ncia'	=>	0)		);

?>
<?php			/*--------		dados tempo		--------*/
//tempo_usuario <= tempo_turma?
$tp_tempo_true			=	array(	'confian�a'	=>	0,
									'esfor�o'	=>	-1,
									'independ�ncia'	=>	1);

$tp_tempo_false			=	array(	'confian�a'	=>	0,
									'esfor�o'	=>	1,
									'independ�ncia'	=>	-1);
?>