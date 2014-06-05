<?php
/*----------------------------------------------------------------------*/
$tempo_u = 	//tempo de sessao por usuario
	db_busca(
"SELECT sum( tempo ) tempo , sum(num) num , codUsuario
FROM (
		SELECT sum( AD.tempo ) tempo, count( * ) num, TU.codUsuario
		FROM AcessosDisciplina AS AD, TurmaUsuario AS TU
		WHERE AD.tempo !=0
		AND AD.codTurma =$codTurma
		AND AD.codTurma = TU.codTurma
		AND AD.codUsuario = TU.codUsuario
		AND TU.associacao = 'A' AND AD.codUsuario=$codUs
		GROUP BY AD.codTurma, AD.codUsuario
			UNION
		SELECT 30 * count( * ) , count( * ) num, TU.codUsuario
		FROM AcessosDisciplina AS AD, TurmaUsuario AS TU
		WHERE AD.tempo =0
		AND AD.codTurma =$codTurma
		AND AD.codTurma = TU.codTurma
		AND AD.codUsuario = TU.codUsuario
		AND TU.associacao = 'A' AND AD.codUsuario=$codUs
		GROUP BY AD.codTurma, AD.codUsuario
	) AS tabela1
GROUP BY codUsuario
"	
	);
$tempo_u = $tempo_u[0]['tempo'];
/*----------------------------------------------------------------------*/
$tempo_t = 	//tempo de sessao por turma
	db_busca(
"SELECT sum(AD.tempo) tempo
FROM AcessosDisciplina as AD
WHERE AD.tempo!=0 AND AD.codTurma=$codTurma
GROUP BY AD.codTurma

UNION

SELECT 30*count(*)
FROM AcessosDisciplina as AD
WHERE AD.tempo=0 AND AD.codTurma=$codTurma
GROUP BY AD.codTurma
"	
	);
$tempo_t = ($tempo_t[0]['tempo'] + $tempo_t[1]['tempo'])/$num['A'];
/*----------------------------------------------------------------------*/
	$tempo_us = ($tempo_u<=$tempo_t);
/*----------------------------------------------------------------------*/
?>