<?php 
/*
* Cria um arquivo de log nesta pasta com o nome e conteúdo recebido.
* Não faz nenhum tipo de formatação.
*/

session_start();

$nome_arquivo = $_GET['nomeArquivo'];
$conteudo_arquivo = $_GET['conteudoArquivo'];

header('Content-Type: text/html; charset=utf-8');
header('Content-Disposition: attachmment; filename="'.$nome_arquivo.'"');
/*
$conteudoEmArray = explode('\r', $conteudo_arquivo);

for($i=0; $i<count($conteudoEmArray); $i++){
	echo $conteudoEmArray[$i].'\r\n';
}
echo '\n\n\n';*/
echo $conteudo_arquivo;
?>