<?php
	$codTurma	=	11419;
	$codUsuario	=	45959;
	include_once("../sistema.inc.php");
	include_once("../db.inc.php");
?>
<?php		//geral
$_sql['geral_alunos'] =	"
	SELECT count(*) qtde
	FROM TurmaUsuario as TU
	WHERE codTurma=$codTurma AND associacao='A'";
$_sql['geral_acessos_ferramenta'] =	"
	SELECT AF.ferramenta ferramenta, SUM(AF.acessos) acessos, count(*) conta, SUM(AF.contribuicoes) contribuicoes, TU.codUsuario codUsuario
	FROM AcessosFerramenta as AF, TurmaUsuario as TU
	WHERE  	AF.disciplina=$codTurma AND TU.codTurma=AF.disciplina AND AF.codUsuario=TU.codUsuario AND TU.associacao = 'A'
	GROUP BY AF.ferramenta,TU.codUsuario";
?>
<?php		//tempo
$_sql['tempo_sessao'] =	"
	SELECT sum(AD.tempo) tempo, AD.codUsuario
	FROM AcessosDisciplina as AD, TurmaUsuario as TU
	WHERE AD.tempo!=0 AND AD.codTurma=$codTurma AND TU.codTurma=AD.codTurma AND TU.codUsuario=AD.codUsuario AND TU.Associacao = 'A'
	GROUP BY AD.codUsuario
		UNION
	SELECT 30*count(*), TU.codUsuario codUsuario
	FROM AcessosDisciplina as AD, TurmaUsuario as TU
	WHERE AD.tempo=0 AND AD.codTurma=$codTurma AND TU.codTurma=AD.codTurma AND TU.codUsuario=AD.codUsuario AND TU.Associacao = 'A'
	GROUP BY AD.codUsuario";
?>
<?php		//forum
$_sql['for_mensagem_qtd'] = "
	SELECT count(*) num, TU.codUsuario codUsuario
	FROM Forum as F, ForumTopico as FT, ForumMensagem as FM, TurmaUsuario as TU
	WHERE F.codTurma=$codTurma AND FT.codForum=F.codForum AND FM.codTopico=FT.codTopico AND TU.associacao='A' AND TU.codTurma=F.codTurma AND TU.codUsuario=FM.codUsuario
	GROUP BY TU.codUsuario";
$_sql['for_topicos_new_qtd'] = "
	SELECT count(*) num, FT.codUsuario codUsuario
	FROM Forum AS F, ForumTopico AS FT, TurmaUsuario AS TU
	WHERE F.codTurma =$codTurma AND FT.codForum = F.codForum AND TU.codUsuario = FT.codUsuario AND TU.associacao = 'A' AND TU.codTurma=F.codTurma
	GROUP BY FT.codUsuario";
$_sql['for_mensagem_new_qtd'] = "
	SELECT count(*) num, FM.codUsuario codUsuario
	FROM Forum as F, ForumTopico as FT, ForumMensagem as FM, TurmaUsuario as TU
	WHERE 	F.codTurma=$codTurma AND FT.codForum=F.codForum AND FT.codTopico=FM.codTopico AND FM.profCitacao=0 AND TU.codTurma=F.codTurma AND TU.codUsuario=FM.codUsuario AND TU.associacao='A'
	GROUP BY FM.codUsuario";
$_sql['for_mensagem_vistas_qtd'] = "
	SELECT sum(AF.msgVistas) num, TU.codUsuario codUsuario
	FROM AcessoForum as AF, Forum as F, TurmaUsuario as TU
	WHERE F.codForum=AF.codForum AND F.codTurma=$codTurma AND TU.codUsuario=AF.codUsuario AND TU.codTurma=F.codTurma AND TU.associacao='A'
	GROUP BY TU.codUsuario;";
$_sql['for_resposta_formador'] = "
	SELECT COUNT(*) num, TU0.codUsuario
	FROM Forum as F, ForumTopico as FT, ForumMensagem as FM0, ForumMensagem as FM1, TurmaUsuario as TU0, TurmaUsuario as TU1
	WHERE FM0.citou>0 and F.codTurma=$codTurma and FT.codForum=F.codForum and FT.codTopico=FM0.codTopico and TU0.codTurma=F.codTurma and TU1.codTurma=F.codTurma and FM1.codMensagem=FM0.citou and TU1.codUsuario=FM1.codUsuario and TU0.codUsuario=FM0.codUsuario and FM1.codUsuario!=FM0.codUsuario and TU0.associacao='A' and (TU1.associacao='P' OR TU1.associacao='M')
	GROUP BY TU0.codUsuario
	";
