<?php
	//Dados recebidos do controller
	include_once("../controller/controllerConsultaSubjetividade.php");

?>
<html>
	<head></head>
	<body>
		<table>
		<tr>
			<th> Per&iacute;odo </th>
			<th> Quadrante </th>
			<th> Subquadrante </th>
			<th> Intensidade </th>
		</tr>
		<?php
			for($i=0; $i<$numeroIntervalosTempo; $i++){
				echo 	"<tr>
							<td> ".$i." </td>
							<td> ".$subjetividade->getEmocao($i)->getQuadrante()." </td>
							<td> ".$subjetividade->getEmocao($i)->getSubquadrante()." </td>
							<td> ".$subjetividade->getEmocao($i)->getIntensidade()." </td>
						</tr>";
			}
		?>
		</table>
	</body>
</html>