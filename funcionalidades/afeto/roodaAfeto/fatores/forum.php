<?php
/*----------------------------------------------------------------------*/
$forum_a_u = array();	//msgvistas do usuário - por fórum da turma
$forum_a_t = array();	//msgvistas da turma - por fórum da turma
$ft = //temporário pra buscas
	db_busca(
"SELECT sum(AF.msgVistas) msgVistas, AF.codUsuario
FROM AcessoForum as AF, Forum as F
WHERE F.codForum=AF.codForum AND F.codTurma=$codTurma
GROUP BY AF.codForum,AF.codUsuario"
	);
for($i=0;$i<count($ft);$i++){	$forum_a_t[$ft[$i]['codUsuario']] = $ft[$i]['msgVistas'];	}
$forum_a_u = $forum_a_t[$codUs];
/*----------------------------------------------------------------------*/
$forum_t_t = array();	//mensagens - turma
$forum_t = 			
	db_busca(
"SELECT FM.*, FT.codUsuario codAutor , F.codTurma codTurma
FROM Forum as F, ForumTopico as FT, ForumMensagem as FM, TurmaUsuario as TU
WHERE F.codTurma=$codTurma AND FT.codForum=F.codForum AND 
		FM.codTopico=FT.codTopico AND TU.associacao='A' AND TU.codTurma=$codTurma AND TU.codUsuario=FM.codUsuario and FM.hora between '{$dataini}' AND '{$datafim}'"
	);
for($i=0;$i<count($forum_t);$i++){
	$forum_t_t[$forum_t[$i]['codUsuario']][] = 
		array(
			'mensagem' => $forum_t[$i]['mensagem'],
			'hora' => $forum_t[$i]['hora'],
			'citou' => $forum_t[$i]['citou'],
			'profCitacao' => $forum_t[$i]['profCitacao'],
			'codAutor' => $forum_t[$i]['codAutor']);
}
$forum_t_u = $forum_t_t[$codUs];	//mensagens - usuário
/*----------------------------------------------------------------------*/
$forum_a_topico = 			//usuário criou tópico?
	count(
		db_busca(	"SELECT *	FROM Forum as F, ForumTopico as FT ".
					"WHERE F.codTurma=$codTurma AND FT.codUsuario=$codUs AND FT.codForum=F.codForum"
				)
	)>0?	TRUE:FALSE;
/*----------------------------------------------------------------------*/
$forum_a_mensagem = 		//usuário criou mensagem?
	count(
		db_busca(	"SELECT *	FROM Forum as F, ForumTopico as FT, ForumMensagem as FM ".
					"WHERE F.codTurma=$codTurma AND FM.codUsuario=$codUs AND FT.codForum=F.codForum AND ".
					"FT.codTopico=FM.codTopico AND FM.profCitacao=0 and FM.hora between '{$dataini}' AND '{$datafim}'"
				)
	)>0?	TRUE:FALSE;
/*----------------------------------------------------------------------*/
$b_f = 
	db_busca(
"SELECT count(*) num, FM.codUsuario codus
FROM Forum as F, ForumTopico as FT, ForumMensagem as FM, TurmaUsuario as TU
WHERE F.codTurma=$codTurma AND FT.codForum=F.codForum and FM.hora between '{$dataini}' AND '{$datafim}' AND 
		FM.codTopico=FT.codTopico AND TU.associacao='A' AND TU.codTurma=$codTurma AND TU.codUsuario=FM.codUsuario
GROUP BY FM.codUsuario"
	);
$freq_forum = cont_freq(&$b_f,&$num['A'],$codUs);
/*----------------------------------------------------------------------*/
$acessos_forum =			//numero de acessos do usuario >= media de acessos?
	$ferr_us['forum']['acessos']
	>=
	($ferr_geral['forum']['acessos']/$num['A']);
/*----------------------------------------------------------------------*/
$visitas_forum =			//número de mensagens vistas maior que a média?
	$forum_a_u
	>=
	array_sum($forum_a_t)/$num['A'];
/*----------------------------------------------------------------------*/
$mms = 
	db_busca(
"select	TU.associacao ass, count(*) qtdResp
from	ForumMensagem as FM0, Forum as F, ForumTopico as FT, ForumMensagem as FM1, TurmaUsuario as TU
where	FM0.citou>0 and FM0.codUsuario=$codUs and FT.codTopico=FM0.codTopico
		and FT.codForum=F.codForum and F.codTurma=$codTurma and FM1.codMensagem=FM0.citou
		and TU.codUsuario=FM1.codUsuario and TU.codTurma=$codTurma and FM1.codUsuario!=$codUs 
		and FM1.hora between '{$dataini}' AND '{$datafim}'
group by TU.associacao
"
	);
$qtdd = array('prof'=> 0, 'aluno'=> 0);
for($i=0;$i<count($mms);$i++){
	if(in_array(strtoupper($mms[$i]['ass']),array("P","M")))	$qtdd['prof'] += $mms[$i]['qtdResp'];
	elseif(in_array(strtoupper($mms[$i]['ass']),array("A")))	$qtdd['aluno'] += $mms[$i]['qtdResp'];
}
$resp_formador = $qtdd['prof']>0;			//se respondeu para formador - professor ou monitor
$resp_colega = $qtdd['aluno']>0;			//se respondeu para colega - aluno
/*----------------------------------------------------------------------*/

?>