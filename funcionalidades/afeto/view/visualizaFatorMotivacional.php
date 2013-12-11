<?php

include "../controller/controllerConsultaFatorMotivacional.php";
require_once "../model/Motivacao/FuncionalidadeFatorMotivacional.php";

/**
* Transforma um fator motivacional em uma célula de tabela, cuidando os três casos possíveis:
* 	1) Caso em que está implementado e funcionando: retorna o valor do fator.
* 	2) Caso em que deveria estar implementado, mas ainda não está: retorna "Não implementado.".
* 	3) Caso em que não deveria estar implementado, pois não se aplica: retorna "Não se aplica.".
* @param {int, null, array}	$fator	O fator que será identificado.
* @return {int, String}	O valor do fator ou uma String explicando por que não há um valor para o fator.
*/
function fatorParaCelula($fator){
	if($fator == FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE){
		return "N&atilde;o se aplica.";
	} else if($fator == array()){
		return "N&atilde;o implementado.";
	} else {
		return $fator;
	}
}

/**
* Análogo à anterior, mas retornará 0 se o fator não for adequado (por um dos vários motivos possíveis).
* @param {int, null, array}	$fator	O fator que será identificado.
* @return int	O valor do fator.
*/
function fatorParaValor($fator){
	if($fator == FuncionalidadeFatorMotivacional::VARIAVEL_INDEFINIDA_PARA_FUNCIONALIDADE){
		return 0;
	} else if($fator == array()){
		return 0;
	} else {
		return $fator;
	}
}

