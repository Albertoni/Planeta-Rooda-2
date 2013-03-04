<?php
require("../bd.php");

// Versão velha que não funcionava.

//function getImageFile($file){
//	$takeFile = fopen($file, "r");
//	$file = fread($takeFile, filesize($file));
//	fclose($takeFile);
//	return $file;
//}
//
//function getfileType($name){
//	$name = explode(".", $name);
//	$name = array_reverse($name);
//	$name = $name[0];
//
//	return $name;
//}
//
//function redirecionar ($aqui) {
//	global $base_loc;
//	header("Location: $base_loc/$aqui");
//	exit;
//}
//
///**
// * Registra (errorlog) um erro fatal do sistema e encerra a página atual.
// * @author Omar
// * @date 2004-02-04
// */
//function deu_pau ($por_que) {
//	echo '</body></html><br><br><br>'."\n\n\n".$por_que.
//		'<br><br>Esse erro foi registrado. Obrigado.';
//	//$fh = fopen('/var/log/rooda/error', 'a');
//	// TODO: incluir username se possível
//	//fwrite($fh, $_SERVER['REMOTE_ADDR'].' '.date('Ymd-His').' '.$por_que."\n");
//	//fclose($fh);
//	exit();
//}
//
///**
// * Registra (warnlog) um erro contornáveal do sistema e continua.
// * @author Omar
// * @date 2004-02-04
// */
//function deu_merda ($por_que) {
//	// aviso para debug:
//	echo "<div style='background:red;font:bold 10pt verdana;color:white'>[### $por_que ###]</div>";
//	//$fh = fopen('/var/log/rooda/warn', 'a');
//	// TODO: incluir username se possível
//	//fwrite($fh, $_SERVER['REMOTE_ADDR'].' '.date('Ymd-His').' '.$por_que."\n");
//	//fclose($fh);
//}
//
//
//function db_conecta () {
//	global $db_h, $db_server, $db_user, $db_pass, $db_db;
//	if ($db_h = mysql_connect ($db_server, $db_user, $db_pass))
//		mysql_select_db ($db_db)
//			or deu_pau("Não consegui selecionar o BD '$db_db'! ".mysql_error());
//	else
//		deu_pau('Não consegui me conectar no MySQL! '.mysql_error());
//	register_shutdown_function('db_desconecta');
//}
//db_conecta();
//
///**
// * Função de inserção de dados no SGBD.
// * @author Omar
// * @date 2004-02-04
// */
//function db_faz ($query) {
//	if ($q = mysql_query($query)) {
//		return @mysql_insert_id();
//	} else {
//		deu_merda("db_faz('$query'): ".mysql_error());
//		return FALSE;
//	}
//}
//
///**
// * Insere o array associativo $dados como um registro em $tabela. 
// * @author Pato
// * @date 2007-08-06
// */
//function db_insere($dados,$tabela) {
//	empty($campos);
//	empty($valores);
//	foreach($dados as $campo => $valor) {
//		$campos[] = $campo;
//		$valores[] = "'" . $valor . "'";
//	}
//	$campos = '(' . implode(',',$campos) . ')';
//	$valores = '(' . implode(',',$valores) . ')';
//	return db_faz("INSERT INTO " . $tabela . " " . $campos . " VALUES " . $valores);
//}
//
//// Só funciona pra tabela erro.
//function db_imagem($imgArq) {
//	$fileContent = getImageFile($imgArq);
//	$uploadedImage = addslashes($fileContent);
//	return $uploadedImage;
//}
//
///**
// * Faz uma busca no SGBD e retorna um array indexado
// * (linhas) de arrays associativos (colunas).
// * @author Omar
// * @date 2004-02-04
// */
//function db_busca($query) {
//	if ($busca = mysql_query($query)) {
//		$num = @mysql_num_rows($busca);
//		$x = array();
//		for ($i=0; $i<$num; $i++)
//			$x[] = mysql_fetch_assoc($busca);
//		return $x;
//	} else {
//		deu_merda("db.inc.php: db_busca('$query'): ".mysql_error());
//		return array();
//	}
//}
//
///**
// * Retorna o número de registros em uma determinada consulta.
// * @author Omar
// * @date 2004-02-04
// */
//function db_conta($query) {
//	if ($busca = mysql_query('SELECT COUNT(*) '.$query)) {
//		$pega = @mysql_fetch_row($busca);
//		return 0 + $pega[0];
//	} else {
//		deu_merda('db_conta(): '.mysql_error().' em SELECT COUNT(*) '.$query);
//		return 0;
//	}
//}
//
///**
// * Torna uma string segura.
// * @author Omar
// * @date 2004-02-05
// */
//function db_escape($str) {
//	return mysql_escape_string($str);
//}
//
///**
// * Encerra a conexão com o SGBD. É chamada otomaticamente.
// * @author Omar
// * @date 2004-02-04
// */
//function db_desconecta() {
//	global $db_h;
//	mysql_close($db_h);
//}-->
//?>
