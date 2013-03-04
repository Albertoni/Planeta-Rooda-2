<style>
th.cc		{	width:	315px;
				background-color: #444444;
				color:	#ffffff;
				text-align:	center;	}
td.cc1		{	width:	422px;
				text-align:	left;
				background-color: #000000;
				color:	#ffffff;	}
td.cc2		{	background-color: #444444;
				font-size:	13px;
				color:	#ffffff;
				vertical-align:	middle;	}
</style>

<table>
<?php
function monta_tabela($str){
	$legendas	=	array(
						'NA'	=>	'Número de acessos',
						'NV'	=>	'Número de vistas ao tópico',
						'FP'	=>	'Frequência de participação na ferramenta',
						'MPF'	=>	'Modo de participação (formador)',
						'MPC'	=>	'Modo de participação (colega)',
						'MS'	=>	'Geração de mensagens no fórum',
						'TO'	=>	'Geração de tópicos no fórum',
						'AS'	=>	'Abertura de sala',
						'PA'	=>	'Pedidos de ajuda ou presta ajuda',
						'TP'	=>	'Tempo de permanência na sessão'
					);

	$str = explode("&",$str);		//separa string por linhas, e 'html-eia' as linhas
		unset($str[count($str)-1]);	//retira o lixo do fim da string
	for($i=0;$i<count($str);$i++){
		$strt = explode(";",$str[$i]);

$c	= mb_strtolower(substr($strt[0],strpos($strt[0],"=")+1));
$sferr	= mb_strtoupper(substr($strt[1],strpos($strt[1],"=")+1));
$val	= substr(	$strt[2],	strpos($strt[2],"=")+1	);
$min	= substr(	$strt[3],	strpos($strt[3],"=")+1	);
$max	= substr(	$strt[4],	strpos($strt[4],"=")+1	);
	$val = number_format(100*max($val/$min,$val/$max),2)."%";

		$str[$i]	=	"<span onclick='alert(\"";
		$str[$i]	.=	$sferr.": ".$legendas[$sferr]."\")'>";
		$str[$i]	.=	"<img src='fatores/quadradinho_2.php?crc=".$c.
						"'  style='width: 10px; height: 10px;' /> ".$sferr.": ";
		$str[$i]	.=	$val."</span>";
	}
	$str = implode("<br />",$str);
	echo	$str;
}
?>
<?php
$max_f	=	$extremos['max']['for'];		$min_f	=	$extremos['min']['for'];
$max_b	=	$extremos['max']['btp'];		$min_b	=	$extremos['min']['btp'];
$max_d	=	$extremos['max']['ddb'];		$min_d	=	$extremos['min']['ddb'];

$keys = array('c' => 'confiança', 'e' => 'esforço', 'i' => 'independência');

$for = array();				$btp = array();				$ddb = array();
foreach($export as $reg=>$data){
	foreach($data as $fator=>$val)
		if(in_array("$reg.$fator",$grafica)){
			list($_fer,$_subfer) = explode(".",$reg);		//	'ferramenta'.'subferramenta'
			switch($_fer){
				case 'for':		$for[$_subfer][substr($fator,0,1)] = $val;		break;
				case 'ddb':		$ddb[$_subfer][substr($fator,0,1)] = $val;		break;
				case 'btp':		$btp[$_subfer][substr($fator,0,1)] = $val;		break;
				default:														break;
			}
		}
}

$quant_max = 8;					//	quantidade maxima de 
//	formar_forum
$i = 0;
foreach($for as $_sf=>$_ft){	//	subferramenta => fator
	$_ferr = 'for';
	foreach($_ft as $_ff=>$_v){	//	fator => valor
		if($i%$quant_max == 0){
			$_forum[(integer)($i/$quant_max)] = "<img src='fatores/grafbarra.php?";
			$tb_forum[(integer)($i/$quant_max)] = "";
		}
		$_forum[(integer)($i/$quant_max)]	.= "c[]={$_ff}&tit[]=".mb_strtoupper($_sf)."&val[]={$_v}&";
		$tb_forum[(integer)($i/$quant_max)]	.= "c={$_ff};tit={$_sf};val={$_v};".
												"min=".$lim_min[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"max=".$lim_max[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"&";
		$i++;
		if($i%$quant_max == 0)                                                       	//
			$_forum[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";	//
	}                                                                                	//garantia de que sempre vai
}                                                                                    	//fechar a ultima tag de imagem
if($i%$quant_max != 0)                                                               	//
		$_forum[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";    	//

//	formar_bate_papo
$i = 0;
foreach($btp as $_sf=>$_ft){	//	subferramenta => fator
	$_ferr = 'btp';
	foreach($_ft as $_ff=>$_v){	//	fator => valor
		if($i%$quant_max == 0){
			$_btp[(integer)($i/$quant_max)] = "<img src='fatores/grafbarra.php?";
			$tb_btp[(integer)($i/$quant_max)] = "";
		}
		$_btp[(integer)($i/$quant_max)]		.= "c[]={$_ff}&tit[]={$_sf}&val[]={$_v}&";
		$tb_btp[(integer)($i/$quant_max)]	.= "c={$_ff};tit={$_sf};val={$_v};".
												"min=".$lim_min[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"max=".$lim_max[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"&";
		$i++;
		if($i%$quant_max == 0)                                                       	//
			$_btp[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";		//
	}                                                                                	//garantia de que sempre vai
}                                                                                    	//fechar a ultima tag de imagem
if($i%$quant_max != 0)                                                               	//
		$_btp[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";    		//

//	formar_diario_bordo
$i = 0;
foreach($ddb as $_sf=>$_ft){	//	subferramenta => fator
	$_ferr = 'ddb';
	foreach($_ft as $_ff=>$_v){	//	fator => valor
		if($i%$quant_max == 0){
			$_ddb[(integer)($i/$quant_max)] = "<img src='fatores/grafbarra.php?";
			$tb_ddb[(integer)($i/$quant_max)] = "";
		}
		$_ddb[(integer)($i/$quant_max)]		.= "c[]={$_ff}&tit[]={$_sf}&val[]={$_v}&";
		$tb_ddb[(integer)($i/$quant_max)]	.= "c={$_ff};tit={$_sf};val={$_v};".
												"min=".$lim_min[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"max=".$lim_max[$_ferr][mb_strtolower($_sf)][$keys[mb_strtolower($_ff)]].";".
												"&";
		$i++;
		if($i%$quant_max == 0)                                                       	//
			$_ddb[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";		//
	}                                                                                	//garantia de que sempre vai
}                                                                                    	//fechar a ultima tag de imagem
if($i%$quant_max != 0)                                                               	//
		$_ddb[(integer)(($i-1)/$quant_max)] .= "max={$max_f}&min={$min_f}' />";    		//
echo "\t<th colspan='2' class='cc'><b><i>Fórum</i></b></th>\n";
foreach($_forum as $n=>$__f){
	echo 	"\t<tr>\n\t\t<td class='cc1'>{$__f}</td>\n\t\t<td class='cc2'>";
		monta_tabela($tb_forum[$n]);
	echo "</td>\n\t</tr>\n";
}

echo "\t<th colspan='2' class='cc'><b><i>Bate-Papo</i></b></th>\n";
foreach($_btp as $n=>$__f){
	echo 	"\t<tr>\n\t\t<td class='cc1'>{$__f}</td>\n\t\t<td class='cc2'>";
		monta_tabela($tb_btp[$n]);
	echo "</td>\n\t</tr>\n";
}

echo "\t<th colspan='2' class='cc'><b><i>Diário de Bordo</i></b></th>\n";
foreach($_ddb as $n=>$__f){
	echo 	"\t<tr>\n\t\t<td class='cc1'>{$__f}</td>\n\t\t<td class='cc2'>";
		monta_tabela($tb_ddb[$n]);
	echo "</td>\n\t</tr>\n";
}
?>
</table>