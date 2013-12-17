<table>
<?php
$read = file("fatores.csv");

$argumentos = array();

foreach($read as $element){
	$element = explode(";",mb_strtolower($element));
	list($ferr,$bublist,$c11,$c12,$c13,$c21,$c22,$c23,$c31,$c32,$c33) = $element;
	$argumentos[$ferr][$sublist]	=	array(	'r1' => $c11, 'g1' => $c12, 'b1' => $c13,
												'r2' => $c21, 'g2' => $c22, 'b2' => $c23,
												'r3' => $c31, 'g3' => $c32, 'b3' => $c33);
}

foreach($argumentos as $l=>$ferramenta){
	echo "<tr><td colspan='100'>{$l}</td></tr>";
	foreach($ferramenta as $sf=>$f){
		echo	"<tr>".
					"<td><img src='grafbarra.php?l={$f['cmax']}a{$f['cmin']}&v=".(($f['cmin']+$f['cmax'])/2)."&c={$f['r1']},{$f['g1']},{$f['b1']}' /></td>".
					"<td><img src='grafbarra.php?l={$f['emax']}a{$f['emin']}&v=".(($f['emin']+$f['emax'])/2)."&c={$f['r2']},{$f['g2']},{$f['b2']}' /></td>".
					"<td><img src='grafbarra.php?l={$f['imax']}a{$f['imin']}&v=".(($f['imin']+$f['imax'])/2)."&c={$f['r3']},{$f['g3']},{$f['b3']}' /></td>".
				"</tr>";
		echo	"<tr>".
					"<td><img src='quadradinho_2.php?r={$f['r1']}&g={$f['g1']}&b={$f['b1']}' />{$sf}*{$f['cmax']},{$f['cmin']}</td>".
					"<td><img src='quadradinho_2.php?r={$f['r2']}&g={$f['g2']}&b={$f['b2']}' />{$sf}*{$f['emax']},{$f['emin']}</td>".
					"<td><img src='quadradinho_2.php?r={$f['r3']}&g={$f['g3']}&b={$f['b3']}' />{$sf}*{$f['imax']},{$f['imin']}</td>".
				"</tr>";
	}
}


?>
</table>