$_sql['for_resposta_colega'] = "
	SELECT COUNT(*) num, TU0.codUsuario
	FROM Forum as F, ForumTopico as FT, ForumMensagem as FM0, ForumMensagem as FM1, TurmaUsuario as TU0, TurmaUsuario as TU1
	WHERE FM0.citou>0 and F.codTurma=$codTurma and FT.codForum=F.codForum and FT.codTopico=FM0.codTopico and TU0.codTurma=F.codTurma and TU1.codTurma=F.codTurma and FM1.codMensagem=FM0.citou and TU1.codUsuario=FM1.codUsuario and TU0.codUsuario=FM0.codUsuario and FM1.codUsuario!=FM0.codUsuario and TU0.associacao='A' and TU1.associacao='A'
	GROUP BY TU0.codUsuario
	";
?>	
<?php		//ddb
$_sql['ddb_mensagem_qtd'] = "
	SELECT COUNT(*) num, TU.codUsuario codUsuario
	FROM DDBMensagem as DM, TurmaUsuario as TU
	WHERE	TU.codTurma=$codTurma AND DM.codTurma=TU.codTurma AND TU.associacao='A' AND TU.codUsuario=DM.codUsuario
	GROUP BY DM.codUsuario";
?>
<?php		//bp
$_sql['btp_mensagem_qtd'] = "
	SELECT count(*) num, BPM.codUsuario codUsuario
	FROM BatePapoSala as BPS, BatePapoMensagem as BPM, TurmaUsuario as TU
	WHERE BPS.codTurma=$codTurma AND BPS.codSala=BPM.codSala AND TU.associacao='A' AND TU.codTurma=BPS.codTurma AND TU.codUsuario=BPM.codUsuario
	GROUP BY BPM.codUsuario";
?>
<?php		//coleta de dados
foreach($_sql as $nome=>$query)
	$$nome = db_busca($query);

$num_alunos = $geral_alunos[0]['qtde'];

$index = array(	'for_na',	'for_nv',	'for_fp',	'for_mp_f',	'for_mp_c',	'for_to_t',	'for_to_m',	'btp_fp',	'ddb_fp',	'tp_ses');

$for_na		=	array();
	for($i=0;$i<count($geral_acessos_ferramenta);$i++){
		$tmp = $geral_acessos_ferramenta[$i];
		$for_na[$tmp['codUsuario']][$tmp['ferramenta']] = $tmp['acessos'];
	}

$for_nv		=	array();
	for($i=0;$i<count($for_mensagem_vistas_qtd);$i++){
		$tmp = $for_mensagem_vistas_qtd[$i];
		$for_nv[$tmp['codUsuario']] = $tmp['num'];
	}

$for_fp		=	array();
	for($i=0;$i<count($for_mensagem_qtd);$i++){
		$tmp = $for_mensagem_qtd[$i];
		$for_fp[$tmp['codUsuario']] = $tmp['num'];
	}

$for_mp_f 	=	array();
	for($i=0;$i<count($for_resposta_formador);$i++){
		$tmp = $for_resposta_formador[$i];
		$for_mp_f[$tmp['codUsuario']] = $tmp['num'];
	}

$for_mp_c	=	array();
	for($i=0;$i<count($for_resposta_colega);$i++){
		$tmp = $for_resposta_colega[$i];
		$for_mp_c[$tmp['codUsuario']] = $tmp['num'];
	}

$for_to_t	=	array();
	for($i=0;$i<count($for_topicos_new_qtd);$i++){
		$tmp = $for_topicos_new_qtd[$i];
		$for_to_t[$tmp['codUsuario']] = $tmp['num'];
	}

$for_to_m	=	array();
	for($i=0;$i<count($for_mensagem_new_qtd);$i++){
		$tmp = $for_mensagem_new_qtd[$i];
		$for_to_m[$tmp['codUsuario']] = $tmp['num'];
	}

$btp_fp		=	array();
	for($i=0;$i<count($btp_mensagem_qtd);$i++){
		$tmp = $btp_mensagem_qtd[$i];
		$btp_fp[$tmp['codUsuario']] = $tmp['num'];
	}

$ddb_fp		=	array();
	for($i=0;$i<count($ddb_mensagem_qtd);$i++){
		$tmp = $ddb_mensagem_qtd[$i];
		$ddb_fp[$tmp['codUsuario']] = $tmp['num'];
	}