?>
<html>
	<head>
		<script>
			var indicesFuncionalidades = new Array();
			indicesFuncionalidades["BIBLIOTECA"] = 0;
			indicesFuncionalidades["BLOG"] = 0;
			indicesFuncionalidades["FORUM"] = 0;
			indicesFuncionalidades["PORTFOLIO"] = 0;
			indicesFuncionalidades["APARENCIA"] = 0;
			indicesFuncionalidades["ARTE"] = 0;
			indicesFuncionalidades["PERGUNTAS"] = 0;
			indicesFuncionalidades["AULAS"] = 0;
			indicesFuncionalidades["PLAYER"] = 0;
		
			/**
			* Mostra o indice dado da funcionalidade dada.
			*/
			function mostrar(_funcionalidade, _indice){
				var funcionalidade = document.getElementById(_funcionalidade+""+_indice);
				funcionalidade.style.display = "inline";
				funcionalidade = document.getElementById(_funcionalidade+""+indicesFuncionalidades[_funcionalidade+""]);
				funcionalidade.style.display = "none";
				indicesFuncionalidades[_funcionalidade+""] = _indice;
			}
		</script>
	</head>
	<body>
		<?php
		$arrayIds = array("BIBLIOTECA", "BLOG", "FORUM", "PORTFOLIO", "APARENCIA", "ARTE", "PERGUNTAS", "AULAS", "PLAYER");
		$arrayFatores = array(	$fatorMotivacional->getFatoresBiblioteca(),
								$fatorMotivacional->getFatoresBlog(),
								$fatorMotivacional->getFatoresForum(),
								$fatorMotivacional->getFatoresPortfolio(),
								$fatorMotivacional->getFatoresAparencia(),
								$fatorMotivacional->getFatoresArte(),
								$fatorMotivacional->getFatoresPerguntas(),
								$fatorMotivacional->getFatoresAulas(),
								$fatorMotivacional->getFatoresPlayer());
		$somasFatores = array();
		$somasFatores["confianca"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		$somasFatores["esforco"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		$somasFatores["independencia"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		
		$somasTodos = array();
		$somasTodos["confianca"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		$somasTodos["esforco"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		$somasTodos["independencia"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
		
		for($r=0; $r<9; $r++){
			$id = $arrayIds[$r];
			$fatores = $arrayFatores[$r];
			echo "<div style=\"border: 1px solid black;\">"; 
			echo "<table>";
			echo "<tr ><td>";
			$somasFatores = array();
			$somasFatores["confianca"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
			$somasFatores["esforco"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
			$somasFatores["independencia"] = array("NA"=>0, "FP"=>0, "MP"=>0, "PA"=>0, "TO"=>0, "NV"=>0, "TP"=>0);
			
			for($i=0; $i<count($fatores); $i++){
				$somasFatores["confianca"]["NA"] += fatorParaValor($fatores[$i]["confianca"]["NA"]);
				$somasFatores["confianca"]["FP"] += fatorParaValor($fatores[$i]["confianca"]["FP"]);
				$somasFatores["confianca"]["MP"] += fatorParaValor($fatores[$i]["confianca"]["MP"]);
				$somasFatores["confianca"]["PA"] += fatorParaValor($fatores[$i]["confianca"]["PA"]);
				$somasFatores["confianca"]["TO"] += fatorParaValor($fatores[$i]["confianca"]["TO"]);
				$somasFatores["confianca"]["NV"] += fatorParaValor($fatores[$i]["confianca"]["NV"]);
				$somasFatores["confianca"]["TP"] += fatorParaValor($fatores[$i]["confianca"]["TP"]);
				
				$somasFatores["esforco"]["NA"] += fatorParaValor($fatores[$i]["esforco"]["NA"]);
				$somasFatores["esforco"]["FP"] += fatorParaValor($fatores[$i]["esforco"]["FP"]);
				$somasFatores["esforco"]["MP"] += fatorParaValor($fatores[$i]["esforco"]["MP"]);
				$somasFatores["esforco"]["PA"] += fatorParaValor($fatores[$i]["esforco"]["PA"]);
				$somasFatores["esforco"]["TO"] += fatorParaValor($fatores[$i]["esforco"]["TO"]);
				$somasFatores["esforco"]["NV"] += fatorParaValor($fatores[$i]["esforco"]["NV"]);
				$somasFatores["esforco"]["TP"] += fatorParaValor($fatores[$i]["esforco"]["TP"]);
				
				$somasFatores["independencia"]["NA"] += fatorParaValor($fatores[$i]["independencia"]["NA"]);
				$somasFatores["independencia"]["FP"] += fatorParaValor($fatores[$i]["independencia"]["FP"]);
				$somasFatores["independencia"]["MP"] += fatorParaValor($fatores[$i]["independencia"]["MP"]);
				$somasFatores["independencia"]["PA"] += fatorParaValor($fatores[$i]["independencia"]["PA"]);
				$somasFatores["independencia"]["TO"] += fatorParaValor($fatores[$i]["independencia"]["TO"]);
				$somasFatores["independencia"]["NV"] += fatorParaValor($fatores[$i]["independencia"]["NV"]);
				$somasFatores["independencia"]["TP"] += fatorParaValor($fatores[$i]["independencia"]["TP"]);
				
				echo "	<div style=\"display:".($i==0? "inline" : "none")."\" id=\"".$id."".$i."\">
							".$id."<br>
							Intervalo de tempo ".$i." de ".$numeroIntervalosTempo."
							<table border=\"1\" >
								<th>
									<td>NA</td>
									<td>FP</td>
									<td>MP</td>
									<td>PA</td>
									<td>TO</td>
									<td>NV</td>
									<td>TP</td>
								</th>
								<tr>
									<td>Confianca</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["NA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["FP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["MP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["PA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["TO"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["NV"])."</td>
									<td>".fatorParaCelula($fatores[$i]["confianca"]["TP"])."</td>
								</tr>
								<tr>
									<td>Esforco</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["NA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["FP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["MP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["PA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["TO"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["NV"])."</td>
									<td>".fatorParaCelula($fatores[$i]["esforco"]["TP"])."</td>
								</tr>
								<tr>
									<td>Independencia</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["NA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["FP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["MP"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["PA"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["TO"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["NV"])."</td>
									<td>".fatorParaCelula($fatores[$i]["independencia"]["TP"])."</td>
								</tr>
							</table>
							<button type=\"button\" onclick=\"mostrar('".$id."',indicesFuncionalidades['".$id."']-1);\">Anterior!</button>
							<button type=\"button\" onclick=\"mostrar('".$id."',indicesFuncionalidades['".$id."']+1);\">Proximo!</button>
							<br>
						</div>";
				} 
				echo "</td><td>";
				echo "Somas
							<table border=\"1\" >
								<th>
									<td>NA</td>
									<td>FP</td>
									<td>MP</td>
									<td>PA</td>
									<td>TO</td>
									<td>NV</td>
									<td>TP</td>
								</th>
								<tr>
									<td>Confianca</td>
									<td>".$somasFatores["confianca"]["NA"]."</td>
									<td>".$somasFatores["confianca"]["FP"]."</td>
									<td>".$somasFatores["confianca"]["MP"]."</td>
									<td>".$somasFatores["confianca"]["PA"]."</td>
									<td>".$somasFatores["confianca"]["TO"]."</td>
									<td>".$somasFatores["confianca"]["NV"]."</td>
									<td>".$somasFatores["confianca"]["TP"]."</td>
								</tr>
								<tr>
									<td>Esforco</td>
									<td>".$somasFatores["esforco"]["NA"]."</td>
									<td>".$somasFatores["esforco"]["FP"]."</td>
									<td>".$somasFatores["esforco"]["MP"]."</td>
									<td>".$somasFatores["esforco"]["PA"]."</td>
									<td>".$somasFatores["esforco"]["TO"]."</td>
									<td>".$somasFatores["esforco"]["NV"]."</td>
									<td>".$somasFatores["esforco"]["TP"]."</td>
								</tr>
								<tr>
									<td>Independencia</td>
									<td>".$somasFatores["independencia"]["NA"]."</td>
									<td>".$somasFatores["independencia"]["FP"]."</td>
									<td>".$somasFatores["independencia"]["MP"]."</td>
									<td>".$somasFatores["independencia"]["PA"]."</td>
									<td>".$somasFatores["independencia"]["TO"]."</td>
									<td>".$somasFatores["independencia"]["NV"]."</td>
									<td>".$somasFatores["independencia"]["TP"]."</td>
								</tr>
							</table>
							<br>";
				echo "</td></tr>";
				echo "</table>";
				echo "</div>";
				
				$somasTodos["confianca"]["NA"] += $somasFatores["confianca"]["NA"];
				$somasTodos["confianca"]["FP"] += $somasFatores["confianca"]["FP"];
				$somasTodos["confianca"]["MP"] += $somasFatores["confianca"]["MP"];
				$somasTodos["confianca"]["PA"] += $somasFatores["confianca"]["PA"];
				$somasTodos["confianca"]["TO"] += $somasFatores["confianca"]["TO"];
				$somasTodos["confianca"]["NV"] += $somasFatores["confianca"]["NV"];
				$somasTodos["confianca"]["TP"] += $somasFatores["confianca"]["TP"];
				
				$somasTodos["esforco"]["NA"] += $somasFatores["esforco"]["NA"];
				$somasTodos["esforco"]["FP"] += $somasFatores["esforco"]["FP"];
				$somasTodos["esforco"]["MP"] += $somasFatores["esforco"]["MP"];
				$somasTodos["esforco"]["PA"] += $somasFatores["esforco"]["PA"];
				$somasTodos["esforco"]["TO"] += $somasFatores["esforco"]["TO"];
				$somasTodos["esforco"]["NV"] += $somasFatores["esforco"]["NV"];
				$somasTodos["esforco"]["TP"] += $somasFatores["esforco"]["TP"];
				
				$somasTodos["independencia"]["NA"] += $somasFatores["independencia"]["NA"];
				$somasTodos["independencia"]["FP"] += $somasFatores["independencia"]["FP"];
				$somasTodos["independencia"]["MP"] += $somasFatores["independencia"]["MP"];
				$somasTodos["independencia"]["PA"] += $somasFatores["independencia"]["PA"];
				$somasTodos["independencia"]["TO"] += $somasFatores["independencia"]["TO"];
				$somasTodos["independencia"]["NV"] += $somasFatores["independencia"]["NV"];
				$somasTodos["independencia"]["TP"] += $somasFatores["independencia"]["TP"];
			}
		
					echo "Somas de Todos
							<table border=\"1\" >
								<th>
									<td>NA</td>
									<td>FP</td>
									<td>MP</td>
									<td>PA</td>
									<td>TO</td>
									<td>NV</td>
									<td>TP</td>
								</th>
								<tr>
									<td>Confianca</td>
									<td>".$somasTodos["confianca"]["NA"]."</td>
									<td>".$somasTodos["confianca"]["FP"]."</td>
									<td>".$somasTodos["confianca"]["MP"]."</td>
									<td>".$somasTodos["confianca"]["PA"]."</td>
									<td>".$somasTodos["confianca"]["TO"]."</td>
									<td>".$somasTodos["confianca"]["NV"]."</td>
									<td>".$somasTodos["confianca"]["TP"]."</td>
								</tr>
								<tr>
									<td>Esforco</td>
									<td>".$somasTodos["esforco"]["NA"]."</td>
									<td>".$somasTodos["esforco"]["FP"]."</td>
									<td>".$somasTodos["esforco"]["MP"]."</td>
									<td>".$somasTodos["esforco"]["PA"]."</td>
									<td>".$somasTodos["esforco"]["TO"]."</td>
									<td>".$somasTodos["esforco"]["NV"]."</td>
									<td>".$somasTodos["esforco"]["TP"]."</td>
								</tr>
								<tr>
									<td>Independencia</td>
									<td>".$somasTodos["independencia"]["NA"]."</td>
									<td>".$somasTodos["independencia"]["FP"]."</td>
									<td>".$somasTodos["independencia"]["MP"]."</td>
									<td>".$somasTodos["independencia"]["PA"]."</td>
									<td>".$somasTodos["independencia"]["TO"]."</td>
									<td>".$somasTodos["independencia"]["NV"]."</td>
									<td>".$somasTodos["independencia"]["TP"]."</td>
								</tr>
							</table>
							<br>";
			
		?>
	</body>
</html>