<script>
	function descreve(elemento){
		var descricao = new Array();
			descricao["for_na"] =	"<b>NA (Número de acessos)</b><br />\ndefinido pelo ato de abrir ou entrar na funcionalidade.";
			descricao["for_nv"] =	"<b>NV (Número de vistas ao tópico)</b><br />\ndefinido pela quantidade de vezes em que um usuário visitou um tópico do fórum.";
			descricao["for_fp"] =	"<b>FP (Frequência de participação)</b><br />\níndice probabilístico que relaciona o número de vezes em que o aluno participou, no Fórum, em relação à turma.";
			descricao["for_mp_f"] =	"<b>MP (Modo de participação)</b><br />\nverificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum formador.";
			descricao["for_mp_c"] =	"<b>MP (Modo de participação)</b><br />\nverificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum colega.";
			descricao["for_to_t"] =	"<b>TO (Geração de tópicos)</b><br />\nindica a criação de novos tópicos para a funcionalidade Fórum.";
			descricao["for_to_m"] =	"<b>TO (Geração de mensagens)</b><br />\nindica a criação de novas mensagens em um tópico para a funcionalidade Fórum.";
			descricao["btp_fp"] =	"<b>FP (Frequência de participação)</b><br />\níndice probabilístico que relaciona o número de vezes em que o aluno participou, no Bate-Papo, em relação à turma.";
			descricao["ddb_fp"] =	"<b>FP (Frequência de participação)</b><br />\níndice probabilístico que relaciona o número de vezes em que o aluno participou, no Diário de Bordo, em relação à turma.";
			descricao["tp_tp"] =	"<b>TP (Tempo de permanência na sessão)</b><br />\nrepresenta a média de tempo despendido em uma sessão.";
		document.getElementById("explicacao").innerHTML = descricao[elemento];
	}
</script>
<style>
.titulo_fatores{
	
}
#tabela_fatores{
	width: 100%;
}
a.nolink{
	color:blue;
}
.col_1{
	font-size:80%;
	border: 1px dotted black;
	width: 20%;
	text-align: center;
}
.col_2{
	font-size:80%;
	border: 1px dotted black;
	width: 80%;
	text-align: center;
}
</style>
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
<?php		//btp
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

$index = array(	'for_na',	'for_nv',	'for_fp',	'for_mp_f',	'for_mp_c',	'for_to_t',	'for_to_m',	'btp_fp',	'ddb_fp',	'tp_tp');

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

$tp_tp		=	array();
	for($i=0;$i<count($tempo_sessao);$i++){
		$tmp = $tempo_sessao[$i];
		if(!isset($tp_ses[$tmp['codUsuario']])){
			$tp_tp[$tmp['codUsuario']] = $tmp['tempo'];
		}
		else{
			$tp_tp[$tmp['codUsuario']] += $tmp['tempo'];
		}
	}
?>
<?php		//auxiliares
function pt2_normaliza($usuario,$corpo){
	$normal	=	array(
					0 => 'muito baixo (0%)',
					1 => 'baixo (entre 0% e 25%,inclusive)',
					2 => 'regular (entre 25% e 50%,inclusive)',
					3 => 'alto (entre 50% e 75%,inclusive)',
					4 => 'muito alto (entre 75% e 100%,inclusive)',
				);
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
		   case $v<0.25:
					return $normal[1];
					// return "0% < FP &le; 25%";
					break;
		   case $v<0.50:
					return $normal[2];
					// return "25% < FP &le; 50%";
					break;
		   case $v<0.75:
					return $normal[3];
					// return "50% < FP &le; 75%";
					break;
		   case $v<=1:          
					return $normal[4];
					// return "75% < FP &le; 100%";
					break;
		   case $v<=0:
			default:             
					return $normal[0];
					// return "FP = 0%";
					break;
   }
}

$__tp = $tp_tp[$codUs];
$limitacao      =       2;
       $_corpo = $tp_tp;
		//deleta n=$limitacao extremos minimo e maximo
               for($i=0;$i<$limitacao;$i++){   sort($_corpo);          unset($_corpo[count($_corpo)-1]);                       unset($_corpo[0]);      }                       sort($_corpo);

$habilitados	=	array(
						'for'=>isset($able['forum']),
						'btp'=>isset($able['btp']),
						'ddb'=>isset($able['ddb']),
						'tp' =>1
					);

