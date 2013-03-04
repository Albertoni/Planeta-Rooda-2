<?php
$cores = file("fatores.csv");
$indexes = array("confiança","esforço","independência");
$cor = array();

$b = 0;
foreach($cores as $linha){
	list($ferramenta,$subferramenta,$cC1,$cC2,$cC3,$cE1,$cE2,$cE3,$cI1,$cI2,$cI3)
		= explode(";",$linha);
	$cor[$ferramenta.".".$subferramenta.".".$indexes[0]] = array($cC1,$cC2,$cC3);
	$cor[$ferramenta.".".$subferramenta.".".$indexes[1]] = array($cE1,$cE2,$cE3);
	$cor[$ferramenta.".".$subferramenta.".".$indexes[2]] = array($cI1,$cI2,$cI3);
}