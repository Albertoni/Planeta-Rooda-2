<?php	//funções extras
function cont_freq($b,$num,$codUs){	//argumentos: BUSCA, QUANTIDADE, CONTADOR, CHAVES -> mexe na memória direto
	$c = array();		//guardar os contadores num_mensagens => num_pessoas com essa qtde
	$p = array(0=>0);	//guardar o nivel de participacao de 0 a 4 (zero ou quartis) - acumulados
	$r = array(0=>0);	//guardar o nivel de participacao de 0 a 4 (zero ou quartis) - parciais
	$q = array();		//guardar a quantidade de mensagens de cada usuario
	$k = array();		//guardar a quantidade de "mensagens por pessoa" q foram enviadas

	for($i=0;$i<count($b);$i++){
		$k = $b[$i]['num'];			$q[$b[$i]['codus']] = $b[$i]['num'];
		if(array_key_exists($k,$c))
			$c[$k]++;
		else
			$c[$k] = 1;
	}
	ksort($c);
	$k = array_keys($c);

	for($i=0;$i<count($k);$i++){
		$r[ $k[$i] ] = $c[$k[$i]]/array_sum($c);
		$p[ $k[$i] ] = $r[ $k[$i] ] + $p[ $k[$i-1] ];
	}

	$v = $p[(int)$q[$codUs]];
	switch($v){
		case $v<=0:		$ret =  0;
			break;
		case $v<0.25:	$ret =  1;
			break;
		case $v<0.50:	$ret =  2;
			break;
		case $v<0.75:	$ret =  3;
			break;
		case $v<=1:		$ret =  4;
			break;
		default:		$ret =  0;
			break;
	}
	return $ret;
}

function normaliza($value,$lim0,$lim1,$lim2,$lim3,$lim4,$lim5){
	$lim = array();
	$lim[0] = $lim0;	$lim[1] = $lim1;	$lim[2] = $lim2;	$lim[3] = $lim3;	$lim[4] = $lim4;	$lim[5] = $lim5;
	
	$r1 = ($value>=0)?	($value/($lim[5])):
						(-1*$value/$lim[0]);
	
	switch($value){
		case($value>=$lim[4] and $value<=$lim[5]):
				$r2	=	"malto";
				break;
		case($value>=$lim[3] and $value<$lim[4]):
				$r2	=	"alto";
				break;
		case($value>=$lim[2] and $value<$lim[3]):
				$r2	=	"medio";
				break;
		case($value>=$lim[1] and $value<$lim[2]):
				$r2	=	"baixo";
				break;
		case($value>=$lim[0] and $value<$lim[1]):
				$r2	=	"malto";
		default:
				break;
	}

	return array($r1,$r2,$value,max($value/$lim0,$value/$lim5));
}
?>
<?php
/*----------------------------------------------------------------------*/
$num = array();			//quantidade de pessoas por 'associacao' na turma
$ar =
	db_busca(
"SELECT associacao Associacao, count(*) Qtde 
FROM TurmaUsuario 
WHERE codTurma=$codTurma 
GROUP BY associacao"
	);
for($i=0;$i<count($ar);$i++){	$num[strtoupper($ar[$i]['Associacao'])] = $ar[$i]['Qtde'];	}
/*----------------------------------------------------------------------*/
$ferr_us =	array();	//ferramentas - acesso por usuario
$ferr =
	db_busca(	
"SELECT AF.ferramenta Ferramenta, sum(AF.acessos) Acessos, AF.contribuicoes Contribuicoes 
FROM AcessosFerramenta as AF
WHERE AF.codUsuario=$codUs AND AF.disciplina=$codTurma
GROUP BY AF.ferramenta"
	);
for($i=0;$i<count($ferr[$i]);$i++){
	$ferr_us[$ferr[$i]['Ferramenta']] = 
		array(
			'acessos' =>  $ferr[$i]['Acessos'],
			'contribuicoes' => $ferr[$i]['Contribuicoes']);
}
/*----------------------------------------------------------------------*/
$ferr_geral = array();	//ferramentas - acesso por turma
$ferr =
	db_busca(	
"SELECT AF.ferramenta Ferramenta, SUM(AF.acessos) Acessos, count(*) C, SUM(AF.contribuicoes) Contribuicoes 
FROM AcessosFerramenta as AF
WHERE  	AF.disciplina=$codTurma
		AND AF.codUsuario IN (	SELECT TF.codUsuario
								FROM TurmaUsuario as TF
								WHERE TF.associacao = 'A' AND TF.codTurma=$codTurma)
GROUP BY AF.ferramenta"
	);
for($i=0;$i<count($ferr[$i]);$i++){
	$ferr_geral[$ferr[$i]['Ferramenta']] = 
		array(
			'acessos' => $ferr[$i]['Acessos'],
			'contribuicoes' => $ferr[$i]['Contribuicoes'],
			'alunos' => $ferr[$i]['C']);
}
/*----------------------------------------------------------------------*/
?>