$__leinds = array(
					"for_na"	=>	"Número de acessos",
					"for_nv"	=>	"Número de vistas ao tópico",
					"for_fp"	=>	"Frequência de participação",
					"for_mp_f"	=>	"Modo de participação",
					"for_mp_c"	=>	"Modo de participação",
					"for_to_t"	=>	"Geração de tópicos",
					"for_to_m"	=>	"Geração de mensagens",
					"btp_fp"	=>	"Frequência de participação",
					"ddb_fp"	=>	"Frequência de participação",
					"tp_tp"		=>	"Tempo de permanência na sessão"
			);

$indexes = array("for"=>"Fórum",	"btp"=>"Bate-Papo",	"ddb"=>"Diário de Bordo",	"tp"=>"Tempo");
?>
<?php
$return		=	array();

/*				forum				*/
$return['for_nv']       = $for_nv[$codUs]>=ceil(array_sum($for_nv)/$num_alunos)?
							"números de acessos é igual ou superior à média <br />(acessos do aluno: ".$for_nv[$codUs]." ; média de acessos da turma: ".ceil(array_sum($for_nv)/$num_alunos).")":
							"números de acessos é inferior à média <br />(acessos do aluno: ".$for_nv[$codUs]." ; média de acessos da turma: ".ceil(array_sum($for_nv)/$num_alunos).")"; 			
$return['for_mp_f']     = $for_mp_f[$codUs]>0?
							"respondeu ao formador":
							"não respondeu ao formador";
$return['for_mp_c']     = $for_mp_c[$codUs]>0?
							"respondeu ao colega":
							"não respondeu ao colega";
$return['for_to_t']     = $for_to_t[$codUs]>0?
							"criou algum tópico":
							"não criou nenhum tópico";
$return['for_to_m']     = $for_to_m[$codUs]>0?
							"criou alguma mensagem":
							"não criou nenhuma mensagem";
$return['for_fp']       = pt2_normaliza($codUs,$for_fp);

/*                              batepapo                        */
$return['btp_fp']       = pt2_normaliza($codUs,$btp_fp);

/*                              ddb                                     */
$return['ddb_fp']       = pt2_normaliza($codUs,$ddb_fp);

/*                              tempo                           */
$return['tp_tp']       = ceil($__tp)>ceil(array_sum($_corpo)/count($_corpo))?
							"tempo médio de sessão é superior à média da turma <br />(tempo médio do aluno: ".
								ceil($__tp)." s; tempo médio da turma: ".ceil(array_sum($_corpo)/count($_corpo))." s)": 
							"tempo médio de sessão é inferior ou igual à média da turma <br />(tempo médio do aluno: ".
								ceil($__tp)." s; tempo médio da turma: ".ceil(array_sum($_corpo)/count($_corpo))." s)";

// var_dump($return);
?>
	<center><br />

<a name	='anch'></a><br />

<table id='tabela_fatores'>
<?php
	$_fer_atual = "";
	$keys = array_keys($return);

	foreach($indexes as $__ind=>$__fer){
		if($habilitados[$__ind]){
			for($i=0;$i<count($keys);$i++){
				$_index = explode("_",$keys[$i]);
				if($_index[0]==$__ind){
					if($_fer_atual!=$_index[0]){
						$_fer_atual = $_index[0];
						echo "\t<tr>\n\t\t<th colspan='2' class='titulo_fatores'>\n\t\t\tDados baseados em: ".mb_strtoupper($__fer)."\n\t\t</th>\n\t</tr>\n";
					}
					echo "\t<tr>\n\t\t<td class='col_1'>\n";
						echo "\t\t\t<a class='nolink' href='#anch' onclick='javascript:descreve(\"{$keys[$i]}\")'>".$__leinds[$keys[$i]];
						//echo "\t\t\t<a class='nolink' href='#anch' onclick='javascript:descreve(\"{$keys[$i]}\")'>".mb_strtoupper($_index[1]);
							// echo count($_index)>2?	"(".mb_strtoupper($_index[2]).")":"";
						echo "</a>\n";
					echo "\t\t</td>\n\t\t<td class='col_2'>\n";
					echo "\t\t\t{$return[$keys[$i]]}\n";
					echo "\t\t</td>\n\t</tr>\n";
				}
			}
			echo "\t<tr><th></th></tr>\n";
			echo "\t<tr><th><br /></th></tr>\n";
		}
	}
?>
	<tr>
		<td colspan='2'>(*) Para descrição mais detalhada do elemento, clique no link</td>
	</tr>
	<tr>
		<td colspan='2'>
			<br /><br />
			<div id='explicacao' height='50px'></div></td>
	</tr>
</table>
	</center>