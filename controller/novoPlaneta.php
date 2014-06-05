<?php
/**
* Cria um planeta!
*/

require_once("../class/planeta.php");

$nome = $_GET['nome'];
$aparencia = $_GET['aparencia'];
$ehVisitante = $_GET['ehVisitante'];

$idNovoPlaneta = Planeta::getIdNovo($nome, $aparencia, $ehVisitante);

if($idNovoPlaneta == Planeta::ID_OBJETO_NAO_SALVO){
	echo "não deu";
} else {
	echo "deu";
}
?>