$tp_ses		=	array();
	for($i=0;$i<count($tempo_sessao);$i++){
		$tmp = $tempo_sessao[$i];
		if(!isset($tp_ses[$tmp['codUsuario']])){
			$tp_ses[$tmp['codUsuario']] = $tmp['tempo'];
		}
		else{
			$tp_ses[$tmp['codUsuario']] += $tmp['tempo'];
		}
	}
?>
<?php
$int = array_keys(array_intersect_key(	$for_na,	$for_nv,	$for_fp,	$for_mp_f,	$for_mp_c,	$for_to_t,	$for_to_m,	$btp_fp,	$ddb_fp,	$tp_ses));
$int = array_keys(array_intersect_key(	$for_na,	$for_nv,	$for_fp,	$for_mp_f,	$for_mp_c,	$for_to_m,	$ddb_fp,	$tp_ses));
$codUsuario = $int[1];
$c = $codUsuario;
?>
<?php		//auxiliares
function normaliza($usuario,$corpo){
       $c = array();           //guardar os contadores num_mensagens => num_pessoas com essa qtde
       $p = array(0=>0);       //guardar o nivel de participacao por quantidade - acumulado
       $r = array(0=>0);       //guardar o nivel de participacao por quantidade (zero ou quartis)
       $q = array();           //guardar a quantidade de mensagens de cada usuario
       $k = array();           //guardar a quantidade de "mensagens por pessoa" q foram enviadas

       foreach($corpo as $user=>$num){
               $k = $num;                      $q[$user] = $num;
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

       $v = $p[(int)$q[$usuario]];
       switch($v){
               case $v<=0:             return 0;
                       break;
               case $v<0.25:   return 1;
                       break;
               case $v<0.50:   return 2;
                       break;
               case $v<0.75:   return 3;
                       break;
               case $v<=1:             return 4;
                       break;
               default:                return 0;
                       break;
       }
}

$__tp = $tp_ses[$codUsuario];
$limitacao      =       2;
       $_corpo = $tp_ses;
       //deleta n=$limitacao extremos minimo e maximo
               for($i=0;$i<$limitacao;$i++){   sort($_corpo);          unset($_corpo[count($_corpo)-1]);                       unset($_corpo[0]);      }                       sort($_corpo);
$normal	=	array(
				0 => 'muito baixo (0%)',
				1 => 'baixo (entre 0% e 25%,inclusive)',
				2 => 'regular (entre 25% e 50%,inclusive)',
				3 => 'regular (entre 50% e 75%,inclusive)',
				4 => 'regular (entre 75% e 100%,inclusive)',
			)
?>
<?php
$return         =       array();

/*                              forum                           */
$return['for_nv']       = $for_nv[$codUsuario]>=(array_sum($for_nv)/$num_alunos)?1:0;
$return['for_mp_f']     = $for_mp_f[$codUsuario]>0?1:0;
$return['for_mp_c']     = $for_mp_c[$codUsuario]>0?1:0;
$return['for_to_t']     = $for_to_t[$codUsuario]>0?1:0;
$return['for_to_m']     = $for_to_m[$codUsuario]>0?1:0;
$return['for_fp']       = normaliza($codUsuario,$for_fp);

/*                              batepapo                        */
$return['btp_fp']       = normaliza($codUsuario,$btp_fp);

/*                              ddb                                     */
$return['ddb_fp']       = normaliza($codUsuario,$ddb_fp);

/*                              tempo                           */
$return['tp_ses']       = $__tp>(array_sum($_corpo)/count($_corpo))?1:0;
var_dump($return);
?>
<table border='1' WIDTH=50%>
	<tr>
		<th colspan='2'>
			Dados da ferramenta FÓRUM
		</th>
	</tr>
	<tr>
		<td>NV
		</td>
		<td><?=$return['for_nv'];?>
		</td>
	</tr>
</table>
<?php
echo '-n v => '.			$return['for_nv']           ."<br>";
echo '-mpf => '.	$return['for_mp_f']         ."<br>";
echo '-mpc => '.	$return['for_mp_c']         ."<br>";
echo '-tot => '.	$return['for_to_t']         ."<br>";
echo '-tom => '.	$return['for_to_m']         ."<br>";
echo '-f p => '.		$return['for_fp']           ."<br>";
echo 'btp<br>';
echo '-f p => '.		$return['btp_fp']           ."<br>";
echo 'ddb<br>';
echo '-f p => '.		$return['ddb_fp']           ."<br>";
echo 'tp<br>';
echo '-ses => '.	$return['tp_ses']           ."<br>";

?>