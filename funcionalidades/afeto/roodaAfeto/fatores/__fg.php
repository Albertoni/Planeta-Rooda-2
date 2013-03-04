<script>
	function descreve(elemento){
		var descricao = new Array();
			descricao["for_na"] =	"NA (Número de acessos):\ndefinido pelo ato de abrir ou entrar na funcionalidade.";
			descricao["for_nv"] =	"NV (Número de vistas ao tópico):\ndefinido pela quantidade de vezes em que um usuário visitou um tópico do fórum.";
			descricao["for_fp"] =	"FP (Frequência de participação):\nobtida pelo número de vezes em que o aluno participa no Fórum.";
			descricao["for_mp_f"] =	"MP (Modo de participação):\nverificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum formador.";
			descricao["for_mp_c"] =	"MP (Modo de participação):\nverificado a partir da forma como o aluno participa na funcionalidade, isto é, o modo como ocorre a interação - se respondeu mensagem de algum colega.";
			descricao["for_to_t"] =	"TO (Geração de tópicos):\nindica a criação de novos tópicos para a funcionalidade Fórum.";
			descricao["for_to_m"] =	"TO (Geração de mensagens):\nindica a criação de novas mensagens em um tópico para a funcionalidade Fórum.";
			descricao["btp_fp"] =	"FP (Frequência de participação):\nobtida pelo número de vezes em que o aluno participa no Bate-Papo.";
			descricao["ddb_fp"] =	"FP (Frequência de participação):\nobtida pelo número de vezes em que o aluno participa no Diário de Bordo.";
			descricao["tp_tp"] =	"TP (Tempo de permanência na sessão):\nrepresenta a média de tempo despendido em uma sessão.";
		document.getElementById("explicacao").innerHTML = descricao[elemento];
	}
</script>
<style>
.titulo_fatores{
	
}
#tabela_fatores{
	width: 30%;
}
.col_1{
	border: 1px dotted black;
	width: 20%;
	text-align: center;
}
.col_2{
	border: 1px dotted black;
	width: 80%;
	text-align: center;
}
</style>
<?php		include_once("dados.php");		?>
<?php
	$codUsuario	=	45959;
	$codus		=	$codUsuario;
	$num_alunos	=	33;
?>
<?php		//auxiliares
error_reporting(E_ALL&!E_NOTICE&!E_WARNING);

function normaliza($usuario,$corpo){
	$c = array();		//guardar os contadores num_mensagens => num_pessoas com essa qtde
	$p = array(0=>0);	//guardar o nivel de participacao por quantidade - acumulado
	$r = array(0=>0);	//guardar o nivel de participacao por quantidade (zero ou quartis)
	$q = array();		//guardar a quantidade de mensagens de cada usuario
	$k = array();		//guardar a quantidade de "mensagens por pessoa" q foram enviadas

	foreach($corpo as $user=>$num){
		$k = $num;			$q[$user] = $num;
		if(array_key_exists($k,$c))
			$c[$k]++;
		else
			$c[$k] = 1;
	}
	ksort($c);
	$k = array_keys($c);
	// print_r($k);

	for($i=0;$i<count($k);$i++){
		$r[ $k[$i] ] = $c[$k[$i]]/array_sum($c);
		$p[ $k[$i] ] = $r[ $k[$i] ] + $p[ $k[$i-1] ];
	}

	$v = $p[(int)$q[$usuario]];
	switch($v){
		case $v<=0:
		case (!isset($p[(int)$q[$usuario]])):
			// return 0;
			return "Não participou";
			break;
		case $v<0.25:
			// return 1;
			return "Ínfima";
			break;
		case $v<0.50:
			// return 2;
			return "Pouco ativa";
			break;
		case $v<0.75:
			// return 3;
			return "Ativa";
			break;
		case $v<=1:	
			// return 4;
			return "Extremamente ativa";
			break;
		default:	
			// return 0;
			return "(Ocorreu um problema. Favor entrar em contato com o suporte.)";
			break;
	}
}

$indexes = array("for"=>"Fórum",	"btp"=>"Bate-Papo",	"ddb"=>"Diário de Bordo",	"tp"=>"Tempo");

$__tp = $tp_tp[$codus];
$limitacao	=	2;
	$_corpo = $tp_tp;
	//deleta n=$limitacao extremos minimo e maximo
		for($i=0;$i<$limitacao;$i++){	sort($_corpo);		unset($_corpo[count($_corpo)-1]);			unset($_corpo[0]);	}			sort($_corpo);
?>
<?php
$return		=	array();

/*				forum				*/
$return['for_nv']       = $for_nv[$codUsuario]>=(array_sum($for_nv)/$num_alunos)?
							"números de acessos igual ou superior à média":
							"números de acessos inferior à média";
$return['for_mp_f']     = $for_mp_f[$codUsuario]>0?
							"respondeu formador":
							"não respondeu formador";
$return['for_mp_c']     = $for_mp_c[$codUsuario]>0?
							"respondeu colega":
							"não respondeu colega";
$return['for_to_t']     = $for_to_t[$codUsuario]>0?
							"criou tópico":
							"não criou tópico";
$return['for_to_m']     = $for_to_m[$codUsuario]>0?
							"criou mensagem":
							"não criou mensagem";
$return['for_fp']       = normaliza($codUsuario,$for_fp);

/*                              batepapo                        */
$return['btp_fp']       = normaliza($codUsuario,$btp_fp);

/*                              ddb                                     */
$return['ddb_fp']       = normaliza($codUsuario,$ddb_fp);

/*                              tempo                           */
$return['tp_tp']       = $__tp>(array_sum($_corpo)/count($_corpo))?
							"tempo médio de sessão superior à média da turma":
							"tempo médio de sessão inferior ou igual à média da turma";

var_dump($return);
?>
	<center>
<table id='tabela_fatores'>
<?php
	$_fer_atual = "";
	$keys = array_keys($return);

	foreach($indexes as $__ind=>$__fer){
		for($i=0;$i<count($keys);$i++){
			$_index = explode("_",$keys[$i]);
			if($_index[0]==$__ind){
				if($_fer_atual!=$_index[0]){
					$_fer_atual = $_index[0];
					echo "\t<tr>\n\t\t<th colspan='2' class='titulo_fatores'>\n\t\t\tDados baseados em: ".mb_strtoupper($__fer)."\n\t\t</th>\n\t</tr>\n";
				}
				echo "\t<tr>\n\t\t<td class='col_1'>\n";
					echo "\t\t\t<span onclick='javascript:descreve(\"{$keys[$i]}\")'>".mb_strtoupper($_index[1]);
						echo count($_index)>2?	"(".mb_strtoupper($_index[2]).")":"";
					echo "</span>\n";
				echo "\t\t</td>\n\t\t<td class='col_2'>\n";
				echo "\t\t\t{$return[$keys[$i]]}\n";
				echo "\t\t</td>\n\t</tr>\n";
			}
		}
	}
?>
	<tr>
		<td colspan='2'><div id='explicacao'></div></td>
	</tr>
</table>
	</center>